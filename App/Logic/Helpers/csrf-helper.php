<?php

use App\Logic\Adapters\SessionTokenProvider;

function csrf_token_generate()
{
    // generate/regenerate csrf token if it is expired or not exists for first time
    $csrfCookie = cookie()->get(SessionTokenProvider::CSRF_KEY);
    /**
     * @var SessionTokenProvider $provider
     */
    $provider = container()->get(SessionTokenProvider::class);
    if (empty($csrfCookie)) {
        $provider->refresh();
    } else {
        $provider->getToken();
    }
}
