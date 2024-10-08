<?php

namespace App\Logic\Forms\Ajax\Newsletter;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\NewsletterModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddNewsletterForm implements IPageForm
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
        $validator
            ->setFieldsAlias([
                'inp-add-newsletter-mobile' => 'موبایل',
            ])
            ->toEnglishValue(true, true);

        // mobile
        $validator
            ->setFields('inp-add-newsletter-mobile')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile()
            ->custom(function (FormValue $value) {
                /**
                 * @var NewsletterModel $newsletterModel
                 */
                $newsletterModel = container()->get(NewsletterModel::class);
                if (0 === $newsletterModel->count('mobile=:mobile', ['mobile' => StringUtil::toEnglish($value->getValue())])) {
                    return true;
                }
                return false;
            }, 'این' . ' {alias} ' . 'قبلا ثبت شده است.');

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
            $mobile = input()->post('inp-add-newsletter-mobile', '')->getValue();
            // insert to database
            $res = $newsletterModel->insert([
                'mobile' => $xss->xss_clean(StringUtil::toEnglish($mobile)),
                'created_at' => time(),
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}