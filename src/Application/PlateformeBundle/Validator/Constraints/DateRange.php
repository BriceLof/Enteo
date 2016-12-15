<?php

namespace Application\PlateformeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateRange extends Constraint{

    public $message = "la date de début doit être ultérieur à la date de fin de l'accompagnement";
    public $emptyStartDate = "date de début vide ";
    public $emptyEndDate = "date de fin vide ";

    public $hasEndDate = true;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'daterange_validator';
    }
}
