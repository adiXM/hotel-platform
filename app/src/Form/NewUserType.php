<?php

namespace App\Form;

use App\Form\FieldType\PasswordRepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', TextType::class)
            ->add('LastName', TextType::class)
            ->add('Email', EmailType::class)
            ->add('plainPassword', PasswordRepeatedType::class,['mapped' => false])
            ->add('save', SubmitType::class)
        ;

    }
}