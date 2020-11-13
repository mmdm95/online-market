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
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
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
                            hashed_path('css', 'font.css') .
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
                            asset_path('be/js/demo_pages/components_modals.js') .
                            '"></script>'
                        ),
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
                            asset_path('be/js/demo_pages/gallery.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/forms/styling/switch.min.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/demo_pages/form_checkboxes_radios.js') .
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
                            asset_path('be/js/demo_pages/datatables_advanced.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/plugins/tables/datatables/datatables.min.js') .
                            '"></script>'
                        ),
                    ]
                ],
            ],
            'admin' => [
                'js' => [
                    'bottom' => [
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/demo_pages/components_popups.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/demo_pages/form_layouts.js') .
                            '"></script>'
                        ),
                        e(
                            '<script type="text/javascript" src="' .
                            asset_path('be/js/app.js') .
                            '"></script>'
                        ),
                    ],
                ],
            ],
        ],
        'view/admin-login' => [
            'title' => 'پایار تأسیسات | صفحه ورود',
            'common' => [
                'admin-base',
                'admin'
            ],
        ],
        'view/user/view' => [
            'title' => 'پایار تأسیسات | مشاهده کاربران',
            'common' => [
                'admin-base',
                'admin-form',
                'admin-table',
                'admin'
            ],
        ],
        'view/user/add' => [
            'title' => 'پایار تأسیسات | افزودن کاربر',
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
            'title' => 'پایار تأسیسات | ویرایش کاربر',
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
        'view/user/view-profile' => [
            'title' => 'پایار تأسیسات | مشاهده کاربر',
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
            ],
        ],
        'view/category/add' => [
            'title' => 'پایار تأسیسات | افزودن دسته',
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
        'view/file-manager/index' => [
            'title' => 'پایار تأسیسات | مدیریت فایل‌ها',
            'common' => [
                'admin-base',
                'admin-form',
                'admin'
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