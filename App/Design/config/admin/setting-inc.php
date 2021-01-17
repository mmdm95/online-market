<?php

return [
    'view/setting/main' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات',
                'is_active' => true,
            ],
        ],
    ],
];