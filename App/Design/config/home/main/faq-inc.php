<?php

return [
    'view/main/faq' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سؤالات متداول'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'سؤالات متداول',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'سؤالات متداول',
                'is_active' => true,
            ],
        ],
    ],
];