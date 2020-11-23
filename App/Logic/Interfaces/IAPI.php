<?php

namespace App\Logic\Interfaces;

interface IAPI
{
    /**
     * Return all items
     *
     * @return void
     */
    public function index(): void;

    /**
     * Show a specific item
     *
     * @param $id
     * @return void
     */
    public function show($id): void;

    /**
     * Store in database with a 201 response code
     *
     * @return void
     */
    public function store(): void;

    /**
     * Update a specific item with a 200 response code
     *
     * @param $id
     * @return void
     */
    public function update($id): void;

    /**
     * Delete a specific item with a 204 response code
     *
     * @param $id
     * @return void
     */
    public function delete($id): void;
}