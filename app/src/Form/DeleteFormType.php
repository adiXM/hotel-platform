<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rowId', HiddenType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'hidden',
                ],
            ])
        ;

    }
}