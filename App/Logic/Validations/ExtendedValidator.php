<?php

namespace App\Logic\Validations;

use App\Logic\Utils\CaptchaUtil;
use Sim\Form\Exceptions\FormException;
use Sim\Form\Exceptions\ValidationException;
use Sim\Form\ValidationExtend\AbstractCustomValidation;
use Sim\Form\Validations\PersianAlphaValidation;
use Sim\Form\Validations\PersianMobileValidation;
use Sim\Form\Validations\PersianNationalCodeValidation;

class ExtendedValidator extends AbstractCustomValidation
{
    /**
     * @var array
     */
    protected $extend_validator_classes = [
        'persianAlpha' => PersianAlphaValidation::class,
        'persianMobile' => PersianMobileValidation::class,
        'persianNationalCode' => PersianNationalCodeValidation::class,
    ];

    /**
     * @param string|null $message
     * @param callable|null $callback
     * @return $this
     * @throws FormException
     */
    public function persianAlpha(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_persian_alpha($value);
        }, $callback);
        return $this;
    }

    /**
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     * @throws FormException
     */
    public function persianMobile(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_persian_mobile($value);
        }, $callback);
        return $this;
    }

    /**
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     * @throws FormException
     */
    public function persianNationalCode(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return $this->_persian_national_code($value);
        }, $callback);
        return $this;
    }

    /**
     * @param string|null $message
     * @param callable|null $callback
     * @return static
     * @throws FormException
     */
    public function captcha(?string $message = null, callable $callback = null)
    {
        $this->assertRequirements();
        $name = $this->fields;
        $this->_execute($name, $message, __FUNCTION__, function ($value) {
            return !CaptchaUtil::verify($value, input()->post('inp-captcha-name', ''));
        }, $callback);
        return $this;
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _persian_alpha($value): bool
    {
        /**
         * @var PersianAlphaValidation $instance
         */
        $instance = $this->getInstanceOf('persianAlpha');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws ValidationException
     * @throws \ReflectionException
     */
    protected function _persian_mobile($value): bool
    {
        /**
         * @var PersianMobileValidation $instance
         */
        $instance = $this->getInstanceOf('persianMobile');
        return $instance->validate($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws \ReflectionException
     * @throws ValidationException
     */
    protected function _persian_national_code($value): bool
    {
        /**
         * @var PersianNationalCodeValidation $instance
         */
        $instance = $this->getInstanceOf('persianNationalCode');
        return $instance->validate($value);
    }
}