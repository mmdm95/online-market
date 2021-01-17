<?php

return [
    'view/main/signup/step1' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ثبت نام'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
    ],
    'view/main/signup/step2' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ثبت نام', 'وارد کردن کد ارسال شده'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
    ],
    'view/main/signup/step3' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ثبت نام', 'وارد کردن کلمه عبور'),
        'common' => [
            'default',
            'default-changeable',
            'default-cart',
        ],
    ],
];