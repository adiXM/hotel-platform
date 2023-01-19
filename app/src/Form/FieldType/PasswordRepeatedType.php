<?php

namespace App\Form\FieldType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordRepeatedType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => PasswordType::class,
            'attr' => [
                'autocomplete' => 'new-password'
            ],
            'required' => false,
            'first_options' => [
               'label' => 'Password',
               'constraints' => [
                   new Length(['min' => 6, 'minMessage' => 'Password should be at least {{ limit }} characters', 'max' => 4096,]),
               ],
            ],
            'second_options' => [
                'label' => 'Repeat Password'
            ],
            'invalid_message' => 'Passwords do not match',
            'empty_data' => ''
        ]);
    }

    public function getParent(): string
    {
        return RepeatedType::class;
    }
}