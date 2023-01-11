<?php

namespace App\Table;

use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class RoomTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable->add('id', TextColumn::class, ['label' => 'Id', 'searchable' => true])
            ->add('room_type_name', TextColumn::class, ['label' => 'Room Type'])
            ->add('room_number', TextColumn::class, ['label' => 'Room Number'])
            ->add('active', BoolColumn::class, ['label' => 'Active'])
            ->add('actions', TwigStringColumn::class, [
                'label' => 'Actions',
                'template' => '<a class="btn btn-secondary" role="button" href="{{ path(\'admin_edit_room\', {id: row.id}) }}">Manage</a><button type="button" class="ms-2 btn btn-danger btn-delete" data-rowId="{{ row.id }}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        Delete
                    </button>',
            ]);
    }
}