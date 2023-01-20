<?php

namespace App\Form\Booking;

use App\Entity\Customer;
use App\Entity\Room;
use App\Repository\MediaRepository;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;


class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => function (Room $room) {
                    return $room->getRoomType()->getName(). ' - '. $room->getRoomNumber();
                },
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => function (Customer $customer) {
                    return $customer->getFirstName() . ' ' . $customer->getLastName();
                }
            ])
            ->add('adults', NumberType::class,[
                'html5' => true,
                'data' => '0',
                'attr' => ['min' => 0]
            ])
            ->add('childs', NumberType::class,[
                'html5' => true,
                'data' => '0',
                'attr' => ['min' => 0]
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
                'required' => false
            ])

            ->add('save', SubmitType::class, [
                'row_attr' => ['class' => 'mb-auto'],
            ])
        ;

    }
}