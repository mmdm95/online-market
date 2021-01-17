<?php

return [
    'view/main/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'صفحه اصلی'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
    ],
];