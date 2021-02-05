<?php

namespace App\Logic\Interfaces;

interface ISelect2Controller
{
    /**
     * @param array $_
     * @return void
     */
    public function getPaginatedSelect2(...$_): void;
}