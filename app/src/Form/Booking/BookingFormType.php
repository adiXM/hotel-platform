<?php

namespace App\Form\Booking;

use App\Entity\Customer;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * TODO: de adaugat numarul de oaspeti (adulti si copii)
 * De adaugat factura
 * De facut functionalitatea care pune pretul in functie de camera selectata
 * De adaugat Setarile site-ului, date care vor aparea si pe factura
 * 
*/

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => function (Room $room) {
                    return $room->getRoomType()->getName(). ' - '. $room->getRoomNumber();
                }
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => function (Customer $customer) {
                    return $customer->getFirstName() . ' ' . $customer->getLastName();
                }
            ])
            ->add('adults', NumberType::class,[
                'mapped' => false,
                'html5' => true
            ])
            ->add('children', NumberType::class,[
                'mapped' => false,
                'html5' => true
            ])
            ->add('checkin', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('checkout', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('note', TextareaType::class, [
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'disabled' => true
            ])

            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;

    }
}