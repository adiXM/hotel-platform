<?php

namespace App\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('email',EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 6],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('human',TextType::class, [
                'label'=> 'Are you human? 3 + 1 =',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn_1 outline'],
                'label' => 'Submit',
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;

    }
}