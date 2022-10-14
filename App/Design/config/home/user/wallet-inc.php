<?php

return [
    'view/main/user/wallet/index' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'کیف پول'),
        'common' => [
            'default',
            'default-theia-sticky-sidebar',
            'default-changeable',
        ],
        'sub_title' => 'کیف پول',
        'breadcrumb' => [
            [
                'url' => url('home.index')->getRelativeUrl(),
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('user.index')->getRelativeUrl(),
                'text' => 'داشبورد',
                'is_active' => false,
            ],
            [
                'text' => 'کیف پول',
                'is_active' => true,
            ],
        ],
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/wallet-charge-preparation.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
];