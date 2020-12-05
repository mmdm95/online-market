<?php

namespace App\Logic\Validations;

use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;

class FormValidations
{
    /**
     * @var ExtendedValidator
     */
    protected $validator;

    /**
     * FormValidations constructor.
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function __construct()
    {
        $this->validator = form_validator();
        $this->validator->reset();
    }

    /**
     * @return array
     * @throws FormException
     */
    public function contact(): array
    {
        // aliases
        $this->validator->setFieldsAlias([
            'inp-contact-name' => 'نام',
            'inp-contact-mobile' => 'موبایل',
            'inp-contact-subject' => 'موضوع',
            'inp-contact-message' => 'متن پیام',
            'inp-contact-captcha' => 'کد تصویر',
        ]);
        // captcha
        $this->validator
            ->setFields('inp-contact-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // name
        $this->validator
            ->setFields('inp-contact-name')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // mobile
        $this->validator
            ->setFields('inp-contact-mobile')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianMobile('موبایل نامعتبر است.');
        // subject
        $this->validator
            ->setFields('inp-contact-subject')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(250, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // message
        $this->validator
            ->setFields('inp-contact-message')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true);

        return [
            $this->validator->getStatus(),
            $this->validator->getError(),
            $this->validator->getUniqueErrors(),
            $this->validator->getFormattedError('<p class="m-0">'),
            $this->validator->getFormattedUniqueErrors('<p class="m-0">'),
            $this->validator->getRawErrors(),
        ];
    }
}