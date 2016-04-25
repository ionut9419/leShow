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

        $er = $em->getRepository('SpectateBundle:Reprezentation');
        $representations = $er->findBySpectate($spectateFound);

        $array = array();
        $er = $em->getRepository('RezervationBundle:Rezervation');
        foreach($representations as $representation){
            $rezervations = $er->findByReprezentation($representation);
            $array[] = $this->getOccupied($rezervations);
        }
        
        return $this->render('SpectateBundle:Spectate:list_spectate_details.html.twig', array(
            'spectate' => $spectateFound,
            'representations' => $representations,
            'seatsOccupied' => $array
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

    public function getOccupied($objects)
    {
        $array = array();

        foreach ($objects as $object) 
        {
            $shit = $object->getSeats();
            $array[] = sizeof($shit);
        }
        $sum = 0;
        for($i=0;$i<sizeof($array);$i++){
            $sum += $array[$i];
        }
        return $sum;   
    }

}
