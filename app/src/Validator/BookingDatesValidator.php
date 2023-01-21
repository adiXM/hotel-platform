<?php

namespace App\Validator;

use App\Service\HelperService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BookingDatesValidator extends ConstraintValidator
{
    public function __construct(private readonly HelperService $helperService)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof BookingDates) {
            throw new UnexpectedTypeException($constraint, BookingDates::class);
        }


        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $resultDates = explode('>', $value);

        if(\count($resultDates) !== 2) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

        $dates = $this->helperService->getDates($value);
        $checkin = $this->helperService->transformDates('m-d-y', $dates['checkin'], 'd-m-Y');
        $checkout = $this->helperService->transformDates('m-d-y', $dates['checkout'], 'd-m-Y');
        if($checkin === '' || $checkout === '') {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}