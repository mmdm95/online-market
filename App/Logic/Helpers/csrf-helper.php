<?php

use App\Logic\Adapters\SessionTokenProvider;

function csrf_token_regenerate()
{
    // generate/regenerate csrf token if it is expired or not exists for first time
    $csrfCookie = cookie()->get(SessionTokenProvider::CSRF_KEY);
    if (empty($csrfCookie)) {
        container()->get(SessionTokenProvider::class)->refresh();
    }
}
