<?php

return [
    'view/send-method/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن روش ارسال'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'افزودن روش ارسال',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.send_method.view')->getRelativeUrl(),
                'text' => 'مدیریت روش‌های ارسال',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن روش ارسال',
                'is_active' => true,
            ],
        ],
    ],
    'view/send-method/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش روش ارسال'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش روش ارسال',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.send_method.view')->getRelativeUrl(),
                'text' => 'مدیریت روش‌های ارسال',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش روش ارسال',
                'is_active' => true,
            ],
        ],
    ],
    'view/send-method/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'نمایش روش‌های ارسال'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'روش‌های ارسال',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت روش‌های ارسال',
                'is_active' => true,
            ],
        ],
    ],
];