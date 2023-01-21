<?php

namespace App\Form\Frontend\Homepage;

use App\Validator\BookingDates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

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
                'row_attr' => ['class' => 'form-group'],
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new BookingDates()
                ]
            ])
            ->add('adults', IntegerType::class, [
                'mapped' => false,
                'attr' => [
                    'name' => 'adults',
                    'class' => 'qty',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new Positive(),
                ]
            ])
            ->add('childs', IntegerType::class, [
                'mapped' => false,
                'attr' => [
                    'name' => 'childs',
                    'class' => 'qty',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new PositiveOrZero()
                ]
            ])
            ->add('search_button', SubmitType::class, [
                'label' => 'Search',
                'attr' => ['class' => 'btn_search']
            ])
        ;
    }
}