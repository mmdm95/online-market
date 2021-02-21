<?php

return [
    'view/main/user/address/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'آدرس‌های من'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'آدرس‌های من',
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
                'text' => 'آدرس‌های من',
                'is_active' => true,
            ],
        ],
    ],
];