<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;

class RegistrationController extends BaseRegistrationController
{
    public function registerSpectatorAction(Request $request)
    {
        $formFactory = $this->get('fos_user.registration.form.factory');
        $formFactory->setType('UserBundle\Form\Type\UserType');
        return $this->registerAction($request);
    }

    public function registerAdministratorAction(Request $request)
    {
        $formFactory = $this->get('fos_user.registration.form.factory');
        $formFactory->setType('UserBundle\Form\Type\UserType');
        return $this->registerAction($request);
    }
}
