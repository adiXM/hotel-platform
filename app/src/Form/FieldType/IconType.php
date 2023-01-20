<?php

namespace App\Form\FieldType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => ChoiceType::class,
            'required' => true,
            'choices' => [
                'Apple' => 1,
                'Banana' => 2,
                'Durian' => 3,
            ],
        ]);
    }
}