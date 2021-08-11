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
];