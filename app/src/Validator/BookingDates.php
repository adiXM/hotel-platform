<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;


class BookingDates extends Constraint
{
    public $message = 'The string "{{ string }}" is not a booking date format.';

    #[HasNamedArguments]
    public function __construct(array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
    }

    public function validatedBy()
    {
        return static::class.'Validator';
    }

}