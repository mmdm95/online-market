<?php

return [
    /**
     * Notfound route configuration
     */
    'notfound_route' => [
        /**
         * Notfound error layout.
         * Note: if you don't have any layout for notfound, make sure this parameter is [null]
         */
        'layout' => 'main',

        /**
         * Notfound error template
         */
        'template' => 'view/main/404',
    ],

    'error_route' => [
        /**
         * Server error layout.
         * Note: if you don't have any layout for server error, make sure this parameter is [null]
         */
        'layout' => null,

        /**
         * Server error template
         */
        'template' => 'error/500',
    ],
];