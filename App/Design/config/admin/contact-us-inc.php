<?php

return [
    'view/contact-us/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت تماس‌ها'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مدیریت تماس‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت تماس‌ها',
                'is_active' => true,
            ],
        ],
    ],
    'view/contact-us/message' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده تماس‌'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مشاهده تماس‌',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.contact-us.view', '')->getRelativeUrl(),
                'text' => 'مدیریت تماس‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'مشاهده تماس‌',
                'is_active' => true,
            ],
        ],
    ],
];