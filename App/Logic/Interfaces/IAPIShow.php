<?php

namespace App\Logic\Interfaces;

interface IAPIShow
{
    /**
     * Show a specific item
     *
     * @param $id
     * @return void
     */
    public function show($id): void;
}
