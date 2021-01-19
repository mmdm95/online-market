<?php

return [
    'view/product/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن محصول جدید'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'افزودن محصول',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.product.view', '')->getRelativeUrl(),
                'text' => 'مدیریت محصولات',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن محصول جدید',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش محصول'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-editor',
            'admin'
        ],
        'sub_title' => 'ویرایش محصول',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.product.view', '')->getRelativeUrl(),
                'text' => 'مدیریت محصولات',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش محصول',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت محصولات'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'محصولات',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت محصولات',
                'is_active' => true,
            ],
        ],
    ],
];