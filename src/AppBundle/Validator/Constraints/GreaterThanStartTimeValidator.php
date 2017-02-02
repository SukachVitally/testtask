<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class GreaterThanFieldValidator
 * @package AppBundle\Validator\Constraints
 */
class GreaterThanStartTimeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value > $this->context->getObject()->getStartTime()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }

}
