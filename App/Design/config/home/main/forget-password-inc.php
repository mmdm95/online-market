<?php

return [
    'view/main/forget-password/step1' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور', 'وارد کردن شماره موبایل'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'فراموشی کلمه عبور',
        'stepy' => [
            [
                'text' => 'وارد کردن شماره موبایل',
                'icon' => 'linearicons-phone',
                'is_active' => true,
            ],
            [
                'text' => 'وارد کردن کد ارسال شده',
            ],
            [
                'text' => 'تغییر کلمه عبور',
            ],
            [
                'text' => 'اتمام عملیات',
            ],
        ],
    ],
    'view/main/forget-password/step2' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور', 'کد ارسال شده'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'فراموشی کلمه عبور',
        'stepy' => [
            [
                'text' => 'وارد کردن شماره موبایل',
                'icon' => 'linearicons-check',
                'is_done' => true,
            ],
            [
                'text' => 'وارد کردن کد ارسال شده',
                'icon' => 'linearicons-barcode',
                'is_active' => true,
            ],
            [
                'text' => 'تغییر کلمه عبور',
            ],
            [
                'text' => 'اتمام عملیات',
            ],
        ],
    ],
    'view/main/forget-password/step3' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور', 'تغییر کلمه عبور'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'فراموشی کلمه عبور',
        'stepy' => [
            [
                'text' => 'وارد کردن شماره موبایل',
                'icon' => 'linearicons-check',
                'is_done' => true,
            ],
            [
                'text' => 'وارد کردن کد ارسال شده',
                'icon' => 'linearicons-check',
                'is_done' => true,
            ],
            [
                'text' => 'تغییر کلمه عبور',
                'icon' => 'linearicons-keyboard',
                'is_active' => true,
            ],
            [
                'text' => 'اتمام عملیات',
            ],
        ],
    ],
    'view/main/forget-password/step4' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور', 'اتمام'),
        'common' => [
            'default',
            'default-changeable',
        ],
        'sub_title' => 'فراموشی کلمه عبور',
        'stepy' => [
            [
                'text' => 'وارد کردن شماره موبایل',
                'icon' => 'linearicons-check',
                'is_done' => true,
            ],
            [
                'text' => 'وارد کردن کد ارسال شده',
                'icon' => 'linearicons-check',
                'is_done' => true,
            ],
            [
                'text' => 'تغییر کلمه عبور',
                'icon' => 'linearicons-check',
                'is_done' => true,
            ],
            [
                'text' => 'اتمام عملیات',
                'icon' => 'linearicons-check',
                'is_done' => true,
            ],
        ],
    ],
];