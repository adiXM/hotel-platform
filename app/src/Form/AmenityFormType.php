<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AmenityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('icon_class', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'iconpicker'],
            ])
            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;

    }
}