<?php

namespace Application\PlateformeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateRangeValidator extends ConstraintValidator
{

    public function validate($entity, Constraint $constraint)
    {

        $hasEndDate = true;
        if ($constraint->hasEndDate !== null) {
            $hasEndDate = $constraint->hasEndDate;
        }

        if ($entity->getDateDebut() !== null) {
            if ($hasEndDate) {
                if ($entity->getDateFin() !== null) {
                    if ($entity->getDateDebut() > $entity->getDateFin()) {
                        $this->context->buildViolation($constraint->message)
                            ->addViolation();
                        return false;
                    }
                    return true;
                } else {
                    $this->context->buildViolation($constraint->message)
                        ->addViolation();
                    return false;
                }
            } else {
                if ($entity->getDateFin() !== null) {
                    if ($entity->getDateDebut() > $entity->getDateFin()) {
                        $this->context->buildViolation($constraint->message)
                            ->addViolation();
                        return false;
                    }
                }
                return true;
            }
        } else {
            $this->setMessage($constraint->emptyStartDate);
            return false;
        }
    }
}