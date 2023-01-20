<?php

namespace App\Service;

use Omines\DataTablesBundle\DataTable;

interface TableServiceInterface
{
    public function createTableType(string $tableType): DataTable;

    public function createTable(): DataTable;
}