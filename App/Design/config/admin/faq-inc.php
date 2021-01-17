<?php

return [
    'view/faq/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سؤالات متداول'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مشاهده سؤالات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت سؤالات متداول',
                'is_active' => true,
            ],
        ],
    ],
];