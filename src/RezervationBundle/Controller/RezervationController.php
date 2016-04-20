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
        $rezervationForm = $this->createForm(RezervationFormType::class);

        $rezervationForm->handleRequest($request);

        $rezervation = new Rezervation();
        $rezervation = $rezervationForm->getData();

        if($rezervationForm->isSubmitted() && $rezervationForm->isValid()) {

                $rezervation->setUser($this->getUser());
                $this->persistData($rezervation);
                $request->getSession()->getFlashBag()->add('succes', 'You successfully made a reservation');

                return $this->redirectToRoute('reservation_make');
        }

        return $this->render('RezervationBundle:Rezervation:rezervation.html.twig',array(
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
