<?php declare(strict_types=1);

namespace App\Table\Frontend;

use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class BookingTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('id', TextColumn::class, ['label' => 'Id', 'searchable' => true])
            ->add('room_type_name', TextColumn::class, ['label' => 'Room Type'])
            ->add('checkin', TextColumn::class, ['label' => 'Checkin Date'])
            ->add('checkout', TextColumn::class, ['label' => 'Checkout Date'])
            ->add('guests', TextColumn::class, ['label' => 'Number of person'])
            ->add('price', TextColumn::class, ['label' => 'Price']);
    }
}