<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\RoomType;
use App\Form\FieldType\MediaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RoomTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('price', MoneyType::class)
            ->add('media', FileType::class, [
                'label' => 'Media',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                /*'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],*/
            ])
            ->add('adults', NumberType::class,[
                'mapped' => true,
                'html5' => true
            ])
            ->add('childs', NumberType::class,[
                'mapped' => true,
                'html5' => true
            ])
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
            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;
    }
}