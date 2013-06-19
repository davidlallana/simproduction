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
use simProduction\HomeBundle\Entity\conocimientoComprado;
use simProduction\HomeBundle\Entity\MovimientoFinanciero;
use simProduction\HomeBundle\Form\VentaType;
use simProduction\HomeBundle\Form\LoteType;
use simProduction\HomeBundle\Form\EmpresaType;
use simProduction\HomeBundle\Form\UsuarioType;
use simProduction\HomeBundle\Form\MensajeType;
use Symfony\Component\HttpFoundation\Response;


class MensajesController extends Controller
{	
	public function borrarMensaje1Action($id)  {  
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensaje = $em->getRepository('simProductionHomeBundle:mensaje')->findOneBy(array('id' => $id,'origen'=> $empresa_id));		
		if($mensaje!=null  & $mensaje->getBorrado1()!=true ){
			if($mensaje->getBorrado2()==true){
				$em->remove($mensaje);
				$em->flush();
			}else{
				$mensaje->setBorrado1(true);
				$em->persist($mensaje);
				$em->flush($mensaje);
			}
			$this->get('session')->setFlash('key',"borrado");
			return $this->redirect(
				$this->generateUrl('mensajes_enviados')
			);
		}else{
			$this->get('session')->setFlash('key',"errorBorrado");
			return $this->redirect(
				$this->generateUrl('mensajes_enviados')
			);
		}	
	}
	
	public function borrarMensaje2Action($id)  {  
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensaje = $em->getRepository('simProductionHomeBundle:mensaje')->findOneBy(array('id' => $id,'destino'=> $empresa_id));		
		if($mensaje!=null & $mensaje->getBorrado2()!=true ){
			if($mensaje->getBorrado1()==true){
				$em->remove($mensaje);
				$em->flush();
			}else{
				$mensaje->setBorrado2(true);
				$em->persist($mensaje);
				$em->flush($mensaje);
			}
			$this->get('session')->setFlash('key',"borrado");
			return $this->redirect(
				$this->generateUrl('mensajes')
			);
		}else{
			$this->get('session')->setFlash('key',"errorBorrado");
			return $this->redirect(
				$this->generateUrl('mensajes')
			);
		}		
	}
	
	public function mensajeAction($id)  {  
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		$mensaje = $em->getRepository('simProductionHomeBundle:mensaje')->findOneBy(array('id' => $id,'destino'=> $empresa_id));		
		if($mensaje!=null){
			$mensaje->setLeido(true);
			$em->flush();
			$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
			$num_mensajes = sizeof($mensajes_recibidos);
			return $this->render('simProductionHomeBundle:Mensajes:mensaje.html.twig', array('empresa' => $empresa ,'mensaje' => $mensaje,'user' => $user,'num_mensajes'=>$num_mensajes) );	
		}else{	
			return $this->redirect($this->generateUrl('mensajes'));
		}
	}	
	
	public function mensaje_enviadoAction($id)  {  
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		$mensaje = $em->getRepository('simProductionHomeBundle:mensaje')->findOneBy(array('id' => $id,'origen'=> $empresa_id));	
		if($mensaje!=null){
			return $this->render('simProductionHomeBundle:Mensajes:mensaje_enviado.html.twig', array('empresa' => $empresa ,'mensaje' => $mensaje,'user' => $user,'num_mensajes'=>$num_mensajes) );	
		}else{
			return $this->redirect($this->generateUrl('mensajes_enviados'));
		}
	}
	
