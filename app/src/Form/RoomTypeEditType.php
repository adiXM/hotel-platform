<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\Media;
use App\Entity\RoomType;
use App\Form\FieldType\MediaType;
use App\Repository\MediaRepository;
use App\Repository\RoomTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('media_select', FileType::class, [
                'label' => 'Add more media files',
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
            ->add('media', EntityType::class, [
                'class' => Media::class,
                'attr' => ['class' => 'custom-class'],
                'label_html' => true,
                'choice_name' => 'id',
                'choice_label' => function ($val, $key, $index) use ($options) {

                    /** @var Media $media */
                    $media = $val;

                    return '<img width=200 class="img-fluid" src='.$options['public_media_directory'].'/'.$media->getFileName().'>';
                },
                'query_builder' => function (MediaRepository $er) use ($options) {

                    return $er->createQueryBuilder('m')
                        ->join('m.roomTypes', 'r')
                        ->addSelect('r')
                        ->where('r.id = :roomTypeId')
                        ->setParameter('roomTypeId', $options['roomTypeId']);


                },
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('adults', NumberType::class,[
                'html5' => true,
                'attr' => ['min' => 0, 'max' => 10],
                'constraints' => [
                    new GreaterThanOrEqual(0),
                ],
            ])
            ->add('childs', NumberType::class,[
                'html5' => true,
                'attr' => ['min' => 0, 'max' => 10],
                'constraints' => [
                    new GreaterThanOrEqual(0),
                ],
            ])
            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoomType::class,
            'roomTypeId' => null,
            'public_media_directory' => null,
            'media_directory' => null
        ]);
    }
}