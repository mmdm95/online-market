<?php

return [
    'view/file-manager/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت فایل‌ها'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-fab',
            'admin'
        ],
        'sub_title' => 'مدیریت فایل‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت فایل‌ها',
                'is_active' => true,
            ],
        ],
    ],
];