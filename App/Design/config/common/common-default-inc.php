<?php

return [
    /******************************
     ************ User ************
     *****************************/
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
                    asset_path('plugins/lazy.min.js') .
                    '"></script>'
                ),
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/cart.js') .
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
                    asset_path('css/app.css') .
                    '" rel="stylesheet" type="text/css">'
                ),
            ],
        ],
    ],
    'default-changeable' => [
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/scripts.js') .
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
    'default-theia-sticky-sidebar' => [
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('plugins/theia/ResizeSensor.min.js') .
                    '"></script>'
                ),
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('plugins/theia/theia-sticky-sidebar.min.js') .
                    '"></script>'
                ),
            ]
        ],
    ],
    'default-jquery-ui' => [
        'js' => [
            'bottom' => [
                e(
                    '<script type="text/javascript" src="' .
                    asset_path('js/all/jquery-ui.min.js') .
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
];