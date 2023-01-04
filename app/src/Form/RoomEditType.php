<?php

namespace App\Form;

use App\Entity\RoomType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RoomEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roomType', EntityType::class, [
                'class' => RoomType::class,
                'choice_label' => 'name',
            ])
            ->add('roomNumber', NumberType::class)
            ->add('price', TextType::class)
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;
    }
}