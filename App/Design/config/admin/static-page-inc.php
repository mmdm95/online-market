<?php

return [
    'view/static-page/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن صفحه ثابت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'افزودن صفحه ثابت',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.static.page.view', '')->getRelativeUrl(),
                'text' => 'صفحات ثابت',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن صفحه ثابت',
                'is_active' => true,
            ],
        ],
    ],
    'view/static-page/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش صفحه ثابت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'ویرایش صفحه ثابت',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.static.page.view', ''),
                'text' => 'صفحات ثابت',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش صفحه ثابت',
                'is_active' => true,
            ],
        ],
    ],
    'view/static-page/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده صفحات ثابت'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مدیریت صفحات ثابت',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'صفحات ثابت',
                'is_active' => true,
            ],
        ],
    ],
];