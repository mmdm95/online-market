<?php

return [
    'view/coupon/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن کوپن تخفیف'),
        'common' => [
            'admin-base',
            'admin-date',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'افزودن کوپن تخفیف',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.coupon.view')->getRelativeUrl(),
                'text' => 'مشاهده کوپن‌های تخفیف',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن کوپن‌ تخفیف',
                'is_active' => true,
            ],
        ],
    ],
    'view/coupon/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش کوپن تخفیف'),
        'common' => [
            'admin-base',
            'admin-date',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش کوپن تخفیف',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.coupon.view')->getRelativeUrl(),
                'text' => 'مشاهده کوپن‌های تخفیف',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش کوپن‌ تخفیف',
                'is_active' => true,
            ],
        ],
    ],
    'view/coupon/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده کوپن‌های تخفیف'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مشاهده کوپن تخفیف',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مشاهده کوپن‌های تخفیف',
                'is_active' => true,
            ],
        ],
    ],
];