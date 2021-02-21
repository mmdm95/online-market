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
];