<?php

namespace App\Logic\Interfaces;

interface IAjaxController
{
    /**
     * @return void
     */
    public function add(): void;

    /**
     * @param $id
     * @return void
     */
    public function edit($id): void;

    /**
     * @param $id
     * @return void
     */
    public function remove($id): void;

    /**
     * @param $id
     * @return void
     */
    public function get($id): void;
}