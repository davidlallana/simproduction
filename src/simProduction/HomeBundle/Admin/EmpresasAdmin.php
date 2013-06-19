<?php

namespace simProduction\HomeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;


class EmpresasAdmin extends Admin {
	/**
	* @param \Sonata\AdminBundle\Form\FormMapper $formMapper
	* @return void
	*/  
	protected function configureFormFields(FormMapper $formMapper)    {
        $formMapper
			->add('nombre')
			->add('usuario')
			->add('nivel')
			->add('sector')
			->add('cuadrante')
			->add('saldo')
			->add('logo')
			->add('minerales')
			->add('herramientas')
			->add('vehiculos')
			->add('produccion')
        ;
    }
	
    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
			->add('nombre')
			->add('usuario')
			->add('nivel')
			->add('sector')
			->add('cuadrante')
			->add('saldo')
			->add('logo')
			->add('minerales')
			->add('herramientas')
			->add('vehiculos')
			->add('produccion')
        ;
    }
	/**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {

		$listMapper
			->add('nombre')
			->add('usuario')
			->add('nivel')
			->add('sector')
			->add('cuadrante')
			->add('saldo')
			->add('logo')
			->add('minerales')
			->add('herramientas')
			->add('vehiculos')
			->add('produccion')
		// add custom action links
        ->add('_action', 'actions', array(
            'actions' => array(
                'edit' => array(),
                'delete' => array(),
            )
        ))
    ;
    }

    /**
     * @return array
     */
    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        $actions['enabled'] = array(
            'label' => $this->trans('batch_enable_comments'),
            'ask_confirmation' => true,
        );

        $actions['disabled'] = array(
            'label' => $this->trans('batch_disable_comments'),
            'ask_confirmation' => false
        );

        return $actions;
    }
    
}