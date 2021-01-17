<?php

return [
    'view/category/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن دسته'),
        'common' => [
            'admin-base',
            'admin-tags-input',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'مدیریت دسته‌بندی‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.category.view')->getRelativeUrl(),
                'text' => 'دسته‌بندی‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن دسته',
                'is_active' => true,
            ],
        ],
    ],
    'view/category/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش دسته'),
        'common' => [
            'admin-base',
            'admin-tags-input',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش دسته‌بندی‌',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.category.view')->getRelativeUrl(),
                'text' => 'مدیریت دسته‌بندی‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش دسته',
                'is_active' => true,
            ],
        ],
    ],
    'view/category/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده دسته‌ها'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مدیریت دسته‌بندی‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'دسته‌بندی‌ها',
                'is_active' => true,
            ],
        ],
    ],
];