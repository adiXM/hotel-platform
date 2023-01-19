<?php

namespace App\Form\Frontend\Homepage;

use App\Entity\Amenity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AmenityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amenities', EntityType::class, [
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
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            // check if the field that loads entities has changed
            if ($form->get('amenities')->isSubmitted()) {
               //return $data;
            }
        });
    }
}