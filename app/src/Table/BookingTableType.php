<?php

namespace App\Table;

use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class BookingTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable->add('id', TextColumn::class, ['label' => 'Id', 'searchable' => true])
            ->add('room_type_name', TextColumn::class, ['label' => 'Room Type'])
            ->add('room_number', TextColumn::class, ['label' => 'Room Number'])
            ->add('checkin', TextColumn::class, ['label' => 'Checkin Date'])
            ->add('checkout', TextColumn::class, ['label' => 'Checkout Date'])
            ->add('price', TextColumn::class, ['label' => 'Price'])
            ->add('actions', TwigStringColumn::class, [
                'label' => 'Actions',
                'template' => '<a class="btn btn-secondary" role="button" href="{{ path(\'admin_edit_room_type\', {id: row.id}) }}">Manage</a><button type="button" class="ms-2 btn btn-danger btn-delete" data-amenityId="{{ row.id }}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        Delete
                    </button>',
            ]);
    }
}