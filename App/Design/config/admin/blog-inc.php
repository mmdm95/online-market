<?php

return [
    'view/blog/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن مطلب'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'افزودن مطلب',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.blog.view', '')->getRelativeUrl(),
                'text' => 'مطالب',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن مطلب',
                'is_active' => true,
            ],
        ],
    ],
    'view/blog/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش مطلب'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'ویرایش مطلب',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.blog.view', '')->getRelativeUrl(),
                'text' => 'مطالب',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش مطلب',
                'is_active' => true,
            ],
        ],
    ],
    'view/blog/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده مطالب'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مدیریت مطلب',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مطالب',
                'is_active' => true,
            ],
        ],
    ],
];