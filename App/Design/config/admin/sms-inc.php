<?php

return [
    'view/sms/logs' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'لاگ‌های پیامک'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'لاگ‌های پیامک',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'لاگ‌های پیامک',
                'is_active' => true,
            ],
        ],
    ],
];