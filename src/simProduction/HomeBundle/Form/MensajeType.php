<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;
 
class MensajeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $id = $options['id'];
        $builder->add('origen', 'entity',
            array('label' => 'origen',
                'class' => 'simProductionHomeBundle:Empresa',
                'property' => 'nombre',
                'required' => 'true',
				'attr'=> array('style'=>'display:none')
                )
              );
			  
       $builder->add('destino2', 'text');
	
       $builder->add('mensaje','textarea', array(
                'required' => 'true',
                'label'    => ' '
            ));
	}
	
    public function getDefaultOptions(array $options)
    {
		 return $options ;
    }
	
    public function getName()
    {
        return 'new_mensaje';
    }
	
	
}