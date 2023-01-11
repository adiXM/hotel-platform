<?php

namespace App\Form;

use App\Entity\Amenity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RoomTypeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', MoneyType::class)
            ->add('amenities', EntityType::class, [
                'class' => Amenity::class,
                'label_html' => true,
                'choice_label' => function ($val, $key, $index) {
                    /** @var Amenity $amenity */
                    $amenity = $val;

                    $icon = $amenity->getIconClass();
                    return '<i class="' . $icon . '"></i> ' . $amenity->getName();
                },
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;
    }
}