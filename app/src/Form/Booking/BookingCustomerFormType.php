<?php

namespace App\Form\Booking;

use App\Form\FieldType\PasswordRepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BookingCustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'disabled' => true
            ])
            ->add('lastname', TextType::class, [
            'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'disabled' => true
            ])
            ->add('phone', TextType::class,  [
                'disabled' => true
            ])
        ;

    }
}