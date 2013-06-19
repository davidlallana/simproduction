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
use simProduction\HomeBundle\Entity\Trabajador;
use simProduction\HomeBundle\Entity\Usuario;
use simProduction\HomeBundle\Entity\NivelFormacionTrabajador;
use simProduction\HomeBundle\Entity\conocimientoComprado;
use simProduction\HomeBundle\Entity\MovimientoFinanciero;
use simProduction\HomeBundle\Form\TrabajadorType;
use simProduction\HomeBundle\Form\VentaType;
use simProduction\HomeBundle\Form\LoteType;
use simProduction\HomeBundle\Form\EmpresaType;
use simProduction\HomeBundle\Form\UsuarioType;
use simProduction\HomeBundle\Form\MensajeType;
use Symfony\Component\HttpFoundation\Response;

class PersonalController extends Controller
{	
	public function personalAction()  {
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);	
		$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa_id,),array('id' => 'ASC'));	
		$total = count($empleados);
		$perfiles = $em->getRepository('simProductionHomeBundle:PerfilTrabajador')->findBy(array('sector' => $empresa->getSector()->getId()),array('id' => 'ASC'));	
		for ($x=0;$x< count($perfiles); $x++){
			$num=0;
			for ($y=0;$y< count($empleados); $y++){
				if($empleados[$y]->getPerfilTrabajador()->getId() == $perfiles[$x]->getId() ){
					$num=$num+1;
				}
			}	
			$perfiles[$x]->setSalarioBase($num);
		}
		$trabajador = new Trabajador();		
		$form = $this->createForm(new TrabajadorType(), $trabajador, array("id" => $empresa->getSector()->getId()));		
        return $this->render('simProductionHomeBundle:Personal:personal.html.twig', array('form' => $form->createView(),'user' => $user,'empresa' => $empresa,'num_mensajes'=>$num_mensajes,'total'=>$total,'empleados'=>$empleados, 'perfiles'=>$perfiles ) );
    }	
	
	public function nuevo_empleadoAction($id)  {	
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 	
			$em = $this->getDoctrine()->getEntityManager();
			$perfil = $em->getRepository('simProductionHomeBundle:PerfilTrabajador')->findOneBy(array('id' => $id));	
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			//genero nombre y apellidos
			$nombres = $em->getRepository('simProductionHomeBundle:Nombres')->findBy(array());
			$numaleatorio = rand(0,count($nombres)-1); 
			$nombre = $nombres[$numaleatorio]->getNombre();
			$apellidos = $em->getRepository('simProductionHomeBundle:Apellidos')->findBy(array());
			$numaleatorio = rand(0,count($apellidos)-1); 
			$apellido1 = $apellidos[$numaleatorio]->getApellido();
			$numaleatorio = rand(0,count($apellidos)-1); 
			$apellido2 = $apellidos[$numaleatorio]->getApellido();
			//asignaciones
			$trabajador=new Trabajador();
			$trabajador->setPerfilTrabajador($perfil);
			$trabajador->setEmpresa($empresa);
			$trabajador->setSector($empresa->getSector());
			$trabajador->setNombre($nombre);
			$trabajador->setApellidos($apellido1.' '.$apellido2);
			$trabajador->setSalario($perfil->getSalarioBase());
			$trabajador->setIncorporacion(new \DateTime());
			$em->persist($trabajador);
			$em->flush($trabajador);
			//formacion
			$formaciones = $em->getRepository('simProductionHomeBundle:Formacion')->findBy(array('PerfilTrabajador' => $perfil->getId()));
			for ($x=0;$x< count($formaciones); $x++){	
				$NFT= new NivelFormacionTrabajador();
				$NFT->setFormacion($formaciones[$x]);
				$NFT->setTrabajador($trabajador);
				$NFT->setFormado(false);
				$em->persist($NFT);
				$em->flush($NFT);				
			}
			//actualizamos pago total de salarios de la empresa
			$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa->getId()));		
			$total=0;
			for ($x=0;$x< count($empleados); $x++){
				$total=$total + $empleados[$x]->getSalario();
			}
			$empresa->setSalarios($total);
			$em->persist($empresa);
			$em->flush($empresa);
			return new Response($trabajador->getNombre().' '.$trabajador->getApellidos());			
		}	
    }	
		
	public function formacionAction($id)  {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$empresa_id = $empresa->getId();
			$empleado = $em->getRepository('simProductionHomeBundle:Trabajador')->findOneBy(array('id' => $id,'empresa'=>$empresa->getId()));
			$formaciones = $em->getRepository('simProductionHomeBundle:NivelFormacionTrabajador')->findBy(array('trabajador' => $empleado->getId()));
			return $this->render('simProductionHomeBundle:Personal:formacion.html.twig', array('formaciones'=>$formaciones,'empleado'=>$empleado) );
		}
    }	
	
	public function recargarEmpleadosAction()  {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$empresa_id = $empresa->getId();
			$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa_id,),array('id' => 'ASC'));		
			$perfiles = $em->getRepository('simProductionHomeBundle:PerfilTrabajador')->findBy(array('sector' => $empresa->getSector()->getId()),array('id' => 'ASC'));	
			for ($x=0;$x< count($perfiles); $x++){
				$num=0;
				for ($y=0;$y< count($empleados); $y++){
					if($empleados[$y]->getPerfilTrabajador()->getId() == $perfiles[$x]->getId() ){
						$num=$num+1;
					}
				}	
				$perfiles[$x]->setSalarioBase($num);
			}
			return $this->render('simProductionHomeBundle:Personal:empleados.html.twig', array('empleados'=>$empleados,'perfiles'=>$perfiles ) );
			
		}
    }	
	
	public function cursosAction($id)  {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$empresa_id = $empresa->getId();
			$empleado = $em->getRepository('simProductionHomeBundle:Trabajador')->findOneBy(array('id' => $id,'empresa'=>$empresa->getId()));
			$formaciones = $em->getRepository('simProductionHomeBundle:NivelFormacionTrabajador')->findBy(array('trabajador' => $empleado->getId()));
			$cursos=array();
			for ($x=0;$x< count($formaciones); $x++){	
				$array1=$em->getRepository('simProductionHomeBundle:Curso')->findBy(array('formacion' => $formaciones[$x]->getFormacion()->getId()));
				$cursos=array_merge((array)$cursos, $array1);
			}
			//MODIFICAMOS EL PRECIO SEGUN LA DISTANCIA
			for ($x=0;$x< count($cursos); $x++){
				$CX = $cursos[$x]->getCT()->getCuadrante()->getCoordX();			
				$CY = $cursos[$x]->getCT()->getCuadrante()->getCoordY();
				$EX = $empresa->getCuadrante()->getCoordX();
				$EY = $empresa->getCuadrante()->getCoordY();
				$distancia = ceil(sqrt((($CX-$EX)*($CX-$EX))+(($CY-$EY)*($CY-$EY))));
				$cursos[$x]->setPrecio($cursos[$x]->getPrecio()+$distancia);
			}
			return $this->render('simProductionHomeBundle:Personal:cursos.html.twig', array('cursos'=>$cursos,'empleado'=>$empleado) );
		}
    }	

	public function calcularFiniquitoAction($idEmpleado)  { 
		//$request = $this->getRequest();
       // if ($request->isXmlHttpRequest()) { 
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$empleado = $em->getRepository('simProductionHomeBundle:Trabajador')->findOneBy(array('id' => $idEmpleado,'empresa'=>$empresa->getId()));
			if($empleado!=null){
				//COMPROBAMOS SI DESPIDIENDO A ESTE EMPLEADO INCUMPLE NORMAS DE NIVEL
				$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa->getId()),array('id' => 'ASC'));		
				$clave = array_search($empleado, $empleados);
				if($clave>=0 ){
					array_splice($empleados,$clave,1);
				}
				$nivel = $em->getRepository('simProductionHomeBundle:Nivel')->findOneBy(array('nivel' => $empresa->getNivel()->getNivel()-1,'sector'=>$empresa->getSector()->getId()));
				$formaciones = $em->getRepository('simProductionHomeBundle:FormacionesNivel')->findBy(array('nivel' => $nivel->getId()));
				$cumpleRequisitos=true;
				for ($x=0;$x< count($formaciones); $x++){	
					$num=0;
					if($formaciones[$x]->getFormacion()==null){
						for ($y=0;$y< count($empleados); $y++){
							if($empleados[$y]->getPerfilTrabajador()->getId() == $formaciones[$x]->getPerfilTrabajador()->getId() ){
								$num=$num+1;
							}
						}
					}else{
						for ($y=0;$y< count($empleados); $y++){
							if($empleados[$y]->getPerfilTrabajador()->getId() == $formaciones[$x]->getPerfilTrabajador()->getId() ){
								$formacionesEmpleado = $em->getRepository('simProductionHomeBundle:NivelFormacionTrabajador')->findBy(array('trabajador' => $empleados[$y]->getId(),'formado' => 1,'formacion' => $formaciones[$x]->getFormacion()->getId()));
								if ($formacionesEmpleado!=null) {$num=$num+1;}
							}
						}
					}
					if($num >= $formaciones[$x]->getNumEmpleados() ){
						$formaciones[$x]->setCumpleRequisito(true);	
					}else{
						$cumpleRequisitos=false;
					}				
				}
				if ($cumpleRequisitos==false){
					return new Response('No puedes despedir a este empleado. Es necesario para estar en este nivel.');
				}
				//CALCULAMOS FINIQUITO
				$salario = $empleado->getSalario();
				$fecha_contratacion = $empleado->getIncorporacion();
				$segundos=$fecha_contratacion->getTimestamp() - strtotime('now');
				$tiempo_trabajado=-intval($segundos/60/60/24);			
				$finiquito=round($salario * $tiempo_trabajado/175,2);
				$finiquito=$finiquito+100;
				return new Response($empleado->getNombre().' '. $empleado->getApellidos().' ha estado trabajando '.$tiempo_trabajado.' días y su finiquito es '.$finiquito.' €. ¿Quieres continuar con el despido?');
			}else{
				return new Response('ERROR AL DESPEDIR A ESTE EMPLEADO');
			}		
		//}
	}
	
	public function despedirEmpleadoAction($idEmpleado)  { 
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$empleado = $em->getRepository('simProductionHomeBundle:Trabajador')->findOneBy(array('id' => $idEmpleado,'empresa'=>$empresa->getId()));
			if($empleado!=null){
				$salario = $empleado->getSalario();
				$fecha_contratacion = $empleado->getIncorporacion();
				$segundos=$fecha_contratacion->getTimestamp() - strtotime('now');
				$tiempo_trabajado=-intval($segundos/60/60/24);			
				$finiquito=round($salario * $tiempo_trabajado/175,2);
				$finiquito=$finiquito+100;
				$resultado=$empresa->getSaldo()-($finiquito);
				$empresa->setSaldo($resultado);							
				$movimiento= new MovimientoFinanciero();
				$movimiento->setFecha(new \DateTime());	
				$movimiento->setEmpresa($empresa);
				$movimiento->setMotivo("Despido de ".$empleado->getNombre()." ".$empleado->getApellidos());
				$movimiento->setOperacion(-$finiquito);
				$movimiento->setSaldo($resultado);
				$em->persist($movimiento);
				$em->remove($empleado);
				$em->flush();
				
				//actualizamos pago total de salarios de la empresa
				$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa->getId()));		
				$total=0;
				for ($x=0;$x< count($empleados); $x++){
					$total=$total + $empleados[$x]->getSalario();
				}
				$empresa->setSalarios($total);
				$em->persist($empresa);
				$em->flush($empresa);
				
				return new Response('Has despedido a '.$empleado->getNombre()." ".$empleado->getApellidos());
			}else{
				return new Response('ESE EMPLEADO NO ES TUYO');
			}	
		}
	}
	
	public function formarEmpleadoAction($idEmpleado,$idCurso)  { 
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$empleado = $em->getRepository('simProductionHomeBundle:Trabajador')->findOneBy(array('id' => $idEmpleado,'empresa'=>$empresa->getId()));
			if($empleado!=null){
				$curso = $em->getRepository('simProductionHomeBundle:Curso')->findOneBy(array('id' => $idCurso));
				if($curso!=null){
					$CX = $curso->getCT()->getCuadrante()->getCoordX();			
					$CY = $curso->getCT()->getCuadrante()->getCoordY();
					$EX = $empresa->getCuadrante()->getCoordX();
					$EY = $empresa->getCuadrante()->getCoordY();
					$distancia = ceil(sqrt((($CX-$EX)*($CX-$EX))+(($CY-$EY)*($CY-$EY))));	
					$precioTotal=$curso->getPrecio()+$distancia;
					if( $empresa->getSaldo()>=($precioTotal)){
						$resultado=$empresa->getSaldo()-($precioTotal);
						$empresa->setSaldo($resultado);							
						$movimiento= new MovimientoFinanciero();
						$movimiento->setFecha(new \DateTime());	
						$movimiento->setEmpresa($empresa);
						$movimiento->setMotivo("Pago de formación de ".$empleado->getNombre()." ".$empleado->getApellidos());
						$movimiento->setOperacion(-$precioTotal);
						$movimiento->setSaldo($resultado);
						$nivelEmpleado = $em->getRepository('simProductionHomeBundle:NivelFormacionTrabajador')->findOneBy(array('trabajador' => $idEmpleado,'formacion'=>$curso->getFormacion()->getId()));				
						$nivelEmpleado->setFormado(true);					
						$em->persist($movimiento);
						$em->persist($nivelEmpleado);
						$em->flush($nivelEmpleado);
						
						//AÑADIR TEMA DE AUMENTO DE SALARIO X FORMACION Y ACTUALIZACION DE SALARIO TOTAL DE LA EMPRESA
						$empleado->setSalario($empleado->getSalario()+$precioTotal/10);
						$em->persist($empleado);
						
						//actualizamos pago total de salarios de la empresa
						$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa->getId()));		
						$total=0;
						for ($x=0;$x< count($empleados); $x++){
							$total=$total + $empleados[$x]->getSalario();
						}
						$empresa->setSalarios($total);
						$em->persist($empresa);
						$em->flush($empresa);
						
						return new Response('OK');
					}else{
						return new Response('ERROR!');
					}
				}else{
					return new Response('ERROR');
				}
			}
		}else{
			return $this->redirect($this->generateUrl('personal'));
		}
	}	
	
	public function mostrarEmpleadosAction($idPerfil)  { 
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		if($idPerfil==0){
			$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa'=>$empresa->getId()));
		}else{
			$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('PerfilTrabajador' => $idPerfil,'empresa'=>$empresa->getId()));
		}
		return $this->render('simProductionHomeBundle:Personal:empleados2.html.twig', array('empleados'=>$empleados) );
	}	
}
