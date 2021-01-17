<?php

return [
    'view/festival/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن جشنواره'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'افزودن جشنواره',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.festival.add')->getRelativeUrl(),
                'text' => 'مدیریت جشنواره',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن جشنواره',
                'is_active' => true,
            ],
        ],
    ],
    'view/festival/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش جشنواره'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش جشنواره',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.festival.view')->getRelativeUrl(),
                'text' => 'مدیریت جشنواره',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن جشنواره',
                'is_active' => true,
            ],
        ],
    ],
    'view/festival/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت جشنواره‌ها'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'جشنواره‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.festival.view')->getRelativeUrl(),
                'text' => 'مدیریت جشنواره‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن جشنواره',
                'is_active' => true,
            ],
        ],
    ],
];