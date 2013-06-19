<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints as Assert;

class PrestamoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('saldoSolicitado','text', array('label' => 'Cantidad a solicitar'));
		$builder->add('interes','text', array('label' => 'Interés','attr'=> array('disabled'=>'disabled')));
		$builder->add('totalDevolver','text',  array('label' => 'Total a devolver', 'attr'=> array('disabled'=>'disabled') ));
		$builder->add('plazos_restantes','text', array('label' => 'Plazos'));
	}
 
    public function getName()
    {
        return 'prestamo';
    }
	
	
}