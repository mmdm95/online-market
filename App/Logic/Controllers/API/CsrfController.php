<?php

namespace App\Logic\Controllers\API;

class CsrfController
{
    public function show()
    {
        response()->httpCode(200)->json([
            'csrfToken' => csrf_token(),
        ]);
    }
}
