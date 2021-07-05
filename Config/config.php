<?php

return [
    /**
     * Set current mode.
     * Acceptable values are [MODE_DEVELOPMENT],[MODE_PRODUCTION]
     */
    'mode' => MODE_DEVELOPMENT,

    /**
     * In general, the errors are show in framework's formatting
     * manners, if you need to see actual errors (in PHP format or native),
     * set this to true
     * Acceptable values are [true], [false]
     */
    'show_native_errors' => false,

    /*
     * Maintenance settings
     */
    'maintenance' => [
        /*
         * Is it in maintenance mode or not
         * [accept: true|false]
         */
        'is_on' => false,

        /*
         * Which page to show on maintenance mode
         *
         * It'll get page from [error] path in logic
         */
        'page' => 'maintenance.php',

        /*
         * Event in maintenance mode, you can see application as
         * a developer with a unique key in url query parameters.
         *
         * To enter you need to specify it in url like:
         *   www.url-to-app.com?key=your_special_key
         *
         * You can specify an array of codes
         *
         * [accept: string|array]
         */
        'force_with' => 'xXibUvvu0VUIPQi5FNcRDQmYaAL3rHXzudB3CAsPDII',
    ],
];