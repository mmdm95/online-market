<?php

return [
    'view/main/user/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'حساب کاربری'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'حساب کاربری',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'داشبورد',
                'is_active' => true,
            ],
        ],
    ],
    'view/main/user/info' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'اطلاعات حساب کاربری'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'اطلاعات حساب کاربری',
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
                'text' => 'اطلاعات حساب کاربری',
                'is_active' => true,
            ],
        ],
    ],
    'view/main/user/favorite' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'آیتم‌های مورد علاقه'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'آیتم‌های مورد علاقه',
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
                'text' => 'آیتم‌های مورد علاقه',
                'is_active' => true,
            ],
        ],
    ],
];