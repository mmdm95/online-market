<?php

return [
    'view/blog/category/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن دسته'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'افزودن دسته‌بندی مطلب‌',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.blog.view')->getRelativeUrl(),
                'text' => 'مطالب',
                'is_active' => false,
            ],
            [
                'url' => url('admin.blog.category.view')->getRelativeUrl(),
                'text' => 'دسته‌بندی‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن دسته',
                'is_active' => true,
            ],
        ],
    ],
    'view/blog/category/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش دسته'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش دسته‌بندی مطلب‌',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.blog.view')->getRelativeUrl(),
                'text' => 'مطالب',
                'is_active' => false,
            ],
            [
                'url' => url('admin.blog.category.view')->getRelativeUrl(),
                'text' => 'دسته‌بندی‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش دسته',
                'is_active' => true,
            ],
        ],
    ],
    'view/blog/category/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده دسته‌های مطالب'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'دسته‌بندی‌های مطالب',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.blog.view')->getRelativeUrl(),
                'text' => 'مطالب',
                'is_active' => false,
            ],
            [
                'text' => 'دسته‌بندی‌ها',
                'is_active' => true,
            ],
        ],
    ],
];