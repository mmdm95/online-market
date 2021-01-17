<?php

return [
    'view/complaints/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'شکایت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'بررسی شکایات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت شکایات',
                'is_active' => true,
            ],
        ],
    ],
    'view/complaints/message' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده شکایت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'بررسی شکایات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.complaints.view')->getRelativeUrl(),
                'text' => 'مشاهده شکایات',
                'is_active' => false,
            ],
            [
                'text' => 'بررسی شکایت',
                'is_active' => true,
            ],
        ],
    ],
];