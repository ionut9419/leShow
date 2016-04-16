<?php

namespace RezervationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use RezervationBundle\Form\RezervationType;
use RezervationBundle\Entity\Rezervation;

use SpectateBundle\Entity\Reprezentation;

class RezervationController extends Controller
{

    public function rezervationAction(Reprezentation $reprezentation, Request $request)
    {
        $rezervation = new Rezervation();
        $rezervation->setUser($this->getUser());
        $rezervation->setReprezentation($reprezentation);

        $seatNumber = $reprezentation->getNumberOfSeats();
        $seatNumberArray = array();
        for($i=1;$i<=$seatNumber;$i++) $seatNumberArray[$i] = $i;

        $rezervationForm = $this->createFormBuilder($rezervation)
            ->add('details')
            ->add('seats', ChoiceType::class, array(
                'choices' => $seatNumberArray,
                //'choice_attr' => array('checked' => true),
                'expanded' => true,
                'multiple' => true,
                //'required' => true,
                //'attr' => array('checked' => true)
                )
            )
            //->add('reprezentation')
            //->add('user')
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $rezervationForm->handleRequest($request);

        if($rezervationForm->isSubmitted() && $rezervationForm->isValid()) {
                $this->persistData($rezervation);
                $request->getSession()->getFlashBag()->add('succes', 'Rezervation Made Successfully');

                return $this->redirectToRoute('rezervation_make', array(
                    'reprezentation' => $reprezentation->getId()
                ));
        }
        
        $em = $this->getDoctrine()->getManager();
        $er = $em->getRepository('RezervationBundle:Rezervation');

        $r = $er->findByReprezentation($reprezentation);
        var_dump($r[0]->getSeats());
        $array = array();
        $index = 0;
        foreach ($r as $key) {
            $array[$index] = implode(' ',$key->getSeats());
            $index++;
        }

        $occupied = explode(' ',implode(' ',$array));
        var_dump($occupied);
        return $this->render('RezervationBundle:Rezervation:rezervation.html.twig',array(
        	'rezervationForm' => $rezervationForm->createView(),
            'seatNumber' => $seatNumber,
            'occupied' => $occupied
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
