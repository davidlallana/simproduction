<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;
 
class NombreUsuarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('username','text',array('label'=>''));
    }
 
    public function getName()
    {
        return 'configuracion';
    }
	
	
}