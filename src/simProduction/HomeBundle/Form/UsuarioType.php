<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints as Assert;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('username','text',array('label'=>'','attr'=> array('placeholder'=>'Nombre de usuario','autocomplete' =>'off')));
		$builder->add('password','password',array('label'=>'','attr'=> array('placeholder'=>'Contraseña','autocomplete' =>'off')));
        $builder->add('email', 'email',array('label'=>'','attr'=> array('placeholder'=>'Correo electrónico')));
    }
 
    public function getName()
    {
        return 'registro';
    }
	
	
}