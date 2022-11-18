<?php

return [
    'view/main/product/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'محصولات'),
        'common' => [
            'default',
            'default-jquery-ui',
            'default-changeable',
            'default-theia-sticky-sidebar',
        ],
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('home.search')->getRelativeUrl(),
                'text' => 'محصولات',
                'is_active' => false,
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
                    asset_path('js/product-search.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
    'view/main/product/detail' => [
        'common' => [
            'default',
            'default-changeable',
        ],
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('home.search')->getRelativeUrl(),
                'text' => 'محصولات',
                'is_active' => false,
            ],
        ],
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/product-detail.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
    'view/main/product/compare' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مقایسه'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'مقایسه',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('home.search')->getRelativeUrl(),
                'text' => 'محصولات',
                'is_active' => false,
            ],
            [
                'text' => 'مقایسه',
                'is_active' => true,
            ],
        ],
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/compare-search.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
];