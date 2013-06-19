<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints as Assert;

class AutorizacionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('aceptacion');
    }
 
    public function getName()
    {
        return 'aceptacion';
    }
	
	
}