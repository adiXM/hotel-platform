<?php

namespace App\Form\Frontend\Booking;

use App\Form\FieldType\PasswordRepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('notes', TextareaType::class, [
                'label' => 'Special requests',
                'required' => false
            ])
            ->add('book_now', SubmitType::class, [
                'attr' => ['class' => 'w-100 btn_1 mb-3 text-white'],
                'row_attr' => ['class' => 'mb-auto'],
                'label' => 'Book now'
            ])
        ;

    }
}