<?php

return [
    /**
     * Time to live of csrf token to validate
     * after this time, csrf is not valid anymore
     *
     * Note: time must be in seconds
     *
     * Default is 300s or 5min
     */
    'expiration' => 18000, // 5 hours
];