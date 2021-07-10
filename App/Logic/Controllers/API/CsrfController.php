<?php

namespace App\Logic\Controllers\API;

class CsrfController
{
    /**
     * Show a specific item
     *
     * @return void
     */
    public function show()
    {
        response()->httpCode(204);
        echo '';
    }
}
