<?php

namespace UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            // ->add('usernameCanonical')
            ->add('email')
            // ->add('emailCanonical')
            ->add('enabled')
            // ->add('salt')
            // ->add('password')
            ->add('lastLogin')
            ->add('locked')
            // ->add('expired')
            // ->add('expiresAt')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            //->add('roles')
            // ->add('credentialsExpired')
            // ->add('credentialsExpireAt')
            // ->add('id')
            ->add('firstName')
            ->add('lastName')
            ->add('contactNumber')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('firstName')
            ->add('lastName')
            ->add('username')
            // ->add('usernameCanonical')
            ->add('email')
            // ->add('emailCanonical')
            ->add('contactNumber')
            ->add('enabled')
            // ->add('salt')
            // ->add('password')
            ->add('lastLogin')
            ->add('reservations')
            //->add('locked')
            // ->add('expired')
            // ->add('expiresAt')
            //->add('confirmationToken')
            //->add('passwordRequestedAt')
            //->add('roles')
            // ->add('credentialsExpired')
            // ->add('credentialsExpireAt')
            // ->add('id')
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
            ->add('firstName')
            ->add('lastName')
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('contactNumber')
            ->add('lastLogin')
            ->add('passwordRequestedAt')
            ->add('roles')
            // ->add('confirmationToken')
            ->add('enabled')
            // ->add('salt')
            // ->add('password')
            ->add('locked')
            ->add('expired')
            // ->add('expiresAt')
            ->add('credentialsExpired')
            // ->add('credentialsExpireAt')
            // ->add('id')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('firstName')
            ->add('lastName')
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('contactNumber')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('confirmationToken')
            ->add('expired')
            ->add('expiresAt')
            ->add('passwordRequestedAt')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('roles')
            ->add('enabled')
            ->add('locked')
        ;
    }
}
