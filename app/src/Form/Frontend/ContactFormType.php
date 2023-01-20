<?php

namespace App\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('email',EmailType::class)
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 6],
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'w-100 btn_1 mb-3 text-white'],
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;

    }
}