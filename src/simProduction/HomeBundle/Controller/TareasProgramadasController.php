<?php

namespace simProduction\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\HttpFoundation\Request;
use simProduction\HomeBundle\Entity\Sector;
use simProduction\HomeBundle\Entity\Empresa;
use simProduction\HomeBundle\Entity\Venta;
use simProduction\HomeBundle\Entity\Tarea;
use simProduction\HomeBundle\Entity\Usuario;
use simProduction\HomeBundle\Entity\conocimientoComprado;
use simProduction\HomeBundle\Entity\MovimientoFinanciero;
use Symfony\Component\HttpFoundation\Response;


class TareasProgramadasController extends Controller {	
	
	public function tratarTareasAction()  {
		$em = $this->getDoctrine()->getEntityManager();
		$fecha_actual =Date("Y-m-d H:i:s");
		$ending=false;
		while(!$ending){
			$query = $em->createQuery('SELECT tarea FROM simProductionHomeBundle:Tarea tarea WHERE tarea.fecha < :fec')->setParameters(array('fec' => $fecha_actual));
			$tareas = $query->getResult();	
			if(count($tareas)==0) $ending=true;			
			for ($x=0;$x< count($tareas); $x++){	
				switch ($tareas[$x]->getTipo()) {						
					case 1:
						//AUMENTO EL CAPITAL DEL BANCO
						echo " - AUMENTO EL CAPITAL DEL BANCO<br/>";						
						$banco = $em->getRepository('simProductionHomeBundle:Banco')->findOneBy(array());
						$banco->setCapital($banco->getCapital()+20000);
						$em->persist($banco);
						$em->flush($banco);
						$tarea = new Tarea();
						$tarea->setTipo(1);
						$fecha=$tareas[$x]->getFecha();
						$fecha->modify("+1 day");
						$tarea->setFecha($fecha);
						$em->persist($tarea);
						$em->flush($tarea);
						break;
					case 2:
						//AUMENTO PRODUCCION
						$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('id'=> $tareas[$x]->getIdAfectado()));
						echo " - AUMENTO PRODUCCIÓN ".$empresa->getNombre()."<br/>";
						if ($empresa->getSector()->getId()==1){
							$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa->getId(),'PerfilTrabajador' => 1),array('id' => 'ASC'));		
							$nivel=$em->getRepository('simProductionHomeBundle:Nivel')->findOneBy(array('sector'=> 1,'nivel' =>$empresa->getNivel()->getNivel()-1));
							$empresa->setMinerales($empresa->getMinerales()+$nivel->getProduccionFija()+(count($empleados)*$nivel->getProduccionVariable()));
						}else if ($empresa->getSector()->getId()==2){
							$nivel=$em->getRepository('simProductionHomeBundle:Nivel')->findOneBy(array('sector'=> 2,'nivel' =>$empresa->getNivel()->getNivel()-1));
							$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa->getId(),'PerfilTrabajador' => 5),array('id' => 'ASC'));	
							$num_herramientas=$empresa->getProduccion()+$nivel->getProduccionFija()+$nivel->getProduccionVariable()/100*count($empleados);
							if($num_herramientas>1){
								$acabado=false;
								while (!$acabado){
									if($empresa->getMinerales()>$empresa->getSector()->getMp()){
										$empresa->setMinerales($empresa->getMinerales()-$empresa->getSector()->getMp());
										$empresa->setHerramientas($empresa->getHerramientas()+1);
										if ($num_herramientas-1<1) {
											$empresa->setProduccion($num_herramientas-1);
											$acabado=true;
										}else $num_herramientas=$num_herramientas-1;
									}else $acabado=true;
								}
							}else $empresa->setProduccion($num_herramientas);							
						}else {
							$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa->getId(),'PerfilTrabajador' => 9),array('id' => 'ASC'));		
							$nivel=$em->getRepository('simProductionHomeBundle:Nivel')->findOneBy(array('sector'=> 3,'nivel' =>$empresa->getNivel()->getNivel()-1));
							$num_vehiculos=$empresa->getProduccion()+$nivel->getProduccionFija()/100+$nivel->getProduccionVariable()/100*count($empleados);
							if($num_vehiculos>1){
								$acabado=false;
								while (!$acabado){
									if($empresa->getMinerales()>$empresa->getSector()->getMp()){
										$empresa->setMinerales($empresa->getMinerales()-$empresa->getSector()->getMp());
										$empresa->setVehiculos($empresa->getVehiculos()+1);
										if ($num_vehiculos-1<1) {
											$empresa->setProduccion($num_vehiculos-1);
											$acabado=true;
										}else $num_vehiculos=$num_vehiculos-1;
									}else $acabado=true;
								}
							}else $empresa->setProduccion($num_vehiculos);
						}
						$em->persist($empresa);
						$tarea = new Tarea();
						$tarea->setTipo(2);
						$tarea->setIdAfectado($empresa->getId());
						$fecha=$tareas[$x]->getFecha();
						$fecha->modify("+1 hour");
						$tarea->setFecha($fecha);
						$em->persist($tarea);
						$em->flush($tarea);
						break;
					case 3:
						//PAGO DE SALARIOS
						$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('id'=> $tareas[$x]->getIdAfectado()));
						echo " - PAGO DE SALARIOS ".$empresa->getNombre()."<br/>";						
						$resultado=$empresa->getSaldo()-$empresa->getSalarios();
						$empresa->setSaldo($resultado);
						$movimiento= new MovimientoFinanciero();
						$movimiento->setFecha($tareas[$x]->getFecha());									
						$movimiento->setEmpresa($empresa);
						$movimiento->setMotivo("Pago de salarios a empleados");
						$movimiento->setOperacion(-$empresa->getSalarios());
						$movimiento->setSaldo($resultado);
						$em->persist($movimiento);
						$em->flush($movimiento);	

						$tarea = new Tarea();
						$tarea->setTipo(3);
						$tarea->setIdAfectado($empresa->getId());
						$fecha=$tareas[$x]->getFecha();
						$fecha->modify("+20 days");
						$tarea->setFecha($fecha);
						$em->persist($tarea);
						$em->flush($tarea);	
						
						break;
					case 4:
						//DEVOLUCIÓN DE PRÉSTAMO
						$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('id'=> $tareas[$x]->getIdAfectado()));
						echo " - DEVOLUCIÓN DE PRÉSTAMO ".$empresa->getNombre()."<br/>";
						$resultado=$empresa->getSaldo()-$tareas[$x]->getPrestamo()->getPlazoDevolver();
						$empresa->setSaldo($resultado);					
						$movimiento= new MovimientoFinanciero();
						$movimiento->setFecha($tareas[$x]->getFecha());	
						$movimiento->setEmpresa($empresa);
						$movimiento->setMotivo("Pago de devolución de préstamo");
						$movimiento->setOperacion(-$tareas[$x]->getPrestamo()->getPlazoDevolver());
						$movimiento->setSaldo($resultado);
						//modificamos el siguiente pago
						//restamos uno a los restantes
						$prestamo = $em->getRepository('simProductionHomeBundle:Prestamo')->findOneBy(array('id'=> $tareas[$x]->getPrestamo()->getId()));
						$plazos_restantes=$prestamo->getPlazosRestantes()-1;
						$prestamo->setPlazosRestantes($plazos_restantes);
						if($plazos_restantes<1) $prestamo->setEstado(0);
						$em->persist($prestamo);
						$em->persist($movimiento);
						$em->flush($prestamo);	
						break;
				}	
				$em->remove($tareas[$x]);
				$em->flush();
			}	
		}	
		return new Response("***************** TAREAS REALIZADAS CORRECTAMENTE ********************");			
	}
	
}
