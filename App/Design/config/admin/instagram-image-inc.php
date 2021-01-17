<?php

return [
    'view/instagram/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت تصاویر اینستاگرام'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'اینستاگرام',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تصاویر اینستاگرام',
                'is_active' => true,
            ],
        ],
    ],
];