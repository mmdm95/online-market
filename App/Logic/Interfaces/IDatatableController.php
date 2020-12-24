<?php

namespace App\Logic\Interfaces;

interface IDatatableController
{
    /**
     * @return void
     */
    public function getPaginatedDatatable(): void;
}