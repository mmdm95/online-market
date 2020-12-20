<?php

namespace App\Logic\Handlers;

class ResourceHandler
{
    /**
     * @var array $data
     */
    protected $data = [];

    /**
     * ResourceHandler constructor.
     */
    public function __construct()
    {
        $this->resetData();
    }

    /**
     * @param int $code
     * @return static
     */
    public function statusCode(int $code)
    {
        $this->data['status_code'] = $code;
        return $this;
    }

    /**
     * @param string $type
     * @return static
     */
    public function type(string $type)
    {
        $this->data['type'] = $type;
        return $this;
    }

    /**
     * @param string|null $msg
     * @return static
     */
    public function errorMessage(?string $msg)
    {
        $this->data['error'] = $msg;
        return $this;
    }

    /**
     * @param $data
     * @return static
     */
    public function data($data)
    {
        $this->data['data'] = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getReturnData(): array
    {
        return $this->data;
    }

    /**
     * Reset data
     *
     * @return static
     */
    public function resetData()
    {
        $this
            ->statusCode(200)
            ->type(RESPONSE_TYPE_SUCCESS)
            ->errorMessage(null)
            ->data([]);

        return $this;
    }
}