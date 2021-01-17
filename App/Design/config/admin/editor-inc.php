<?php

return [
    'partial/editor/browser' => [
        'title' => title_concat(\config()->get('settings.title.value'), 'انتخاب فایل'),
        'common' => [
            'admin-base',
            'admin',
        ],
    ],
];