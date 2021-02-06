<?php

return [
    'view/product/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن محصول جدید'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-date',
            'admin-tags-input',
            'admin-editor',
            'admin',
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
            'admin-date',
            'admin-tags-input',
            'admin-editor',
            'admin',
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
            'admin',
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
    'view/product/detail' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'جزئیات محصول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-lightbox',
            'admin-editor',
            'admin',
        ],
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
                'text' => 'مدیریت محصولات',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/buyer/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'اطلاعات محصول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin',
        ],
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
                'text' => 'اطلاعات محصول',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/stepped/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن قیمت پلکانی جدید'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-date',
            'admin-tags-input',
            'admin-editor',
            'admin',
        ],
    ],
    'view/product/stepped/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش قیمت پلکانی'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin-date',
            'admin-tags-input',
            'admin-editor',
            'admin',
        ],
    ],
    'view/product/stepped/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'محصولات موجود'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin',
        ],
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
                'text' => 'محصولات موجود برای قیمت پلکانی',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/stepped/view-all' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت قیمت‌های پلکانی'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin',
        ],
    ],
    'view/product/comment/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت نظرات محصول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin',
        ],
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
                'text' => 'مدیریت نظرات',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/comment/message' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'جزئیات نظر'),
        'common' => [
            'admin-base',
            'admin-editor',
            'admin',
        ],
    ],
];