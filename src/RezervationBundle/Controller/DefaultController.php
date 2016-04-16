<?php

namespace RezervationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RezervationBundle:Default:index.html.twig');
    }
}
