<?php

return [
    /**
     * Please Read This Before Use:
     * - We can have three types of platform here:
     *     1. Desktop
     *     2. Tablet
     *     3. Mobile
     * - In each platform we have the following structure
     *     1. The [common] key
     *       - In this section we put all files that are common to all pages
     * - Structure of all keys are like:
     *   EXP:
     *       [
     *           'js' => [
     *               'top' => [
     *                   ...
     *               ],
     *               'bottom' => [
     *                   ...
     *               ],
     *               'title' => 'The Title',
     *               'etc.' => ...
     *           ],
     *           'css' => [
     *               ...
     *           ],
     *           'etc.' => ...
     *       ]
     *
     * NOTE:
     *   [common] has a key that use inside your specific page
     *   as [common] key, OK that's confusing look at example below:
     *
     *   [
     *     'common' => [
     *        'default' => [
     *           'js' => [
     *                     'top' => [
     *                     ],
     *                     'bottom' => [
     *                     ],
     *                   ]
     *           'css' => [
     *                     'top' => [
     *                     ],
     *                     'bottom' => [
     *                     ],
     *                   ],
     *         ]
     *     ],
     *     'example-page' => [
     *       'common' => 'default' or array of common like ['default', 'admin'],
     *       ...
     *     ]
     *   ]
     *
     * See, you can have multiple common for each part of your application.
     * Isn't it cool :)
     *
     * NOTE:
     *   1. [common] key just have [js] and [css] keys
     *   2. You must use slash (/) to separate folder and files from each other.
     *   3. key of other items must be name of the file after [design] path
     *     [EXP:
     *       1. Assume that we have a file named [index] in [app/design/view],
     *          so the key name will be [view/index].
     *       2. Now Assume that we have another file named [index] in [app/design/partial/a-folder],
     *          so the key name will be [partial/a-folder/index].
     *   4. BE CAREFUL! If note number 3 is not observe, then the config will not be available.
     *   5. The config is according to the bigger platform (priority considered here)
     *     [EXP:
     *       If you don't specified mobile config, it'll get config according to upper device,
     *       that is tablet, and so on.]
     *   6. It detect which device is trying to get config, automatically for you
     *   7. BE CAREFUL! Please enter unique js and css file paths,
     *      but it try to detect redundant js and css files according to src and href values
     *   8. Add js and css file as following manner
     *     Exp.
     *      htmlspecialchars('<script type="js type like text/javascript" src="the src"></script>'),
     *      e('<script type="js type like text/javascript" src="the src"></script>'),
     */
    'desktop' => [
        'common' => [
            'default' => [
                'js' => [
                    'top' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/main.js') .
                            '"></script>'
                        ),
                    ],
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/scripts.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('plugins/noty/noty.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/globals.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/index.js') .
                            '"></script>'
                        ),
                    ],
                ],
                'css' => [
                    'top' => [
                        e(
                            '<link href="' .
                            'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap' .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            'https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap' .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('css/main.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('plugins/noty/noty.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('plugins/noty/themes/sunset.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('css/style.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('css/responsive.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('css/rtl-style.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                    ],
                ],
            ],
            'default-cart' => [
                'js' => [
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/cart.js') .
                            '"></script>'
                        ),
                    ],
                ],
            ],
            'default-map' => [
                'js' => [
                    'top' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('plugins/leaflet/leaflet.js') .
                            '"></script>'
                        ),
                    ],
                ],
                'css' => [
                    'top' => [
                        e(
                            '<link href="' .
                            asset_path('plugins/leaflet/leaflet.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                    ],
                ],
            ],
            'default-google-map' => [
                'js' => [
                    'top' => [
                        e(
                            '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7TypZFTl4Z3gVtikNOdGSfNTpnmq-ahQ&amp;"></script>'
                        ),
                    ],
                ],
            ],
            'admin-base' => [
                'js' => [
                    'top' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/main/jquery.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/main/bootstrap.bundle.min.js') .
                            '"></script>'
                        ),
                    ],
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/loaders/blockui.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/ui/ripple.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/notifications/noty.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/forms/validation/validate.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/forms/styling/uniform.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/forms/selects/select2.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/es6-promise.auto.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/axios.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('js/globals.js') .
                            '"></script>'
                        ),
                    ],
                ],
                'css' => [
                    'top' => [
                        e(
                            '<link href="' .
                            'https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('be/css/icons/icomoon/styles.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('be/css/bootstrap.min.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('be/css/bootstrap_limitless.min.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('be/css/layout.min.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('be/css/components.min.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('be/css/colors.min.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                        e(
                            '<link href="' .
                            asset_path('css/font.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                    ],
                ],
            ],
            'admin-form' => [
                'js' => [
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/media/fancybox.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/forms/styling/switchery.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/forms/styling/switch.min.js') .
                            '"></script>'
                        ),
                    ],
                ]
            ],
            'admin-table' => [
                'js' => [
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/tables/datatables/datatables.min.js') .
                            '"></script>'
                        ),
                    ]
                ],
            ],
            'admin-color' => [
                'js' => [
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/pickers/color/spectrum.js') .
                            '"></script>'
                        ),
                    ]
                ]
            ],
            'admin-fab' => [
                'js' => [
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/ui/fab.min.js') .
                            '"></script>'
                        ),
                    ],
                ],
            ],
            'admin' => [
                'js' => [
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/loaders/lazy.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/app.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/custom.js') .
                            '"></script>'
                        ),
                    ],
                ],
                'css' => [
                    'top' => [
                        e(
                            '<link href="' .
                            asset_path('be/css/custom.css') .
                            '" rel="stylesheet" type="text/css">'
                        ),
                    ]
                ]
            ],
        ],

        /******************************
         ************ Home ************
         *****************************/
        'view/main/404' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'صفحه مورد نظر پیدا نشد'),
            'common' => [
                'default',
                'default-cart',
            ],
        ],
        'view/main/index' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'صفحه اصلی'),
            'common' => [
                'default',
                'default-cart',
            ],
        ],
        'view/main/login' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ورود'),
            'common' => [
                'default',
                'default-cart',
            ],
        ],
        'view/main/signup' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ثبت نام'),
            'common' => [
                'default',
                'default-cart',
            ],
        ],
        'view/main/activation/step1' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'فعالسازی حساب' , 'وارد کردن شماره موبایل'),
            'common' => [
                'default',
                'default-cart',
            ],
            'sub_title' => 'فعالسازی حساب',
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
                    'text' => 'اتمام عملیات',
                ],
            ],
        ],
        'view/main/activation/step2' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'فعالسازی حساب' , 'وارد کردن کد ارسال شده'),
            'common' => [
                'default',
                'default-cart',
            ],
            'sub_title' => 'فعالسازی حساب',
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
                    'text' => 'اتمام عملیات',
                ],
            ],
        ],
        'view/main/activation/step3' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'فعالسازی حساب' , 'اتمام'),
            'common' => [
                'default',
                'default-cart',
            ],
            'sub_title' => 'فعالسازی حساب',
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
                    'text' => 'اتمام عملیات',
                    'icon' => 'linearicons-check',
                    'is_done' => true,
                ],
            ],
        ],
        'view/main/forget-password/step1' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور' , 'وارد کردن شماره موبایل'),
            'common' => [
                'default',
                'default-cart',
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
            'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور' , 'کد ارسال شده'),
            'common' => [
                'default',
                'default-cart',
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
            'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور' , 'تغییر کلمه عبور'),
            'common' => [
                'default',
                'default-cart',
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
            'title' => title_concat(\config()->get('settings.title.value'), 'فراموشی کلمه عبور' , 'اتمام'),
            'common' => [
                'default',
                'default-cart',
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
        'view/main/static-page' => [
            'common' => [
                'default',
                'default-cart',
            ],
        ],
        'view/main/about' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'درباره ما'),
            'common' => [
                'default',
                'default-cart',
            ],
            'sub_title' => 'درباره ما',
            'breadcrumb' => [
                [
                    'url' => url('home.index'),
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'درباره ما',
                    'is_active' => true,
                ],
            ],
        ],
        'view/main/faq' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'سؤالات متداول'),
            'common' => [
                'default',
                'default-cart',
            ],
            'sub_title' => 'سؤالات متداول',
            'breadcrumb' => [
                [
                    'url' => url('home.index'),
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'سؤالات متداول',
                    'is_active' => true,
                ],
            ],
        ],
        'view/main/contact' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'تماس با ما'),
            'common' => [
                'default',
                'default-google-map',
                'default-cart',
            ],
            'sub_title' => 'تماس با ما',
            'breadcrumb' => [
                [
                    'url' => url('home.index'),
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'تماس با ما',
                    'is_active' => true,
                ],
            ],
        ],

        /******************************
         *********** Admin ************
         *****************************/
        'view/admin-login' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'صفحه ورود'),
            'common' => [
                'admin-base',
                'admin'
            ],
        ],
        'view/user/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'لیست کاربران سایت'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مدیریت کاربران',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت کاربران',
                    'is_active' => true,
                ],
            ],
        ],
        'view/user/add' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'افزودن کاربر'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مدیریت کاربران',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.user.view'),
                    'text' => 'کاربران',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن کاربر',
                    'is_active' => true,
                ]
            ],
        ],
        'view/user/edit' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش کاربر'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مدیریت کاربران',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.user.view'),
                    'text' => 'کاربران',
                    'is_active' => false,
                ],
                [
                    'text' => 'ویرایش کاربر',
                    'is_active' => true,
                ]
            ],
        ],
        'view/user/user-profile' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'لیست کاربران سایت'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مدیریت کاربران',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.user.view'),
                    'text' => 'مدیریت کاربران',
                    'is_active' => false,
                ],
                [
                    'text' => 'مشاهده کاربر',
                    'is_active' => true,
                ],
            ],
        ],
        'view/category/add' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'افزودن دسته'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مدیریت دسته‌بندی‌ها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.category.add'),
                    'text' => 'دسته‌بندی‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن دسته',
                    'is_active' => true,
                ],
            ],
        ],
        'view/category/edit' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش دسته'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'ویرایش دسته‌بندی‌',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.category.add'),
                    'text' => 'مدیریت دسته‌بندی‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'ویرایش دسته',
                    'is_active' => true,
                ],
            ],
        ],
        'view/category/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده دسته‌ها'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مدیریت دسته‌بندی‌ها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'دسته‌بندی‌ها',
                    'is_active' => true,
                ],
            ],
        ],
        'view/coupon/add' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'افزودن کوپن تخفیف'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'افزودن کوپن تخفیف',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.coupon.view'),
                    'text' => 'مشاهده کوپن‌های تخفیف',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن کوپن‌ تخفیف',
                    'is_active' => true,
                ],
            ],
        ],
        'view/coupon/edit' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش کوپن تخفیف'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'ویرایش کوپن تخفیف',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.coupon.view'),
                    'text' => 'مشاهده کوپن‌های تخفیف',
                    'is_active' => false,
                ],
                [
                    'text' => 'ویرایش کوپن‌ تخفیف',
                    'is_active' => true,
                ],
            ],
        ],
        'view/coupon/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده کوپن‌های تخفیف'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مشاهده کوپن تخفیف',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مشاهده کوپن‌های تخفیف',
                    'is_active' => true,
                ],
            ],
        ],
        'view/color/add' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'افزودن رنگ'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin-color',
                'admin'
            ],
            'sub_title' => 'افزودن رنگ',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.color.view'),
                    'text' => 'مدیریت رنگ‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن رنگ جدید',
                    'is_active' => true,
                ],
            ],
        ],
        'view/color/edit' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش رنگ'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-color',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'ویرایش رنگ',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.color.view'),
                    'text' => 'مدیریت رنگ‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'ویرایش رنگ',
                    'is_active' => true,
                ],
            ],
        ],
        'view/color/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'نمایش رنگ'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'ویرایش رنگ',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت رنگ‌ها',
                    'is_active' => true,
                ],
            ],
        ],
        'view/festival/add' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'افزودن جشنواره'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'افزودن جشنواره',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.festival.add'),
                    'text' => 'مدیریت جشنواره',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن جشنواره',
                    'is_active' => true,
                ],
            ],
        ],
        'view/festival/edit' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش جشنواره'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'ویرایش جشنواره',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.festival.view'),
                    'text' => 'مدیریت جشنواره',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن جشنواره',
                    'is_active' => true,
                ],
            ],
        ],
        'view/festival/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت جشنواره'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'ویرایش جشنواره',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.festival.view'),
                    'text' => 'مدیریت جشنواره',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن جشنواره',
                    'is_active' => true,
                ],
            ],
        ],

        'view/blog/category/add' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'افزودن دسته'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مدیریت دسته‌بندی‌ها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.blog.view'),
                    'text' => 'وبلاگ',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.blog.category.add'),
                    'text' => 'دسته‌بندی‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن دسته',
                    'is_active' => true,
                ],
            ],
        ],
        'view/blog/category/edit' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'ویرایش دسته'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'ویرایش دسته‌بندی‌',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.blog.view'),
                    'text' => 'وبلاگ',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.category.add'),
                    'text' => 'مدیریت دسته‌بندی‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'ویرایش دسته',
                    'is_active' => true,
                ],
            ],
        ],
        'view/blog/category/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده دسته‌ها'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مدیریت دسته‌بندی‌ها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.blog.view'),
                    'text' => 'وبلاگ',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.blog.category.view'),
                    'text' => 'دسته‌بندی‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'دسته‌بندی‌ها',
                    'is_active' => true,
                ],
            ],
        ],
        'view/contact-us/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت تماس‌ها'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مشاهده تماس‌ها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت تماس‌ها',
                    'is_active' => true,
                ],
            ],
        ],
        'view/contact-us/message' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده تماس‌'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مشاهده تماس‌',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.contact-us.view'),
                    'text' => 'مدیریت تماس‌ها',
                    'is_active' => false,
                ],
                [
                    'text' => 'مشاهده تماس‌',
                    'is_active' => true,
                ],
            ],
        ],
        'view/unit/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'واحدها'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مشاهده واحد',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت واحدها',
                    'is_active' => true,
                ],
            ],
        ],
        'view/faq/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'سؤالات متداول'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مشاهده سؤالات',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت سؤالات متداول',
                    'is_active' => true,
                ],
            ],
        ],
        'view/complaints/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'شکایت'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'بررسی شکایات',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت شکایات',
                    'is_active' => true,
                ],
            ],
        ],
        'view/complaints/message' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مشاهده شکایت'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'بررسی شکایات',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.complaints.view'),
                    'text' => 'مشاهده شکایات',
                    'is_active' => false,
                ],
                [
                    'text' => 'بررسی شکایت',
                    'is_active' => true,
                ],
            ],
        ],
        'view/newsletter/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'خبرنامه'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مشاهده خبرنامه',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'خبرنامه',
                    'is_active' => true,
                ],
            ],
        ],
        'view/wallet/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'کیف پول'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin'
            ],
            'sub_title' => 'مشاهده خبرنامه',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'خبرنامه',
                    'is_active' => true,
                ],
            ],
        ],
        'view/wallet/user-wallet' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'کیف پول'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مشاهده کیف پول',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.wallet.view'),
                    'text' => 'کیف پول کاربران',
                    'is_active' => false,
                ],
                [
                    'text' => 'کیف پول کاربر',
                    'is_active' => true,
                ],
            ],
        ],
        'view/wallet/deposit-type' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'انواع تراکنش'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'انواع تراکنش‌ها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'انواع تراکنش',
                    'is_active' => true,
                ],
            ],
        ],
        'view/slider/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت اسلایدشو'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'اسلایدها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'اسلایدها',
                    'is_active' => true,
                ],
            ],

        ],
        'view/order/view' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'لیست سفارشات'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'سفارشات',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'اسلایدها',
                    'is_active' => true,
                ],
            ],

        ],
        'view/order/order-detail' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'جزئیات سفارش'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'سفارش',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.order.view'),
                    'text' => 'مدیرت سفارشات',
                    'is_active' => false,
                ],
                [
                    'text' => 'سفارش',
                    'is_active' => true,
                ],
            ],

        ],
        'view/order/badges' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'وضعیت سفارش'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مدیریت وضعیت سفارش',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'وضعیت سفارش',
                    'is_active' => true,
                ],
            ],

        ],
        'view/order/return-order' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'سفارشات مرجوعی'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مدیریت سفارشات مرجوعی',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'سفارشات مرجوعی',
                    'is_active' => true,
                ],
            ],

        ],
        'view/order/return-order-detail' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'سفارشات مرجوعی'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'مشاهده سفارشات مرجوعی',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.order.return'),
                    'text' => 'مدیریت سفارشات مرجوعی',
                    'is_active' => false,
                ],
                [
                    'text' => 'سفارشات مرجوعی',
                    'is_active' => true,
                ],
            ],

        ],
        'view/setting' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'تنظیمات سایت'),
            'common' => [
                'admin-base',
                'admin-table',
                'admin-form',
                'admin'
            ],
            'sub_title' => 'اسلایدها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'اسلایدها',
                    'is_active' => true,
                ],
            ],

        ],
        'view/file-manager/index' => [
            'title' => title_concat(\config()->get('settings.title.value'), 'مدیریت فایل‌ها'),
            'common' => [
                'admin-base',
                'admin-form',
                'admin-fab',
                'admin'
            ],
            'sub_title' => 'مدیریت فایل‌ها',
            'breadcrumb' => [
                [
                    'url' => url('admin.index'),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت فایل‌ها',
                    'is_active' => true,
                ],
            ],
        ],
    ],
    'tablet' => [
        'default' => [
            'common' => [
                'js' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
                ],
            ],
        ],
    ],
    'mobile' => [
        'default' => [
            'common' => [
                'js' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
                ],
            ],
        ],
    ],
];