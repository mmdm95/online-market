<?php

return [
    'view/slider/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت اسلایدشو'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'اسلایدها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'اسلایدها',
                'is_active' => true,
            ],
        ],
    ],
];