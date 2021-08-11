<?php

return [
    /**
     * Admin error pages translate
     */
    '404' => [
        'title' => 'page not found - 404',
        'number' => '404',
        'message' => 'Page not found!',
        'back_btn' => 'return',
        'home_btn' => 'main page'
    ],
    '500' => [
        'title' => 'server error - 500',
        'number' => '500',
        'message' => 'Server error',
    ],
    'maintenance' => [
        'title' => 'in maintenance mode',
        'body' => '<h1>We&rsquo;ll be back soon!</h1>
    <div>
        <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can
            always <a href="mailto:#">contact us</a>, otherwise we&rsquo;ll be back online shortly!</p>
        <p>&mdash; The Team</p>
    </div>',
    ],
    'not_implemented_yet' => 'Not implemented yet!',

    /**
     * Form translation
     */
    'form_translation' => [
        'alphaNum' => '{alias} should have alpha and numeric',
        'alpha' => '{alias} should have alpha',
        'email' => '{alias} is not a valid email',
        'equalLength' => '{alias} length is not equal to {length}',
        'equal' => '{alias} is not equal to {compareTo}',
        'isFloat' => '{alias} is not float',
        'greaterThanEqualLength' => '{alias} length is not greater than or equal to {min}',
        'greaterThanEqual' => '{alias} is not greater than or equal to {min}',
        'greaterThanLength' => '{alias} length is not greater than {min}',
        'greaterThan' => '{alias} is not greater than {min}',
        'hexColor' => '{alias} is not a valid hex color',
        'isIn' => '{alias} is not in {list}',
        'isInteger' => '{alias} is not integer',
        'ipv4' => '{alias} is not a valid ipv4',
        'ipv6' => '{alias} is not a valid ipv6',
        'ip' => '{alias} is not a valid ip',
        'isChecked' => '{alias} is not checked',
        'lengthBetween' => '{alias} length should be between {min} and {max}',
        'lessThanEqualLength' => '{alias} length is not less than or equal to {max}',
        'lessThanEqual' => '{alias} is not less than or equal to {max}',
        'lessThanLength' => '{alias} length is not less than {max}',
        'lessThan' => '{alias} is not less than {max}',
        'between' => '{alias} should be between {min} and {max}',
        'password' => '{alias} is not a good password',
        'regex' => '{alias} is not in valid format',
        'required' => '{alias} is required',
        'requiredWithAll' => '{alias} is required',
        'requiredWith' => '{alias} is required',
        'timestamp' => '{alias} is not valid timestamp',
        'isUnique' => '{alias} is not unique array',
        'url' => '{alias} is not a valid url',
        'match' => '{second} is not equal to {first}',
        'fileDuplicate' => '{alias} is already exists',
        'persianAlpha' => '{alias} must be persian alpha',
        'persianMobile' => '{alias} is invalid!',
        'persianNationalCode' => '{alias} is invalid!',
        'imageExists' => '{alias} not exists!',
        'alphaSpace' => '{alias} should have english alpha',
    ],
];
