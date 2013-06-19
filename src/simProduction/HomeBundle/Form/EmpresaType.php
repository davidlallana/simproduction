<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;
 
class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
       $builder->add('nombre');
       $builder->add('logo','file');
       $builder->add('sector');
	}
	
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'simProduction\HomeBundle\Entity\Empresa'
        );
    }
	
    public function getName()
    {
        return 'configuracion_cuenta';
    }
	
	
}