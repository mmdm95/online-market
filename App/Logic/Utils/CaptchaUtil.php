<?php

namespace App\Logic\Utils;

use Sim\Captcha\Captcha;
use Sim\Captcha\Exceptions\CaptchaException;
use Sim\Captcha\Interfaces\ICaptchaException;

class CaptchaUtil
{
    /**
     * @param string $name
     * @param string $code
     * @return string
     * @throws CaptchaException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function get(string $name = '', string $code = ''): string
    {
        /**
         * @var Captcha $captcha
         */
        $captcha = \container()->get('captcha-simple');
        return $captcha
            ->setHeight(42)
            ->setName(trim($name))
            ->generate(trim($code));
    }

    /**
     * @param string $input
     * @param string|null $name
     * @return bool
     * @throws ICaptchaException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function verify(string $input = '', ?string $name = ''): bool
    {
        /**
         * @var Captcha $captcha
         */
        $captcha = \container()->get('captcha-simple');
        try {
            return $captcha->useEnglishNumbersToVerify(true)->setName(trim((string)$name))->verify($input);
        } catch (\Exception $e) {
            return false;
        }
    }
}