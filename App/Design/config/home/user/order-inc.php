<?php

return [
    'view/main/user/order/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سفارشات'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'سفارشات',
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
                'text' => 'سفارشات',
                'is_active' => true,
            ],
        ],
    ],
    'view/main/user/order/detail' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'جزئیات سفارش'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
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
                'url' => url('user.orders')->getRelativeUrl(),
                'text' => 'سفارشات',
                'is_active' => false,
            ],
            [
                'text' => 'جزئیات سفارش',
                'is_active' => true,
            ],
        ],
    ],
];