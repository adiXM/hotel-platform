<?php

namespace App\Service;

use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;

class TableService implements TableServiceInterface
{
    public function __construct(private readonly DataTableFactory $dataTableFactory)
    {
    }

    public function createTableType(string $tableType): DataTable
    {
        return $this->dataTableFactory->createFromType($tableType);
    }

    public function createTable(): DataTable
    {
        return $this->dataTableFactory->create();
    }


}