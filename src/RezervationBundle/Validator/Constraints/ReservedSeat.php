<?php

namespace RezervationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ReservedSeat extends Constraint
{
    public $message = 'One or many of your selected seats have been booked by someone';

    public function validatedBy()
	{
	    return 'reserved_seat';
	}

	public function getTargets()
	{
	    return self::CLASS_CONSTRAINT;
	}
}