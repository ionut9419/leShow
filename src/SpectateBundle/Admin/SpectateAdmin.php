<?php

namespace SpectateBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SpectateAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('author')
            ->add('director')
            ->add('duration')
            ->add('status')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {   

        $listMapper
            ->addIdentifier('name')
            //->add('id')
            //->add('name')
            ->add('author')
            //->add('description')
            ->add('director')
            ->add('duration', null, array('label' => 'Duration(minutes)'))
            ->add('image')
            ->add('reprezentations', null, array('label' => 'Representations'))
            ->add('status')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {   
        $formMapper
            //->add('id')
            ->add('name')
            ->add('author')
            ->add('description')
            ->add('director')
            ->add('duration')
            ->add('image')
            ->add('reprezentations', null, array('label' => 'Representations'))
            ->add('status')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            //->add('id')
            ->add('name')
            ->add('author')
            ->add('description')
            ->add('director')
            ->add('duration')
            ->add('image')
            ->add('reprezentations', null, array('label' => 'Representations'))
            ->add('status')
        ;
    }
}
