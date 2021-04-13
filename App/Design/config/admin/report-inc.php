<?php

return [
    'view/report/user' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'گزارش‌گیری از کاربران'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin',
        ],
        'sub_title' => 'گزارش‌گیری از کاربران',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'گزارش‌گیری از کاربران',
                'is_active' => true,
            ],
        ],
    ],
];