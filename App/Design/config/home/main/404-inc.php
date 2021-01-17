<?php

return [
    'view/main/404' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'صفحه مورد نظر پیدا نشد'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
    ],
];