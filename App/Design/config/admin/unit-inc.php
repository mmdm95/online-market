<?php

return [
    'view/unit/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'واحدها'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مشاهده واحد',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت واحدها',
                'is_active' => true,
            ],
        ],
    ],
];