<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Contact;
use UserBundle\Form\ContactType;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
		$contact = new Contact();

		$contactForm = $this->createForm(
			ContactType::class, 
			$contact
		);

        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()) {
                $this->persistData($contact);
                $request->getSession()->getFlashBag()->add('succes', 'Thank you for your feedback');

                return $this->redirectToRoute('user_homepage');
        }

        return $this->render(':default:index.html.twig', array(
                'contactForm' => $contactForm->createView(),
            )
        );
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
