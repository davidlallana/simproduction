<?php

namespace simProduction\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\HttpFoundation\Request;
use simProduction\HomeBundle\Entity\Sector;
use simProduction\HomeBundle\Entity\Lote;
use simProduction\HomeBundle\Entity\Mensaje;
use simProduction\HomeBundle\Entity\Empresa;
use simProduction\HomeBundle\Entity\Venta;
use simProduction\HomeBundle\Entity\Usuario;
use simProduction\HomeBundle\Entity\conocimientoComprado;
use simProduction\HomeBundle\Entity\MovimientoFinanciero;
use simProduction\HomeBundle\Form\VentaType;
use simProduction\HomeBundle\Form\LoteType;
use simProduction\HomeBundle\Form\EmpresaType;
use simProduction\HomeBundle\Form\UsuarioType;
use simProduction\HomeBundle\Form\MensajeType;
use Symfony\Component\HttpFoundation\Response;


class VentasController extends Controller
{	
	public function ventasAction(Request $request)  {
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		$lote = new Lote();
		$form = $this->createForm(new LoteType(), $lote);
		$venta=new Venta();
		$form2 = $this->createForm(new VentaType(),$venta);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {				
				$lotes = $em->getRepository('simProductionHomeBundle:Lote')->findBy(array('empresa' => $empresa_id));
				if( sizeof($lotes)<4){
					$session = $this->get('request')->getSession();
					$lote = $form->getData();
					$lote ->setSector($empresa->getSector());
					$lote ->setEmpresa($empresa);
					
					$em->persist($lote);
					$em->flush($lote);
					$this->get('session')->setFlash('key',"lote");
				}else{
					$this->get('session')->setFlash('key',"limite");
					return $this->redirect($this->generateUrl('ventas'));
				}
			}
		}
		$query = $em->createQuery('SELECT l FROM simProductionHomeBundle:Lote l WHERE l.sector = :sector and l.empresa <> :id')->setParameters(array('sector' => $empresa->getSector()->getId(),'id'  => $empresa_id));
		$lotesSector = $query->getResult();
        return $this->render('simProductionHomeBundle:Ventas:ventas.html.twig', array('user' => $user,'empresa' => $empresa,'lotesSector'=>$lotesSector,'num_mensajes'=>$num_mensajes,'form' => $form->createView(),'form2' => $form2->createView()) );
    }

	public function mostrarHistorialVentasAction()  {
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$ventas = $em->getRepository('simProductionHomeBundle:Venta')->findBy(array('empresaVendedora' => $empresa_id),array('id' => 'DESC'));	
        return $this->render('simProductionHomeBundle:Ventas:historialVentas.html.twig', array('ventas'=>$ventas) );
    }	
	
	public function mostrarLotesVentasAction()  {
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$lotes = $em->getRepository('simProductionHomeBundle:Lote')->findBy(array('empresa' => $empresa_id));
        return $this->render('simProductionHomeBundle:Ventas:lotesVentas.html.twig', array('lotes'=>$lotes) );
    }		

	public function borrarLoteAction($id)  {
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {	
			$em = $this->getDoctrine()->getEntityManager();
			$lote = $em->getRepository('simProductionHomeBundle:Lote')->findOneBy(array('id' => $id));
			$em->remove($lote);
			$em->flush();	
			return new Response('OK');

		}else{
			return $this->redirect($this->generateUrl('ventas'));
		}
    }	
	
	public function ventaEmergenciaAction(Request $request)  {
		$venta=new Venta();
		$form = $this->createForm(new VentaType(),$venta);		
		$form->bindRequest($request);
		$venta =$form->getData();
		if($venta->getCantidad() !=0){
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$sector = $empresa->getSector();
			$precio =$sector->getPrecioExcep();
			$venta->setPrecio($venta->getCantidad()*$sector->getPrecioExcep());
			$venta->setFecha(new \DateTime());
			$venta->setEmpresaVendedora($empresa);
			$empresaCompradora = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('id' => 0));
			$venta->setEmpresaCompradora($empresaCompradora);
			switch ($empresa->getSector()->getId()) {
				case 1:
					$empresa ->setMinerales($empresa ->getMinerales()-$venta->getCantidad());						
					break;
				case 2:
					$empresa ->setHerramientas($empresa ->getHerramientas()-$venta->getCantidad());
					break;
				case 3:
					$empresa ->setVehiculos($empresa ->getVehiculos()-$venta->getCantidad());
					break;
			}
			$resultado=$empresa ->getSaldo()+$venta->getPrecio();
			$empresa ->setSaldo($resultado);
			$movimiento= new MovimientoFinanciero();
			$movimiento->setFecha(new \DateTime());	
			$movimiento->setEmpresa($empresa);
			$movimiento->setMotivo("Venta de producción a SimProduction");
			$movimiento->setOperacion($venta->getPrecio());
			$movimiento->setSaldo($resultado);
			$em->persist($movimiento);
			$em->persist($venta);
			$em->flush($venta);
			$em->persist($empresa);
			$em->flush($empresa);
			$this->get('session')->setFlash('key',"ventaCorrecta");
		}else{
			$this->get('session')->setFlash('key',"ventaIncorrecta");			
		}		
		return $this->redirect($this->generateUrl('ventas'));
    }	
	
}
