<?php

return [
    'view/security-question/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سؤالات امنیتی'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-tags-input',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مشاهده سؤالات امنیتی',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت سؤالات امنیتی',
                'is_active' => true,
            ],
        ],
    ],
];