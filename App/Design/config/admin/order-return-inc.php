<?php

return [
    'view/order/return-order/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سفارشات مرجوعی'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'مدیریت سفارشات مرجوعی',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'سفارشات مرجوعی',
                'is_active' => true,
            ],
        ],
    ],
    'view/order/return-order/detail' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سفارشات مرجوعی'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'مشاهده سفارشات مرجوعی',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.order.return')->getRelativeUrl(),
                'text' => 'مدیریت سفارشات مرجوعی',
                'is_active' => false,
            ],
            [
                'text' => 'سفارشات مرجوعی',
                'is_active' => true,
            ],
        ],
    ],
];