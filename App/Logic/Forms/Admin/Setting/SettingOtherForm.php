<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class SettingOtherForm implements IPageForm
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
                'inp-setting-product-pagination' => 'تعداد کالا در هر صفحه',
                'inp-setting-blog-pagination' => 'تعداد بلاگ در هر صفحه',
            ]);

        // product and blog pagination number
        $validator
            ->setFields([
                'inp-setting-product-pagination',
                'inp-setting-blog-pagination',
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getUniqueErrors(),
            $validator->getError(),
            $validator->getFormattedError('<p class="m-0">'),
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
            $validator->getRawErrors(),
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
         * @var SettingModel $settingModel
         */
        $settingModel = container()->get(SettingModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $productPagination = input()->post('inp-setting-product-pagination', '')->getValue();
            $blogPagination = input()->post('inp-setting-blog-pagination', '')->getValue();

            return $settingModel->updateOtherSetting([
                SETTING_PRODUCT_EACH_PAGE => $xss->xss_clean(trim($productPagination)),
                SETTING_BLOG_EACH_PAGE => $xss->xss_clean(trim($blogPagination)),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}