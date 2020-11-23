<?php

namespace App\Logic\Controllers\API;

use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IAPI;

class ProductAPI implements IAPI
{
    /**
     * @var ResourceHandler
     */
    private $resourceHandler;

    /**
     * ProductAPI constructor.
     */
    public function __construct()
    {
        $this->resourceHandler = new ResourceHandler();
    }

    /**
     * Return all items
     *
     * @return void
     */
    public function index(): void
    {

    }

    /**
     * Show a specific item
     *
     * @param $id
     * @return void
     */
    public function show($id): void
    {

    }

    /**
     * Store in database
     *
     * @return void
     */
    public function store(): void
    {

    }

    /**
     * Update a specific item
     *
     * @param $id
     * @return void
     */
    public function update($id): void
    {

    }

    /**
     * Delete a specific item
     *
     * @param $id
     * @return void
     */
    public function delete($id): void
    {

    }
}