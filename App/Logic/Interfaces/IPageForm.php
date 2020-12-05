<?php

namespace App\Logic\Interfaces;

interface IPageForm
{
    /**
     * @return array
     */
    public function validate(): array;

    /**
     * @return bool
     */
    public function store(): bool;
}