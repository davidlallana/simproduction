<?php
 
namespace simProduction\HomeBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;
 
class TrabajadorType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $id = $options['id'];
		$builder->add('perfilTrabajador', 'entity',
            array('label' => ' ',
                'class' => 'simProductionHomeBundle:PerfilTrabajador',
                'property' => 'nombre',
                'required' => 'true',
                'empty_value' => ' ',
				'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) use ($id) {
					 $qb = $repository->createQueryBuilder('l');
					 $qb->add('where', 'l.sector = ?1');
					 $qb->setParameter(1, $id);
					 $qb->addOrderBy('l.nombre', 'asc');
					return $qb;
				  })
              ); 
	}
	
    public function getDefaultOptions(array $options)    {
		 return $options ;
    }
	
    public function getName()    {
        return 'trabajador';
    }
	
	
}