	public function new_mensajeAction(Request $request)  {  
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();	
		$mensaje = new Mensaje();	
		$form = $this->createForm(new MensajeType(), $mensaje, array("id" => $empresa_id));
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$session = $this->get('request')->getSession();
				$mensaje = $form->getData();
				$mensaje->setFecha(new \DateTime('now'));
				$mensaje->setLeido(false);
				$mensaje->setBorrado1(false);
				$mensaje->setBorrado2(false);
				$mensaje ->setOrigen($empresa);
				$destino = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('nombre' => $mensaje ->getDestino2()));
				if ($destino != null & $destino != $empresa & $mensaje->getMensaje()!=null){
					$mensaje ->setDestino($destino);
					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($mensaje);
					$em->flush($mensaje);					
					$this->get('session')->setFlash('key',"enviado");
				} else {
					$this->get('session')->setFlash('key',"error");
				}				
				return $this->redirect($this->generateUrl('mensajes_enviados'));
			}
		}
		return $this->redirect($this->generateUrl('mensajes_enviados'));
    }
	
	public function enviarMensajeAction($emp,$mens)  {  
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();		
		$mensaje = new Mensaje();		
		$mensaje->setFecha(new \DateTime('now'));
		$mensaje->setLeido(false);
		$mensaje->setBorrado1(false);
		$mensaje->setBorrado2(false);
		$mensaje ->setOrigen($empresa);
		$mensaje ->setMensaje($mens);
		$destino = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('id' => $emp));
		if ($destino != null){
			$mensaje ->setDestino($destino);
			$mensaje ->setDestino2($destino->getNombre());
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($mensaje);
			$em->flush($mensaje);					
			return new Response('OK');
		} else {
			return new Response('Error al enviar el mensaje');
		}				
	}
	
	public function show_new_mensajeAction($id)  { 
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		if($id>0){
			$dest = $em->getRepository('simProductionHomeBundle:empresa')->findOneBy(array('id' => $id));		
		}else{
			$dest = null;
		}
		$mensaje = new Mensaje();
		if ($dest != null){	
			$mensaje->setDestino2($dest->getNombre());
		}
		$query = $em->createQuery('SELECT l FROM simProductionHomeBundle:Empresa l WHERE l.id > 0 and l.id <> :id')->setParameters(array('id'  => $empresa_id));
		$destinos = $query->getResult();
		$form = $this->createForm(new MensajeType(), $mensaje, array("id" => $empresa_id));
		return $this->render('simProductionHomeBundle:Mensajes:new_mensaje.html.twig', array('destinos' => $destinos ,'empresa' => $empresa ,'form' => $form->createView(),'user' => $user,'num_mensajes'=>$num_mensajes));
    }
	
	public function comprobarDestinoAction($nombre)  { 
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$destino = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('nombre' => $nombre));		
		$query = $em->createQuery('SELECT l FROM simProductionHomeBundle:Empresa l WHERE l.id > 0 and l.id <> :id')->setParameters(array('id'  => $empresa_id));
		$destinos = $query->getResult();
		if (in_array($destino, $destinos)) {
			return new Response ('OK');
		}else{
			return new Response ('ERROR');
		}
    }
	
	public function mensajes_enviadosAction()  {   
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		$mensajes_enviados = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('origen' => $empresa_id,'borrado1' => false),array('fecha' => 'DESC'));			
		foreach($mensajes_enviados as $m)	{
			if (strlen($m->getMensaje()) >125) {		
				$corto=substr($m->getMensaje(),0,125)."...";
				$m->setMensaje($corto);
			}
		}
		return $this->render('simProductionHomeBundle:Mensajes:mensajes_enviados.html.twig', array('empresa' => $empresa ,'mensajes_enviados' => $mensajes_enviados,'user' => $user,'num_mensajes'=>$num_mensajes ) );	
    }

	public function mensajesAction()  {   
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos2 = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos2);
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id,'borrado2' => false),array('fecha' => 'DESC'));		
		foreach($mensajes_recibidos as $m)	{
			if (strlen($m->getMensaje()) >125) {		
				$corto=substr($m->getMensaje(),0,125)."...";
				$m->setMensaje($corto);
			}
		}
		return $this->render('simProductionHomeBundle:Mensajes:mensajes.html.twig', array('empresa' => $empresa ,'mensajes_recibidos' => $mensajes_recibidos ,'user' => $user,'num_mensajes'=>$num_mensajes) );	
    }		
}
