<?php

namespace App\Logic\Interfaces;

interface IAPIStore
{
    /**
     * Store in database with a 201 response code
     *
     * @return void
     */
    public function store(): void;
}
