<?php

namespace App\Logic;

use Sim\Interfaces\IInitialize;

class Helper implements IInitialize
{
    /**
     * Return an array of helpers that you need to load
     */
    public function init()
    {
        // helper path string that is
        // usually in logic helper folder
        // and prefixed by
        // __DIR__ . '/Helpers/'
        return [
            __DIR__ . '/Helpers/persian-support.php',
            __DIR__ . '/Helpers/handy-helper.php',
            __DIR__ . '/Helpers/config-helper.php',
            __DIR__ . '/Helpers/easy-file-manager.php',
            __DIR__ . '/Helpers/pagination-helper.php',
        ];
    }
}