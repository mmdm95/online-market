<?php

return [
    'view/main/contact' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تماس با ما'),
        'common' => [
            'default',
            'default-changeable',
            'default-google-map',
            'default-cart',
        ],
        'sub_title' => 'تماس با ما',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تماس با ما',
                'is_active' => true,
            ],
        ],
    ],
];