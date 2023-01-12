<?php

namespace App\Form\Frontend;

use App\Form\FieldType\PasswordRepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('plainPassword', PasswordRepeatedType::class,['mapped' => false])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'w-100 btn_1 mb-3 text-white'],
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;

    }
}