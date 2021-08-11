<?php

namespace App\Logic\Controllers\API;

class CsrfController
{
    public function show()
    {
        response()->httpCode(204);
        echo '';
    }
}
