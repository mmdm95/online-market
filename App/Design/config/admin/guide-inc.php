<?php

return [
    'view/guide/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'راهنما'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'راهنما',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'راهنما',
                'is_active' => true,
            ],
        ],
    ],
];