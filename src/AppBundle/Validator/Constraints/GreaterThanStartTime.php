<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GreaterThanStartTime extends Constraint
{
    /**
     * @var string
     */
    public $message = 'End time should be more than start time';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'greater_than_start_time_validator';
    }

}