<?php

return [
    'view/main/complaint' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ثبت شکایات'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'ثبت شکایات',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'ثبت شکایات',
                'is_active' => true,
            ],
        ],
    ],
];