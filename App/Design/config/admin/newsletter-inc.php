<?php

return [
    'view/newsletter/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'خبرنامه'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مشاهده خبرنامه',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'خبرنامه',
                'is_active' => true,
            ],
        ],
    ],
];