<?php

namespace App\Service;

use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;

class TableService
{
    public function __construct(private DataTableFactory $dataTableFactory)
    {
    }

    public function createTableType(string $tableType): DataTable
    {
        return $this->dataTableFactory->createFromType($tableType);
    }


}