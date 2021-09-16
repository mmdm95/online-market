<?php

return [
    'orders' => [
        'successful' => [
            'users' => explode(',', $_ENV['NOTIFY_SUCCESSFUL_ORDER_USERNAME'] ?? '') ?: [],
        ],
    ],
];