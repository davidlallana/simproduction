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


class ComprasController extends Controller
{	
	public function comprasAction()  {
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		$lotes1 = $em->getRepository('simProductionHomeBundle:Lote')->findBy(array('sector' => '1'));
		$lotes2 = $em->getRepository('simProductionHomeBundle:Lote')->findBy(array('sector' => '2'));
		$lotes3 = $em->getRepository('simProductionHomeBundle:Lote')->findBy(array('sector' => '3'));
		
		if($empresa->getSector()->getId()==1){
			return $this->render('simProductionHomeBundle:Compras:compras.html.twig', array('user' => $user,'empresa' => $empresa,'num_mensajes'=>$num_mensajes,'lotes1'=>$lotes2,'lotes2'=>$lotes3) );
		}elseif($empresa->getSector()->getId()==2){
			return $this->render('simProductionHomeBundle:Compras:compras.html.twig', array('user' => $user,'empresa' => $empresa,'num_mensajes'=>$num_mensajes,'lotes1'=>$lotes1,'lotes2'=>$lotes3) );
		}else{
			return $this->render('simProductionHomeBundle:Compras:compras.html.twig', array('user' => $user,'empresa' => $empresa,'num_mensajes'=>$num_mensajes,'lotes1'=>$lotes1,'lotes2'=>$lotes2) );
		}		
    }	
	
	public function mostrarHistorialComprasAction()  {
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {		
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$empresa_id = $empresa->getId();
			$compras = $em->getRepository('simProductionHomeBundle:Venta')->findBy(array('empresaCompradora' => $empresa_id),array('id' => 'DESC'));	
			return $this->render('simProductionHomeBundle:Compras:historialCompras.html.twig', array('compras'=>$compras) );		
		}else{
			return $this->redirect($this->generateUrl('compras'));
		}
    }	
	
	public function compraEmergenciaAction($id,$cantidad)  { 
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {		
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));			
			$empresaEmer = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('id' => $id));
			$lote = $em->getRepository('simProductionHomeBundle:Lote')->findOneBy(array('id' => $empresaEmer->getId()));
			if($lote->getSector()!=$empresa->getSector()){
				if( $empresa->getSaldo()>=$cantidad*$lote->getPrecio()){	
					$resultado=	$empresa->getSaldo()-($cantidad*$lote->getPrecio());
					$empresa->setSaldo($resultado);		
					if(	$id==-1){
						$empresa->setMinerales($empresa->getMinerales()+$cantidad);
					}elseif($id==-2){
						$empresa->setHerramientas($empresa->getHerramientas()+$cantidad);
					}else{
						$empresa->setVehiculos($empresa->getVehiculos()+$cantidad);
					}
					$compra = new Venta();	
					$compra->setEmpresaVendedora($empresaEmer);
					$compra->setEmpresaCompradora($empresa);
					$compra->setPrecio($lote->getPrecio()*$cantidad);
					$compra->setCantidad($cantidad);
					$compra->setFecha(new \DateTime());	
					
					$movimiento= new MovimientoFinanciero();
					$movimiento->setFecha(new \DateTime());	
					$movimiento->setEmpresa($empresa);
					$movimiento->setMotivo("Compra de ".$lote->getSector()->getProducto()." a ".$lote->getEmpresa());
					$movimiento->setOperacion(-$lote->getPrecio()*$cantidad);
					$movimiento->setSaldo($resultado);
					
					$em->persist($movimiento);
					$em->persist($compra);
					$em->flush($compra);
					return new Response('OK');
				}else{
					return new Response('ERROR!DINERO');
				}			
			}else{
				return new Response('ERROR!SECTOR');
			}
		}
	}
	
	public function comprarLoteAction($id)  { 
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {	
			$user = $this->get('security.context')->getToken()->getUser();
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$lote = $em->getRepository('simProductionHomeBundle:Lote')->findOneBy(array('id' => $id));
			if($lote->getSector()!=$empresa->getSector()){
				if( $empresa->getSaldo()>=$lote->getPrecio()){			
					if(	$lote->getSector()->getId()==1){
						if ($lote->getEmpresa()->getMinerales()>=$lote->getCantidad())	{
							$valido=1;
							$lote->getEmpresa()->setMinerales($lote->getEmpresa()->getMinerales()-$lote->getCantidad());
							$empresa->setMinerales($empresa->getMinerales()+$lote->getCantidad());
						} else $valido=0;
					}elseif($lote->getSector()->getId()==2){
						if ($lote->getEmpresa()->getHerramientas()>=$lote->getCantidad())	{
							$valido=1;
							$lote->getEmpresa()->setHerramientas($lote->getEmpresa()->getHerramientas()-$lote->getCantidad());
							$empresa->setHerramientas($empresa->getHerramientas()+$lote->getCantidad());
						}
						else $valido=0;
					}else{
						if ($lote->getEmpresa()->getVehiculos()>=$lote->getCantidad()){
							$valido=1;
							$lote->getEmpresa()->setVehiculos($lote->getEmpresa()->getVehiculos()-$lote->getCantidad());
							$empresa->setVehiculos($empresa->getVehiculos()+$lote->getCantidad());
						}
						else $valido=0;
					}
					if ($valido==1){	
						$resultado2=$lote->getEmpresa()->getSaldo()+$lote->getPrecio();
						$lote->getEmpresa()->setSaldo($resultado2);		
						$resultado=$empresa->getSaldo()-$lote->getPrecio();
						$empresa->setSaldo($resultado);		
						
						$compra = new Venta();	
						$compra->setEmpresaVendedora($lote->getEmpresa());
						$compra->setEmpresaCompradora($empresa);
						$compra->setPrecio($lote->getPrecio());
						$compra->setCantidad($lote->getCantidad());
						$compra->setFecha(new \DateTime());	
						$movimiento= new MovimientoFinanciero();
						$movimiento->setFecha(new \DateTime());	
						$movimiento->setEmpresa($empresa);
						$movimiento->setMotivo("Compra de ".$lote->getSector()->getProducto()." a ".$lote->getEmpresa());
						$movimiento->setOperacion(-$lote->getPrecio());
						$movimiento->setSaldo($resultado);
						$em->persist($movimiento);
						$movimiento2= new MovimientoFinanciero();
						$movimiento2->setFecha(new \DateTime());	
						$movimiento2->setEmpresa($lote->getEmpresa());
						$movimiento2->setMotivo("Venta de producción a ".$empresa);
						$movimiento2->setOperacion($lote->getPrecio());
						$movimiento2->setSaldo($resultado2);
						$em->persist($movimiento2);
						$em->persist($compra);
						$em->flush($compra);
						return new Response('OK');
					}else{
						return new Response('ERROR!STOCK');
					}
				}else{
					return new Response('ERROR!DINERO');
				}
			}else{
				return new Response('ERROR!SECTOR');
			}
		}else{
			return $this->redirect($this->generateUrl('compras'));
		}		
	}
}
