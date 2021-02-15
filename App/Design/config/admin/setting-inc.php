<?php

return [
    'view/setting/main' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات اصلی'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات اصلی',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات اصلی',
                'is_active' => true,
            ],
        ],
    ],
    'view/setting/sms' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات پیامک'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات پیامک',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات پیامک',
                'is_active' => true,
            ],
        ],
    ],
    'view/setting/contact' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات تماس'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات تماس',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات تماس',
                'is_active' => true,
            ],
        ],
    ],
    'view/setting/social' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات شبکه‌های اجتماعی'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات شبکه‌های اجتماعی',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات شبکه‌های اجتماعی',
                'is_active' => true,
            ],
        ],
    ],
    'view/setting/footer' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات فوتر'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات فوتر/پاورقی',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات فوتر/پاورقی',
                'is_active' => true,
            ],
        ],
    ],
    'view/setting/index-page' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات صفحه اصلی'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات صفحه اصلی',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات صفحه اصلی',
                'is_active' => true,
            ],
        ],
    ],
    'view/setting/about-page' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات صفحه درباره'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'تنظیمات صفحه درباره',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'تنظیمات صفحه درباره',
                'is_active' => true,
            ],
        ],
    ],
    'view/setting/other' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'سایر تنظیمات'),
        'common' => [
            'admin-base',
            'admin',
        ],
        'sub_title' => 'سایر تنظیمات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'سایر تنظیمات',
                'is_active' => true,
            ],
        ],
    ],
];