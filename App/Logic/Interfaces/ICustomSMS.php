<?php

namespace App\Logic\Interfaces;

interface ICustomSMS
{
    /**
     * @param array $numbers
     * @param string $body
     * @return mixed
     */
    public static function send(array $numbers, string $body);
}