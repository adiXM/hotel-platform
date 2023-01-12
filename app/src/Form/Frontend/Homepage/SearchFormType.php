<?php

namespace App\Form\Frontend\Homepage;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dates', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'readonly' => 'readonly',
                    'placeholder' => 'Check in / Check out',
                    'name' => 'dates',
                ],
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('adults', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'name' => 'adults',
                    'class' => 'qty',
                ]
            ])
            ->add('childs', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'name' => 'childs',
                    'class' => 'qty',
                ]
            ])
            ->add('search_button', SubmitType::class, [
                'label' => 'Search',
                'attr' => ['class' => 'btn_search']
            ])
        ;
    }
}