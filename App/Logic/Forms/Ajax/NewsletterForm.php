<?php

namespace App\Logic\Forms\Ajax;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\NewsletterModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use voku\helper\AntiXSS;

class NewsletterForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws FormException
     */
    public function validate(): array
    {
        /**
         * @var ExtendedValidator $validator
         */
        $validator = form_validator();
        $validator->reset();

        // aliases
        $validator->setFieldsAlias([
            'inp-newsletter-mobile' => 'موبایل',
        ]);
        // username
        $validator
            ->setFields('inp-newsletter-mobile')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');

        return [
            $validator->getStatus(),
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
        ];
    }

    /**
     * {@inheritdoc}
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function store(): bool
    {
        /**
         * @var NewsletterModel $newsletterModel
         */
        $newsletterModel = container()->get(NewsletterModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        $mobile = input()->post('inp-newsletter-mobile', '')->getValue();
        if (0 === $newsletterModel->count('mobile=:mobile', ['mobile' => $mobile])) {
            // insert to database
            $res = $newsletterModel->insert([
                'mobile' => $xss->xss_clean($mobile),
                'created_at' => time(),
            ]);
        }

        return $res ?? true;
    }
}