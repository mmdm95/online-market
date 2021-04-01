<?php

return [
    /**
     * Font filename for captcha generation
     *
     * Default files included in library:
     *   - English -> Menlo-Regular,
     *   - Persian -> IRANSansWeb,
     *   - Arabic -> Lateef-Regular,
     */
    'font' => null,

    /**
     * Time to live of captcha to validate
     * after this time, captcha is not valid anymore
     *
     * Note: time must be in seconds
     *
     * Default is 600s or 10min
     */
    'expiration' => 600,

    /**
     * Number of generating characters
     *
     * Default is 6 characters
     */
    'length' => 6,

    /**
     * Difficulty of generating captcha
     *
     * Use one of following constants:
     *   - \Sim\Captcha\CaptchaFactory::DIFFICULTY_EASY
     *   - \Sim\Captcha\CaptchaFactory::DIFFICULTY_NORMAL
     *   - \Sim\Captcha\CaptchaFactory::DIFFICULTY_HARD
     *
     * Default is \Sim\Captcha\CaptchaFactory::DIFFICULTY_NORMAL
     */
    'difficulty' => \Sim\Captcha\CaptchaFactory::DIFFICULTY_EASY,

    /**
     * Add noise to make it harder to read
     *
     * Note: use boolean values
     *
     * Default is false
     */
    'noise' => false,
];