<?php

namespace App\Logic\Utils;

use Sim\Captcha\Captcha;
use Sim\Captcha\Exceptions\CaptchaException;
use Sim\Captcha\Interfaces\ICaptchaException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class CaptchaUtil
{
    /**
     * @param string $name
     * @param string $code
     * @return string
     * @throws CaptchaException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
     * @param string $name
     * @return bool
     * @throws ICaptchaException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public static function verify(string $input = '', string $name = ''): bool
    {
        /**
         * @var Captcha $captcha
         */
        $captcha = \container()->get('captcha-simple');
        return $captcha->useEnglishNumbersToVerify(true)->setName(trim($name))->verify($input);
    }
}