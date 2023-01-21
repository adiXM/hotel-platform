<?php

namespace App\Table;

use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class CustomerTableType implements DataTableTypeInterface
{

    public function configure(DataTable $dataTable, array $options): void
    {
        $manageButton = '<a class="btn btn-secondary" role="button" href="{{ path(\'admin_edit_customer\', {id: row.id}) }}">Manage</a>';
        $deleteButton = '<button type="button" class="ms-2 btn btn-danger btn-delete" data-rowId="{{ row.id }}" 
                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        Delete
                    </button>';

        $dataTable->add('id', TextColumn::class, ['label' => 'Id', 'searchable' => true])
            ->add('firstname', TextColumn::class, ['label' => 'First Name'])
            ->add('lastname', TextColumn::class, ['label' => 'Last Name'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('phone', TextColumn::class, ['label' => 'Phone'])
            ->add('actions', TwigStringColumn::class, [
                'label' => 'Actions',
                'template' => $manageButton . $deleteButton,
            ]);
    }

}