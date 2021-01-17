<?php

return [
    'view/admin-login' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ورود'),
        'common' => [
            'admin-base',
            'admin',
        ],
    ],
];