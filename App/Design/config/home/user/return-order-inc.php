<?php

return [
    'view/main/user/return-order/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مرجوع سفارش'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
        ],
        'sub_title' => 'مرجوع سفارش',
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
                'text' => 'مرجوع سفارش',
                'is_active' => true,
            ],
        ],
    ],
    'view/main/user/return-order/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'آیتم‌های مرجوع سفارش'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
        ],
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
                'url' => url('user.return-order')->getRelativeUrl(),
                'text' => 'مرجوع سفارش',
                'is_active' => false,
            ],
            [
                'text' => 'آیتم‌های مرجوع سفارش',
                'is_active' => true,
            ],
        ],
    ],
    'view/main/user/return-order/detail' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'جزئیات سفارش مرجوع شده'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
        ],
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
                'url' => url('user.return-order')->getRelativeUrl(),
                'text' => 'مرجوع سفارش',
                'is_active' => false,
            ],
            [
                'text' => 'جزئیات سفارش مرجوع شده',
                'is_active' => true,
            ],
        ],
    ],
];
