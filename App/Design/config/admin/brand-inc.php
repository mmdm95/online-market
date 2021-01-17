<?php

return [
    'view/brand/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن برند'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'افزودن برند',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.brand.view')->getRelativeUrl(),
                'text' => 'مدیریت برندها',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن برند',
                'is_active' => true,
            ],
        ],
    ],
    'view/brand/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش برند'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'ویرایش برند',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.brand.view')->getRelativeUrl(),
                'text' => 'مدیریت برندها',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش برند',
                'is_active' => true,
            ],
        ],
    ],
    'view/brand/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت برندها'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'برندها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت برندها',
                'is_active' => true,
            ],
        ],
    ],
];