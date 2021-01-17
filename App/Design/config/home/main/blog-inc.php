<?php

return [
    'view/main/blog/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'بلاگ'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
        'sub_title' => 'بلاگ',
        'breadcrumb' => [
            [
                'url' => url('home.index'),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'بلاگ',
                'is_active' => true,
            ],
        ],
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('plugins/UriParser.min.js') .
                    '"></script>'
                ),
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/blog-search.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
    'view/main/blog/detail' => [
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('home.blog')->getRelativeUrl(),
                'text' => 'بلاگ',
                'is_active' => false,
            ],
        ],
    ],
];