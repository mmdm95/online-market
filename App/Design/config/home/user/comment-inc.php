<?php

return [
    'view/main/user/comment/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'نظرات'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'نظرات',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('user.index')->getRelativeUrl(),
                'text' => 'داشبورد',
                'is_active' => false,
            ],
            [
                'text' => 'نظرات',
                'is_active' => true,
            ],
        ],
    ],
];