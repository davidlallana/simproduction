<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

 
class LoteType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
       
        $builder->add('cantidad');
        $builder->add('precio');
	}
	
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'simProduction\HomeBundle\Entity\Lote'
        );
    }
	
    public function getName()
    {
        return 'formLote';
    }
	
	
}