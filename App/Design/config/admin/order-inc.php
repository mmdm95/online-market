<?php

return [
    'view/order/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'لیست سفارشات'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'سفارشات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'لیست سفارشات',
                'is_active' => true,
            ],
        ],
    ],
    'view/order/detail' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'جزئیات سفارش'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin'
        ],
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.order.view', '')->getRelativeUrl(),
                'text' => 'لیست سفارشات',
                'is_active' => false,
            ],
            [
                'text' => 'جزيیات سفارش',
                'is_active' => true,
            ],
        ],
    ],
    'view/order/badges' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'وضعیت سفارش'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin-color',
            'admin'
        ],
        'sub_title' => 'مدیریت وضعیت سفارش',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'وضعیت سفارش',
                'is_active' => true,
            ],
        ],
    ],
];