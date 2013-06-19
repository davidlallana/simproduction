<?php

namespace simProduction\HomeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;


class CTAdmin extends Admin {
	/**
	* @param \Sonata\AdminBundle\Form\FormMapper $formMapper
	* @return void
	*/  
	protected function configureFormFields(FormMapper $formMapper)    {
        $formMapper
			->add('nombre')
			->add('descripcion')
			->add('imagen')
			->add('cuadrante')
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
			->add('descripcion')
			->add('imagen')
			->add('cuadrante')        ;
    }
	/**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {

		$listMapper
			->add('nombre')
			->add('descripcion')
			->add('imagen')
			->add('cuadrante')
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