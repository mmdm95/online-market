<?php

return [
    'view/main/order/shop-cart' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سبد خرید'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
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
    ],
];