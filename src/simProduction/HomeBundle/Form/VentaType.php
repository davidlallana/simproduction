<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
 
class VentaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('cantidad','integer');
		$builder->add('precio','hidden',array('attr'=>
			array('disabled'=>'disabled')));
	}
 
    public function getName()
    {
        return 'venta';
    }
	
	
}