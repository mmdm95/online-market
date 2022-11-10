<?php

return [
    'view/report/user' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'گزارش‌گیری از کاربران'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-date',
            'admin-query-builder',
            'admin',
        ],
        'sub_title' => 'گزارش‌گیری از کاربران',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'گزارش‌گیری از کاربران',
                'is_active' => true,
            ],
        ],
    ],
    'view/report/product' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'گزارش‌گیری از محصولات'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-date',
            'admin-query-builder',
            'admin',
        ],
        'sub_title' => 'گزارش‌گیری از محصولات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'گزارش‌گیری از محصولات',
                'is_active' => true,
            ],
        ],
    ],
    'view/report/order' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'گزارش‌گیری از سفارشات'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-date',
            'admin-query-builder',
            'admin',
        ],
        'sub_title' => 'گزارش‌گیری از سفارشات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'گزارش‌گیری از سفارشات',
                'is_active' => true,
            ],
        ],
    ],
    'view/report/wallet' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'گزارش‌گیری از کیف پول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-date',
            'admin-query-builder',
            'admin',
        ],
        'sub_title' => 'گزارش‌گیری از کیف پول',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'گزارش‌گیری از کیف پول',
                'is_active' => true,
            ],
        ],
    ],
    'view/report/wallet-deposit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'گزارش‌گیری از تراکنش‌های کیف پول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-date',
            'admin-query-builder',
            'admin',
        ],
        'sub_title' => 'گزارش‌گیری از تراکنش‌های کیف پول',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'گزارش‌گیری از تراکنش‌های کیف پول',
                'is_active' => true,
            ],
        ],
    ],

    'partial/admin/report-templates/order-pdf' => [
        'css' => [
            'top' => [
                e(
                    '<link href="' .
                    asset_path('be/css/report.css') .
                    '" rel="stylesheet" type="text/css"> media="mpdf"'
                ),
            ],
        ],
    ],
];