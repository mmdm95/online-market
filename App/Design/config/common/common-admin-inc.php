<?php

return [
    /******************************
     *********** Admin ************
     *****************************/
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
                    asset_path('be/js/plugins/forms/inputs/maxlength.min.js') .
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
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('be/js/plugins/forms/styling/switchery.min.js') .
                    '"></script>'
                ),
            ]
        ],
    ],
    'admin-date' => [
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('be/js/plugins/pickers/persian-date.min.js') .
                    '"></script>'
                ),
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('be/js/plugins/pickers/persian-datepicker.min.js') .
                    '"></script>'
                ),
            ],
        ],
        'css' => [
            'top' => [
                e(
                    '<link href="' .
                    asset_path('be/css/persian-datepicker-custom.css') .
                    '" rel="stylesheet" type="text/css">'
                ),
            ],
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
    'admin-tags-input' => [
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('be/js/plugins/forms/tags/tagsinput.min.js') .
                    '"></script>'
                ),
            ],
        ],
    ],
    'admin-editor' => [
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('be/js/plugins/editors/tinymce/tinymce.min.js') .
                    '"></script>'
                ),
            ],
        ]
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
];