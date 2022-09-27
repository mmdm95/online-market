<?php

return [
    'view/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'داشبورد'),
        'common' => [
            'admin-base',
            'admin',
            'admin-e-chart',
        ],
    ],
];