<?php

return [
    'view/category/image/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده تصاویر دسته‌ها'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'مدیریت تصاویر دسته‌بندی‌ها',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.category.view')->getRelativeUrl(),
                'text' => 'مدیریت دسته‌بندی‌ها',
                'is_active' => false,
            ],
            [
                'text' => 'تصاویر دسته‌ها',
                'is_active' => true,
            ],
        ],
    ],
];