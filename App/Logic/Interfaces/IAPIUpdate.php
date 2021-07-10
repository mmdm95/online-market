<?php

namespace App\Logic\Interfaces;

interface IAPIUpdate
{
    /**
     * Update a specific item with a 200 response code
     *
     * @param $id
     * @return void
     */
    public function update($id): void;
}
