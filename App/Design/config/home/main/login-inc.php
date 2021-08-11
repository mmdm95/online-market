<?php

return [
    'view/main/login' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ورود'),
        'common' => [
            'default',
            'default-changeable',
        ],
    ],
];