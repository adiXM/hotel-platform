<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\RoomType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amenities', EntityType::class, [
                'class' => Amenity::class,
                'label_html' => true,
                'by_reference' => false,
                'choice_label' => function ($val, $key, $index) {
                    /** @var Amenity $amenity */
                    $amenity = $val;

                    $icon = $amenity->getIconClass();
                    return '<i class="' . $icon . '"></i> ' . $amenity->getName();
                },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;
    }
}