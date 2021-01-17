<?php

return [
    'view/color/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن رنگ'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-color',
            'admin'
        ],
        'sub_title' => 'افزودن رنگ',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.color.view')->getRelativeUrl(),
                'text' => 'مدیریت رنگ‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن رنگ جدید',
                'is_active' => true,
            ],
        ],
    ],
    'view/color/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش رنگ'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-color',
            'admin'
        ],
        'sub_title' => 'ویرایش رنگ',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.color.view')->getRelativeUrl(),
                'text' => 'مدیریت رنگ‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش رنگ',
                'is_active' => true,
            ],
        ],
    ],
    'view/color/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'نمایش رنگ'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'رنگ‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت رنگ‌ها',
                'is_active' => true,
            ],
        ],
    ],
];