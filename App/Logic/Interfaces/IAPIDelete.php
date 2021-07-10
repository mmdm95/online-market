<?php

namespace App\Logic\Interfaces;

interface IAPIDelete
{
    /**
     * Delete a specific item with a 204 response code
     *
     * @param $id
     * @return void
     */
    public function delete($id): void;
}
