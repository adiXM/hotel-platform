<?php


namespace App\Form\Frontend\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RoomTypeSearchType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roomTypeId', HiddenType::class)
            ->add('book_button', SubmitType::class, [
                'label' => 'Book',
                'attr' => ['class' => 'btn_4 learn-more']
            ])
        ;

    }
}