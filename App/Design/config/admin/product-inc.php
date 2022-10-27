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
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('be/js/product-properties.js') .
                    '"></script>'
                ),
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
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('be/js/product-properties.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
    'view/product/batch-edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش دسته جمعی'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin',
        ],
        'sub_title' => 'ویرایش دسته‌جمعی',
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
                'text' => 'ویرایش دسته‌جمعی',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/batch-edit-price' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش دسته جمعی قیمت'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin',
        ],
        'sub_title' => 'ویرایش دسته‌جمعی قیمت',
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
                'text' => 'ویرایش دسته‌جمعی قیمت',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت محصولات'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin-fab',
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
            'admin-no-ui-slider',
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

    //--------------------------------------------------------------------
    // Products Search Attributes
    //--------------------------------------------------------------------

    'view/product/attribute/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن ویژگی جستجو'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'افزودن ویژگی جستجو',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.product.attr.view')->getRelativeUrl(),
                'text' => 'مدیریت ویژگی‌های جستجو',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن ویژگی جستجو',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/attribute/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش ویژگی جستجو'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش ویژگی جستجو',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.product.attr.view')->getRelativeUrl(),
                'text' => 'مدیریت ویژگی‌های جستجو',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش ویژگی جستجو',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/attribute/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت ویژگی‌های جستجو'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'ویژگی‌های جستجو',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت ویژگی‌های جستجو',
                'is_active' => true,
            ],
        ],
    ],

    //--------------------------------------------------------------------
    // Products Search Attributes Categories
    //--------------------------------------------------------------------

    'view/product/attribute/category/add' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'افزودن دسته‌بندی ویژگی جستجو'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'افزودن دسته‌بندی ویژگی جستجو',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.product.attr.category.view')->getRelativeUrl(),
                'text' => 'مدیریت دسته‌بندی ویژگی‌های جستجو',
                'is_active' => false,
            ],
            [
                'text' => 'افزودن دسته‌بندی ویژگی جستجو',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/attribute/category/edit' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش دسته‌بندی ویژگی جستجو'),
        'common' => [
            'admin-base',
            'admin-form',
            'admin'
        ],
        'sub_title' => 'ویرایش دسته‌بندی ویژگی جستجو',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'url' => url('admin.product.attr.category.view')->getRelativeUrl(),
                'text' => 'مدیریت دسته‌بندی ویژگی‌های جستجو',
                'is_active' => false,
            ],
            [
                'text' => 'ویرایش دسته‌بندی ویژگی جستجو',
                'is_active' => true,
            ],
        ],
    ],
    'view/product/attribute/category/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت دسته‌بندی ویژگی‌های جستجو'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'sub_title' => 'دسته‌بندی ویژگی‌های جستجو',
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت دسته‌بندی ویژگی‌های جستجو',
                'is_active' => true,
            ],
        ],
    ],

    //--------------------------------------------------------------------
    // Products Search Attributes Values
    //--------------------------------------------------------------------

    'view/product/attribute/value/view' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت مقادیر ویژگی‌های جستجو'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
        ],
        'breadcrumb' => [
            [
                'url' => url('admin.index')->getRelativeUrl(),
                'icon' => 'icon-home2',
                'text' => 'خانه',
                'is_active' => false,
            ],
            [
                'text' => 'مدیریت مقادیر ویژگی‌های جستجو',
                'is_active' => true,
            ],
        ],
    ],

    'view/product/attribute/value/product-value' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت مقادیر ویژگی‌های جستجوی محصول'),
        'common' => [
            'admin-base',
            'admin-table',
            'admin'
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
                'text' => 'مدیریت مقادیر ویژگی‌های جستجوی محصول',
                'is_active' => true,
            ],
        ],
    ],
];