<?php

return [
    'view/payment-method/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن روش پرداخت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'افزودن روش پرداخت',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.pay_method.view')->getRelativeUrl(),
                'text' => 'مدیریت روش‌های پرداخت',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن روش پرداخت',
                'is_active' => true,
            ],
        ],
    ],
    'view/payment-method/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش روش پرداخت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش روش پرداخت',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.pay_method.view')->getRelativeUrl(),
                'text' => 'مدیریت روش‌های پرداخت',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش روش پرداخت',
                'is_active' => true,
            ],
        ],
    ],
    'view/payment-method/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'نمایش روش‌های پرداخت'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'روش‌های پرداخت',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت روش‌های پرداخت',
                'is_active' => true,
            ],
        ],
    ],
];