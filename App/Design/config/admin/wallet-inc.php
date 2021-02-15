<?php

return [
    'view/wallet/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'کیف پول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin',
        ],
        'sub_title' => 'کیف پول کاربران',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'خبرنامه',
                'is_active' => true,
            ],
        ],
    ],
    'view/wallet/user-wallet' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'جزئیات کیف پول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin',
        ],
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.wallet.view')->getRelativeUrl(),
                'text' => 'کیف پول کاربران',
                'is_active' => false,
            ],
            [
                'text' => 'کیف پول کاربر',
                'is_active' => true,
            ],
        ],
    ],
    'view/wallet/charge' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'شارژ کیف پول'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin',
        ],
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.wallet.view')->getRelativeUrl(),
                'text' => 'کیف پول کاربران',
                'is_active' => false,
            ],
            [
                'text' => 'شارژ کیف پول',
                'is_active' => true,
            ],
        ],
    ],
    'view/wallet/deposit-type' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'انواع تراکنش'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin',
        ],
        'sub_title' => 'انواع تراکنش‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'انواع تراکنش',
                'is_active' => true,
            ],
        ],
    ],
];