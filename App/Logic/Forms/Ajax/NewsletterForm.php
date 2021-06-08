<?php

namespace App\Logic\Forms\Ajax;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\NewsletterModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class NewsletterForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
     * @throws ConfigNotRegisteredException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getUniqueErrors(),
        ];
    }

    /**
     * {@inheritdoc}
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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

        try {
            $mobile = input()->post('inp-newsletter-mobile', '')->getValue();
            if (0 === $newsletterModel->count('mobile=:mobile', ['mobile' => StringUtil::toEnglish($mobile)])) {
                // insert to database
                $res = $newsletterModel->insert([
                    'mobile' => $xss->xss_clean(StringUtil::toEnglish($mobile)),
                    'created_at' => time(),
                ]);
            }
        } catch (\Exception $e) {
            return false;
        }

        return $res ?? true;
    }
}