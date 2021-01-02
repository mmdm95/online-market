<?php

namespace App\Logic\Interfaces;

interface IDatatableController
{
    /**
     * @param array $_
     * @return void
     */
    public function getPaginatedDatatable(...$_): void;
}