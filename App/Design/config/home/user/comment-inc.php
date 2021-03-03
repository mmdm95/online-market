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
    'view/main/user/comment/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش نظر'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'ویرایش نظر',
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
                'url' => url('user.comments')->getRelativeUrl(),
                'text' => 'نظرات',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش نظر',
                'is_active' => true,
            ],
        ],
    ],
];