<?php

return [
    'view/main/colleague/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'داشبورد'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
    ],
];