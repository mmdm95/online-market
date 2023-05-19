<?php

return [
    'view/main/order/shop-cart' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سبد خرید'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'سبد خرید',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'سبد خرید',
                'is_active' => true,
            ],
        ],
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/order-preparation.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
    'view/main/order/checkout' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'بررسی'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
        ],
        'sub_title' => 'بررسی',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'بررسی',
                'is_active' => true,
            ],
        ],
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/order-preparation.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
    'view/main/order/order-completed' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سفارش به پایان رسید'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'سفارش به پایان رسید',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'اتمام سفارش',
                'is_active' => true,
            ],
        ],
    ],
    'view/main/user/wallet/charge' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'نتیجه شارژ کیف پول'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'نتیجه شارژ کیف پول',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'نتیجه شارژ کیف پول',
                'is_active' => true,
            ],
        ],
    ],
];