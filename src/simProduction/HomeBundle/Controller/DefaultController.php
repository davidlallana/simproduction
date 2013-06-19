<?php

namespace simProduction\HomeBundle\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\HttpFoundation\Request;
use simProduction\HomeBundle\Entity\Sector;
use simProduction\HomeBundle\Entity\Mensaje;
use simProduction\HomeBundle\Entity\Empresa;
use simProduction\HomeBundle\Entity\Curso;
use simProduction\HomeBundle\Entity\Venta;
use simProduction\HomeBundle\Entity\Tarea;
use simProduction\HomeBundle\Entity\Usuario;
use simProduction\HomeBundle\Entity\Trabajador;
use simProduction\HomeBundle\Entity\NivelFormacionTrabajador;
use simProduction\HomeBundle\Entity\FormacionesNivel;
use simProduction\HomeBundle\Entity\conocimientoComprado;
use simProduction\HomeBundle\Entity\MovimientoFinanciero;
use simProduction\HomeBundle\Form\VentaType;
use simProduction\HomeBundle\Form\LoteType;
use simProduction\HomeBundle\Form\EmpresaType;
use simProduction\HomeBundle\Form\UsuarioType;
use simProduction\HomeBundle\Form\MensajeType;
use simProduction\HomeBundle\Form\AutorizacionType;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
	public function indexAction() {		
		return new RedirectResponse("accessControl"); 
    }
	
	public function accessControlAction()  {
			
		if ($this->get('security.context')->isGranted('ROLE_ADMIN') === true) {
			return new RedirectResponse("admin/"); 
		}else if ($this->get('security.context')->isGranted('ROLE_USUARIO') === true){
			return new RedirectResponse("i/homepage"); 
		}else{
			$session = $this->getRequest()->getSession();
			$session->invalidate();
			return new RedirectResponse("login"); 
		}
	}
	
	public function homepageAction()  {
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		if($empresa === null){
			return new RedirectResponse("configuracion_cuenta"); 
		}else{
			$empresa_id = $empresa->getId();
			$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false),array('id' => 'DESC'));
			$num_mensajes = sizeof($mensajes_recibidos);	
			$formaciones = $em->getRepository('simProductionHomeBundle:FormacionesNivel')->findBy(array('nivel' => $empresa->getNivel()->getId()));
			$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa_id,),array('id' => 'ASC'));		
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
					$formaciones[$x]->setCumpleRequisito(false);	
					$cumpleRequisitos=false;
				}				
			}
			if($cumpleRequisitos==true){
				//comprobar dinero y otras cosas que tb cumplan requisitos					
				if($empresa->getSector()->getId()== 1){
					if($empresa->getNivel()->getDinero()<=$empresa->getSaldo()){
						if($empresa->getNivel()->getHerramientas()<=$empresa->getHerramientas()){
							if($empresa->getNivel()->getVehiculos()>$empresa->getVehiculos())
							$cumpleRequisitos=false;	
						}else $cumpleRequisitos=false;
					}else $cumpleRequisitos=false;
					
				} else if($empresa->getSector()->getId()== 2){
					if($empresa->getNivel()->getDinero()<=$empresa->getSaldo()){
						if($empresa->getNivel()->getVehiculos()>$empresa->getVehiculos())
						$cumpleRequisitos=false;	
					}else {$cumpleRequisitos=false;}
				} else{
					if($empresa->getNivel()->getDinero()<=$empresa->getSaldo()){
						if($empresa->getNivel()->getHerramientas()>$empresa->getHerramientas())						
						$cumpleRequisitos=false;
					}else $cumpleRequisitos=false;
				}
			}

			$prestamo =  $em->getRepository('simProductionHomeBundle:Prestamo')->findOneBy(array('empresa' => $empresa_id,'estado' => 1),array('id' => 'DESC'));
			$graficos =  $em->getRepository('simProductionHomeBundle:MovimientoFinanciero')->findBy(array('empresa' => $empresa_id),array('fecha' => 'DESC','id' => 'DESC'));
			$y=1;
			while($y< count($graficos)){
				if($graficos[$y]->getFecha() == $graficos[$y-1]->getFecha() ){
					array_splice($graficos,$y,1);
				}else{	$y++; }
			}
			
			$fecha_actual= new \DateTime();
			if($graficos !=null && $graficos[0]->getFecha() != $fecha_actual){
				$movimiento= new MovimientoFinanciero();
				$movimiento->setFecha(new \DateTime());	
				$movimiento->setEmpresa($graficos[0]->getEmpresa());
				$movimiento->setMotivo("");
				$movimiento->setOperacion(0);
				$movimiento->setSaldo($graficos[0]->getSaldo());
				array_push($graficos, $movimiento);
			}
			$query = $em->createQuery('SELECT tarea FROM simProductionHomeBundle:Tarea tarea WHERE tarea.idAfectado = :id and tarea.tipo > 2 ORDER BY tarea.fecha')->setParameters(array('id' => $empresa_id));
			$prox_gastos = $query->getResult();
			$y=5;
			while($y< count($prox_gastos)){			
				array_splice($prox_gastos,$y,1);				
			}
			$prox_pago=$em->getRepository('simProductionHomeBundle:Tarea')->findOneBy(array('idAfectado' => $empresa_id,'tipo' =>4),array('fecha' => 'ASC'));
			
			return $this->render('simProductionHomeBundle:Default:homepage.html.twig', array('empresa' => $empresa,'prox_pago' => $prox_pago,'prox_gastos' => $prox_gastos,'graficos' => $graficos,'prestamo' => $prestamo,'mensajes_recibidos' => $mensajes_recibidos,'cumpleRequisitos' => $cumpleRequisitos,'formaciones' => $formaciones,'user' => $user,'num_mensajes'=>$num_mensajes) );
		}
    }
	
	public function loginAction()  {
		$user = $this->get('security.context')->getToken()->getUser();	
		if($user === 'anon.'){
			$request = $this->getRequest();
			$session = $request->getSession();
	 
			if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
				$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
			} else {
				$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			}
			$last_username = $session->get(SecurityContext::LAST_USERNAME);		
			$usuario = new Usuario();
			$form = $this->createForm(new UsuarioType(), $usuario);
			return $this->render('simProductionHomeBundle:Default:login.html.twig', array('error' => $error, 'last_username' => $last_username,'form' => $form->createView()));
		}else{		
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			return new RedirectResponse("i/homepage");			
		}
	}
	
	public function logoutAction() {   
		$this->get("request")->getSession()->invalidate();
		$this->get("security.context")->setToken(null);
		return new RedirectResponse("login"); 
    }
	
	public function registroAction(Request $request)  {
		$this->get("request")->getSession()->invalidate();
		$this->get("security.context")->setToken(null);
		$usuario = new Usuario();
		$form = $this->createForm(new UsuarioType(), $usuario,array('csrf_protection' => false));

		if ($this->get("request")->getMethod() == 'POST') {
			$form->bindRequest($this->get("request"));
			if ($form->isValid()) {

				$nuevoUsuario = new Usuario();
				$nuevoUsuario = $form->getData();
				$nuevoUsuario->setSalt('');			
				$nuevoUsuario->setAceptacion(true);			
				$nuevoUsuario->setRol('ROLE_USUARIO');			
				$em = $this->getDoctrine()->getEntityManager();

				$em->persist($nuevoUsuario);
				$em->flush($nuevoUsuario);

				$token = new UsernamePasswordToken($nuevoUsuario,null, 'secured_area', array('ROLE_USUARIO'));
				$this->get('security.context')->setToken($token);
				$empresa=new Empresa();
				$form = $this->createForm(new EmpresaType(), $empresa);	 
				return $this->render('simProductionHomeBundle:Default:configuracion_cuenta.html.twig', array('form' => $form->createView()));
			}
		}
		return $this->render('simProductionHomeBundle:Default:registro.html.twig', array('form' => $form->createView()));
	}
	
	public function configuracion_cuentaAction(Request $request)  {
		$user = $this->get('security.context')->getToken()->getUser();	
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		if($empresa === null){
			if ($request->getMethod() == 'POST') {
				$form = $this->createForm(new EmpresaType(), $empresa);
				$form->bindRequest($request);
				if ($form->isValid()) {
					$session = $this->get('request')->getSession();
					
					$nuevaEmpresa = new Empresa();
					$nuevaEmpresa = $form->getData();
					$dir='user_images';
					$nombre_imagen=md5(rand().time()).$user_id.'.jpg';
					$form['logo']->getData()->move($dir,$nombre_imagen);
					$nuevaEmpresa->setLogo($dir.'/'.$nombre_imagen);
					$nuevaEmpresa->setUsuario($user);
					$cuadrantes= $em->getRepository('simProductionHomeBundle:Cuadrante')->findBy(array('ocupado' => 0));	
					if($cuadrantes==null){
						return new Response ("ERROR! El servidor de empresas está lleno");
					}
					$numaleatorio = rand(0,count($cuadrantes)-1); 
					$cuadrante =$cuadrantes[$numaleatorio];
					$cuadrante->setOcupado(1);
					$em->persist($cuadrante);

					$nuevaEmpresa->setCuadrante($cuadrante);
					$nuevaEmpresa->setSalarios(0);					
					$sector= $nuevaEmpresa->getSector();
					$nivel = $em->getRepository('simProductionHomeBundle:Nivel')->findOneBy(array('sector' => $sector->getId(),'nivel'=>1));
					$nuevaEmpresa->setNivel($nivel);
					$nuevaEmpresa->setSaldo(20000);
					$nuevaEmpresa->setProduccion(0);

					//NIVELES INICIALES DE LA EMPRESA
					if($sector->getId()==1){
						$nuevaEmpresa->setMinerales(0);
						$nuevaEmpresa->setHerramientas(3);
						$nuevaEmpresa->setVehiculos(1);
					}else if ($sector->getId()==1){					
						$nuevaEmpresa->setMinerales(300);
						$nuevaEmpresa->setHerramientas(0);
						$nuevaEmpresa->setVehiculos(1);
					}else{
						$nuevaEmpresa->setMinerales(500);
						$nuevaEmpresa->setHerramientas(10);
						$nuevaEmpresa->setVehiculos(0);
					}
							
					$em->persist($nuevaEmpresa);
					$em->flush($nuevaEmpresa);
					
					//ANYADIR EMPLEADOS
					$emplIni = $em->getRepository('simProductionHomeBundle:FormacionesNivel')->findBy(array('nivel' => $nivel->getId()-1));
					for ($x=0;$x< count($emplIni); $x++){
						echo " TOTAL: ".$emplIni[$x]->getNumEmpleados();
						for ($y=0; $y< $emplIni[$x]->getNumEmpleados(); $y++){
							echo " num ".$y;
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
							$perfil=$emplIni[$x]->getPerfilTrabajador();
							$trabajador->setPerfilTrabajador($perfil);
							$trabajador->setEmpresa($nuevaEmpresa);
							$trabajador->setSector($nuevaEmpresa->getSector());
							$trabajador->setNombre($nombre);
							$trabajador->setApellidos($apellido1.' '.$apellido2);
							$trabajador->setSalario($perfil->getSalarioBase());
							$trabajador->setIncorporacion(new \DateTime());
							$em->persist($trabajador);
							$em->flush($trabajador);
							//formacion
							$formaciones = $em->getRepository('simProductionHomeBundle:Formacion')->findBy(array('PerfilTrabajador' => $perfil->getId()));
							for ($z=0;$z< count($formaciones); $z++){	
								$NFT= new NivelFormacionTrabajador();
								$NFT->setFormacion($formaciones[$z]);
								$NFT->setTrabajador($trabajador);
								$NFT->setFormado(false);
								$em->persist($NFT);
								$em->flush($NFT);				
							}
						}
					}
					//actualizamos pago total de salarios de la empresa
					$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $nuevaEmpresa->getId()));		
					$total=0;
					for ($x=0;$x< count($empleados); $x++){
						$total=$total + $empleados[$x]->getSalario();
					}
					$nuevaEmpresa->setSalarios($total);
					$em->persist($nuevaEmpresa);
					$em->flush($nuevaEmpresa);
					
					//CREAMOS EL PRIMER MOVIMIENTO DE LA EMPRESA
					$movimiento= new MovimientoFinanciero();
					$movimiento->setFecha(new \DateTime());	
					$movimiento->setEmpresa($nuevaEmpresa);
					$movimiento->setMotivo("Apertura de cuenta");
					$movimiento->setOperacion(20000);
					$movimiento->setSaldo(20000);
					$em->persist($movimiento);

					//CREAMOS LAS TAREAS PROGRAMADAS
					//Pagos a empleados
					$i=1;
					$fecha1=new \DateTime();
					while ($i<6){
						$tarea = new Tarea();
						$tarea->setTipo(3);
						$tarea->setIdAfectado($nuevaEmpresa->getId());
						$fecha1->modify("+4 days");
						$fecha1->setTime(0,0,0);
						$tarea->setFecha($fecha1);
						$em->persist($tarea);
						$em->flush($tarea);
						$i++;
					}
					//Produccion
					$fecha1=new \DateTime();
					$tarea = new Tarea();
					$tarea->setTipo(2);
					$tarea->setIdAfectado($nuevaEmpresa->getId());
					$fecha1->modify("+1 hour");
					$fecha1->setTime($fecha1->format("H"),0,0);
					$tarea->setFecha($fecha1);
					$em->persist($tarea);
					$em->flush($tarea);			

					
					return new RedirectResponse("homepage"); 
				}
			}else{
				$empresa=new Empresa();
				$form = $this->createForm(new EmpresaType(), $empresa);	 
				return $this->render('simProductionHomeBundle:Default:configuracion_cuenta.html.twig', array('form' => $form->createView()));
			}
		}else{
			return new RedirectResponse("homepage"); 
		}
	}		
	
	public function cuentaAction()  {	
		$user = $this->get('security.context')->getToken()->getUser();		
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa->getId(), 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);		
		$form1 = $this->createForm(new EmpresaType(), $empresa);
		$form2 = $this->createForm(new UsuarioType(), $user);
		$form3 = $this->createForm(new AutorizacionType(), $user);

		return $this->render('simProductionHomeBundle:Default:cuenta.html.twig', array('empresa' => $empresa,'user' => $user,'num_mensajes'=>$num_mensajes,'form_usuario' => $form2->createView(),'form_empresa' => $form1->createView(),'form_autorizacion' => $form3->createView()) );
    }		
	
	public function empresaAction($id)  {	
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 
			$user = $this->get('security.context')->getToken()->getUser();		
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));			
			$empresaVer = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('id' => $id));
			$lotes = $em->getRepository('simProductionHomeBundle:Lote')->findBy(array('empresa' => $empresaVer->getId()));
			if($empresaVer->getSector() == $empresa->getSector()){
				$lotes=null;
			}						
			$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresaVer->getId()));		
			$numEmpleados=count($empleados);
			return $this->render('simProductionHomeBundle:Default:empresa.html.twig', array('empresa' => $empresa,'empresaVer' => $empresaVer,'lotes' => $lotes,'numEmpleados' => $numEmpleados) );
		}		
    }
	
	public function modificarLogoAction(Request $request)  {	
		if ($request->getMethod() == 'POST') {
			$nuevaEmpresa = new Empresa();
			$form = $this->createForm(new EmpresaType(), $nuevaEmpresa);
			$form->bindRequest($request);
			$user = $this->get('security.context')->getToken()->getUser();		
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();			
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
			$dir='user_images';
			unlink ( $empresa->getLogo() );
			$nombre_imagen=md5(rand().time()).$user_id.'.jpg';
			$form['logo']->getData()->move($dir,$nombre_imagen);						
			$empresa->setLogo($dir.'/'.$nombre_imagen);
			$em->persist($empresa);
			$em->flush($empresa);
			return new RedirectResponse("cuenta");						
		}	
    }		
		
	public function modificarNombreAction(Request $request)  {	
		if ($request->getMethod() == 'POST') {
			$nuevaEmpresa = new Empresa();
			$form = $this->createForm(new EmpresaType(), $nuevaEmpresa);
			$form->bindRequest($request);			
			$nuevaEmpresa = $form->getData();
			$user = $this->get('security.context')->getToken()->getUser();		
			$user_id = $user->getId();
			$em = $this->getDoctrine()->getEntityManager();
			$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));			
			$empresa->setNombre($nuevaEmpresa->getNombre());
			$em->persist($empresa);
			$em->flush($empresa);
			return new RedirectResponse("cuenta");			
			
		}	
    }		
	
	public function modificarEmailAction(Request $request)  {	
		if ($request->getMethod() == 'POST') {
			$usuario = new Usuario();
			$form = $this->createForm(new UsuarioType(), $usuario);
			$form->bindRequest($request);			
			$usuario = $form->getData();
			$user = $this->get('security.context')->getToken()->getUser();		
			$user->setEmail($usuario->getEmail());
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($user);
			$em->flush($user);
			return new RedirectResponse("cuenta");			
		}	
    }		
	
	public function guardarAceptacionAction(Request $request)  {	
		if ($request->getMethod() == 'POST') {
			$usuario = new Usuario();
			$form = $this->createForm(new AutorizacionType(), $usuario);
			$form->bindRequest($request);			
			$usuario = $form->getData();
			$user = $this->get('security.context')->getToken()->getUser();				
			if ($usuario->getAceptacion()==true) {
				$user->setAceptacion(1);
			}
			else  $user->setAceptacion(0);
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($user);
			$em->flush($user);
			return new RedirectResponse("cuenta");		
		}	
    }		
	
	public function modificarPassAction($pass_ant,$pass_new)  {	
		$request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { 
			$usuario = new Usuario();
			$usuario->setPassword($pass_ant);
			$user = $this->get('security.context')->getToken()->getUser();	
			if($usuario->getPassword() == $user->getPassword()) {				
				$user->setPassword($pass_new);		
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($user);
				$em->flush($user);
				return new Response("OK");		
			}else{			
				return new Response("ERROR");		
			}
		}
	}	
    		
	public function mapaAction(Request $request)  {  
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$query = $em->createQuery('SELECT emp FROM simProductionHomeBundle:Empresa emp WHERE emp.id >0');
		$empresas = $query->getResult();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$CTS = $em->getRepository('simProductionHomeBundle:CT')->findBy(array());
		$num_mensajes = sizeof($mensajes_recibidos);
	    return $this->render('simProductionHomeBundle:Default:mapa.html.twig', array('user' => $user,'empresas' => $empresas,'empresa' => $empresa,'CTS' => $CTS,'num_mensajes'=>$num_mensajes) );
    }
	
	public function subirnivelAction(Request $request)  {  
		$user = $this->get('security.context')->getToken()->getUser();
		$user_id = $user->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$empresa = $em->getRepository('simProductionHomeBundle:Empresa')->findOneBy(array('usuario' => $user_id));
		$empresa_id = $empresa->getId();
		$mensajes_recibidos = $em->getRepository('simProductionHomeBundle:Mensaje')->findBy(array('destino' => $empresa_id, 'leido' => false));
		$num_mensajes = sizeof($mensajes_recibidos);
		
		$formaciones = $em->getRepository('simProductionHomeBundle:FormacionesNivel')->findBy(array('nivel' => $empresa->getNivel()->getId()));
		$empleados = $em->getRepository('simProductionHomeBundle:Trabajador')->findBy(array('empresa' => $empresa_id,),array('id' => 'ASC'));		
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
				$formaciones[$x]->setCumpleRequisito(false);	
				$cumpleRequisitos=false;
			}				
		}
		if($cumpleRequisitos==true){
			//comprobar dinero y otras cosas que tb cumplan requisitos					
			if($empresa->getSector()->getId()== 1){
				if($empresa->getNivel()->getDinero()<=$empresa->getSaldo()){
					if($empresa->getNivel()->getHerramientas()<=$empresa->getHerramientas()){
						if($empresa->getNivel()->getVehiculos()>$empresa->getVehiculos())
						$cumpleRequisitos=false;	
					}else $cumpleRequisitos=false;
				}else $cumpleRequisitos=false;
				
			} else if($empresa->getSector()->getId()== 2){
				if($empresa->getNivel()->getDinero()<=$empresa->getSaldo()){
					if($empresa->getNivel()->getVehiculos()>$empresa->getVehiculos())
					$cumpleRequisitos=false;	
				}else {$cumpleRequisitos=false;}
			} else{
				if($empresa->getNivel()->getDinero()<=$empresa->getSaldo()){
					if($empresa->getNivel()->getHerramientas()>$empresa->getHerramientas())						
					$cumpleRequisitos=false;
				}else $cumpleRequisitos=false;
			}
		}
		
		if ($cumpleRequisitos){
			$movimiento= new MovimientoFinanciero();
			$movimiento->setFecha(new \DateTime());	
			$movimiento->setEmpresa($empresa);
			$movimiento->setMotivo("Coste de aumento de nivel de instalaciones");
			$movimiento->setOperacion(-$empresa->getNivel()->getDinero());
			$saldo=$empresa->getSaldo()-$empresa->getNivel()->getDinero();
			$movimiento->setSaldo($saldo);
			$empresa->setSaldo($saldo);
			$em->persist($movimiento);

			$nivel = $em->getRepository('simProductionHomeBundle:Nivel')->findOneBy(array('sector' => $empresa->getSector()->getId(),'nivel' => $empresa->getNivel()->getNivel()+1));
			$empresa->setNivel($nivel);
			$em->persist($empresa);
			$em->flush($empresa);
		}
		return new RedirectResponse("homepage");			
    }
	
	public function termsofuseAction()  {
        return $this->render('simProductionHomeBundle:Default:termsofuse.html.twig', array() );
    }
	
	public function aboutAction()  {
        return $this->render('simProductionHomeBundle:Default:about.html.twig', array() );
    }
	
	public function helpAction()  {
        return $this->render('simProductionHomeBundle:Default:help.html.twig', array() );
    }	
}
