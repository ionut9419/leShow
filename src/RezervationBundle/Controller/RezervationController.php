<?php

namespace RezervationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Extension\FormExtension;

use RezervationBundle\Form\RezervationFormType;
use RezervationBundle\Entity\Rezervation;

use SpectateBundle\Entity\Reprezentation;

class RezervationController extends Controller
{

    public function rezervationAction(Request $request)
    {
        $rezervation = new Rezervation();
        $rezervation->setUser($this->getUser());

        $rezervationForm = $this->createForm(
            RezervationFormType::class
        );

        //var_dump($request);
        $rezervationForm->handleRequest($request);

        //var_dump($rezervationForm->getData());
        // $rezervation->setReprezentation($rezervationForm->getData();
        // $rezervation->setSeats($rezervationForm->getData()['seats']);

        // if($rezervationForm->isSubmitted() && $rezervationForm->isValid()) {
        //         //$this->persistData($rezervation);
        //         $request->getSession()->getFlashBag()->add('succes', 'You successfully made a reservation');

        //         return $this->redirectToRoute('reservation_make');
        // }
           
        return $this->render('RezervationBundle:Rezervation:final_rezervation.html.twig',array(
        	'rezervationForm' => $rezervationForm->createView()
        	));
    }

    private function persistData($data){
        try{
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($data);
            $em->flush();
        } catch(Exception $e) {
            return false;
        }
        return true;
    }
}
