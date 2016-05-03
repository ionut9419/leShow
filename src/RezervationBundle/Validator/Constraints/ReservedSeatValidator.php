<?php

// src/AppBundle/Validator/Constraints/ContainsAlphanumericValidator.php
namespace RezervationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ReservedSeatValidator extends ConstraintValidator
{
	public $em;
	
	public function __construct($em) {
		$this->em = $em;
	} 

    public function validate($reservation, Constraint $constraint)
    {
        $er = $this->em->getRepository('RezervationBundle:Rezervation');
        $rezervations = $er->findByReprezentation($reservation->getReprezentation());

        $reservedSeats = $this->getOccupied($rezervations);

        $seatsArray = explode(" ",$reservation->getSeats());

        foreach($seatsArray as $seat) {
        	if(in_array($seat, $reservedSeats)) 
        		$this->context->buildViolation($constraint->message)
        			->atPath('seats')
	                ->addViolation();
        }
	}

    public function getOccupied($objects)
    {
        $array = array();

        foreach ($objects as $object) 
        {
            $shit = explode(" ",$object->getSeats());

            for($i=0;$i<sizeof($shit);$i++) 
            {
                $array[] = (int)$shit[$i];
            }
        }

        return $array;
    }
}