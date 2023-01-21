<?php

namespace App\Table;

use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class AmenityTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable->add('id', TextColumn::class, ['label' => 'Id', 'searchable' => true])
            ->add('name', TextColumn::class, ['label' => 'Type name'])
            ->add('icon', TwigStringColumn::class, [
                'label' => 'Icon',
                'template' => '<i class="{{row.icon_class}}"></i>',

            ])
            ->add('actions', TwigStringColumn::class, [
                'label' => 'Actions',
                'template' => '<a class="btn btn-secondary" role="button" href="{{ path(\'admin_edit_amenity\', {id: row.id}) }}">Manage</a><button type="button" class="ms-2 btn btn-danger btn-delete" data-amenityId="{{ row.id }}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        Delete
                    </button>',
            ]);
    }
}