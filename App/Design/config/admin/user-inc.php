<?php

return [
    'view/user/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'لیست کاربران سایت'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin',
        ],
        'sub_title' => 'مدیریت کاربران',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت کاربران',
                'is_active' => true,
            ],
        ],
    ],
    'view/user/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن کاربر'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'مدیریت کاربران',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.user.view', '')->getRelativeUrl(),
                'text' => 'مشاهده کاربران',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن کاربر',
                'is_active' => true,
            ]
        ],
    ],
    'view/user/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش کاربر'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'مدیریت کاربران',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.user.view', '')->getRelativeUrl(),
                'text' => 'مشاهده کاربران',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش کاربر',
                'is_active' => true,
            ]
        ],
    ],
    'view/user/user-profile' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'لیست کاربران سایت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مدیریت کاربران',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.user.view', '')->getRelativeUrl(),
                'text' => 'مشاهده کاربران',
                'is_active' => false,
            ],
            [
                'text' => 'مشاهده کاربر',
                'is_active' => true,
            ],
        ],
    ],
];