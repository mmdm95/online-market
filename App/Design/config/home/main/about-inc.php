<?php

return [
    'view/main/about' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'درباره ما'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'درباره ما',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'درباره ما',
                'is_active' => true,
            ],
        ],
    ],
];