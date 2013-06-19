<?php

namespace simProduction\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\HttpFoundation\Request;
use simProduction\HomeBundle\Entity\Sector;
use simProduction\HomeBundle\Entity\Mensaje;
use simProduction\HomeBundle\Entity\Empresa;
use simProduction\HomeBundle\Entity\Venta;
use simProduction\HomeBundle\Entity\Usuario;
use simProduction\HomeBundle\Entity\Tarea;
use simProduction\HomeBundle\Entity\Prestamo;
use simProduction\HomeBundle\Entity\conocimientoComprado;
use simProduction\HomeBundle\Entity\MovimientoFinanciero;
use simProduction\HomeBundle\Form\VentaType;
use simProduction\HomeBundle\Form\LoteType;
use simProduction\HomeBundle\Form\EmpresaType;
use simProduction\HomeBundle\Form\UsuarioType;
use simProduction\HomeBundle\Form\MensajeType;
use simProduction\HomeBundle\Form\PrestamoType;


class BancoController extends Controller
{
	public function bancoAction(Request $request)  { 
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		$movimientos =  $em->getRepository('simProductionHomeBundle:MovimientoFinanciero')->findBy(array('empresa' => $empresa_id),array('fecha' => 'DESC','id' => 'DESC'));
		$capital_banco = $em->getRepository('simProductionHomeBundle:Banco')->findOneBy(array('id' => 1))->getCapital();
		//funcion para hallar el interés
		$interes = round(1000000/$capital_banco,2);
		//hasta aquí
		$prestamo = new Prestamo();		
		$prestamo->setSaldoSolicitado(0);
		$prestamo->setInteres($interes);
		$prestamo->setPlazosRestantes(1);
		$form = $this->createForm(new PrestamoType(),$prestamo);
		$prestamo =  $em->getRepository('simProductionHomeBundle:Prestamo')->findOneBy(array('empresa' => $empresa_id,'estado' => 1),array('id' => 'DESC'));
		$prestamos =  $em->getRepository('simProductionHomeBundle:Prestamo')->findBy(array('empresa' => $empresa_id),array('id' => 'DESC'));
		$graficos =  $em->getRepository('simProductionHomeBundle:MovimientoFinanciero')->findBy(array('empresa' => $empresa_id),array('fecha' => 'DESC','id' => 'DESC'));
		$y=1;
		while($y< count($graficos)){
			if($graficos[$y]->getFecha() == $graficos[$y-1]->getFecha() ){
				array_splice($graficos,$y,1);
			}else{	$y++; }
		}
		
		$fecha_actual= new \DateTime();
		if($graficos[0]->getFecha() != $fecha_actual){
			$movimiento= new MovimientoFinanciero();
			$movimiento->setFecha(new \DateTime());	
			$movimiento->setEmpresa($graficos[0]->getEmpresa());
			$movimiento->setMotivo("");
			$movimiento->setOperacion(0);
			$movimiento->setSaldo($graficos[0]->getSaldo());
			array_push($graficos, $movimiento);
		}
			
		$request = $this->getRequest();
		$session = $request->getSession();
		$session->set('interes', $interes);
		$prox_pago=$em->getRepository('simProductionHomeBundle:Tarea')->findOneBy(array('idAfectado' => $empresa_id,'tipo' =>4),array('fecha' => 'ASC'));
	    return $this->render('simProductionHomeBundle:Banco:banco.html.twig', array('user' => $user,'prox_pago' => $prox_pago,'empresa' => $empresa,'num_mensajes'=>$num_mensajes,'graficos'=>$graficos,'movimientos'=>$movimientos,'prestamos'=>$prestamos,'prestamo'=>$prestamo,'capital_banco'=>$capital_banco,'form'=>$form->createView()) );
    }
	
	public function prestamoAction(Request $request)  { 
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		$prestamo= new Prestamo();
		$form = $this->createForm(new PrestamoType(),$prestamo);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$prestamo = $form->getData();
				$prestamo->setFechaVencimiento(new \DateTime());				
				$prestamo->setEmpresa($empresa);	
				$prestamo->setEstado(1);
				
				//se calcula el interés del préstamo
				$banco = $em->getRepository('simProductionHomeBundle:Banco')->findOneBy(array('id' => 1));
				$capital_banco= $banco->getCapital();
				$interes=round((1000000/$capital_banco)+($prestamo->getPlazosRestantes()/100),2);
				$prestamo->setInteres($interes);
				$plazo = (($prestamo->getSaldoSolicitado()/$prestamo->getPlazosRestantes())+(($prestamo->getSaldoSolicitado()/$prestamo->getPlazosRestantes())*($interes)/100));
				$prestamo->setTotalDevolver(round($prestamo->getPlazosRestantes()*$plazo,2));
				$prestamo->setPlazoDevolver(round($plazo,2));
				$plazos=$prestamo->getPlazosRestantes();
				
				//se actualizan los saldos de empresa y banco
				$banco->setCapital($banco->getCapital()-$prestamo->getSaldoSolicitado());
				$resultado=$empresa->getSaldo()+$prestamo->getSaldoSolicitado();
				$empresa->setSaldo($resultado);
				
				$movimiento= new MovimientoFinanciero();
				$movimiento->setFecha(new \DateTime());	
				$movimiento->setEmpresa($empresa);
				$movimiento->setMotivo("Ingreso de préstamo solicitado");
				$movimiento->setOperacion($prestamo->getSaldoSolicitado());
				$movimiento->setSaldo($resultado);
				
				$em->persist($movimiento);
				$em->persist($prestamo);
				
				//preparo las tareas de cobro del préstamo
				$i=1;
				while($i<=$plazos){
					$tarea = new Tarea();
					$tarea->setTipo(4);
					$tarea->setIdAfectado($empresa_id);
					$tarea->setPrestamo($prestamo);
					$dias=$i*4;
					$fecha=new \DateTime();
					$fecha->modify('+'.$dias.' days');
					$fecha->setTime(0,0,0);
					$tarea->setFecha($fecha);
					$em->persist($tarea);
					$em->flush($tarea);
					$i++;
				}
			}
		}
		return $this->redirect(
			$this->generateUrl('banco')
		);    }	
	
}
