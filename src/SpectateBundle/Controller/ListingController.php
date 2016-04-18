<?php

namespace SpectateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SpectateBundle\Entity\Spectate;

class ListingController extends Controller
{
    public function viewSpectateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $er = $em->getRepository('SpectateBundle:Spectate');

        $spectates = $er->findByStatus(true);

        return $this->render('SpectateBundle:Spectate:list_spectate.html.twig', array(
            'spectates' => $spectates
        ));
    }

    public function viewSpectateDetailsAction(Spectate $spectate)
    {
        $em = $this->getDoctrine()->getManager();
        $er = $em->getRepository('SpectateBundle:Spectate');

        $spectateFound = $er->findOneById($spectate->getId());

        return $this->render('SpectateBundle:Spectate:list_spectate_details.html.twig', array(
            'spectate' => $spectateFound
        ));
    }

    public function viewSpectateReprezentationAction(Spectate $spectate)
    {
        $em = $this->getDoctrine()->getManager();
        $er = $em->getRepository('SpectateBundle:Reprezentation');

        $reprezentationFound = $er->findBySpectate($spectate);

        return $this->render('SpectateBundle:Spectate:list_spectate_representation.html.twig', array(
            'reprezentation' => $reprezentationFound,
            'spectate' => $spectate
        ));
    }

}
