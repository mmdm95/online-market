<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
use App\Logic\Validations\ExtendedValidator;
use Pecee\Http\Input\InputItem;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class SettingPageIndexForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws ConfigNotRegisteredException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
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
                'inp-setting-tabbed-slider-title' => 'عنوان اسلایدر تب‌بندی شده',
                'inp-setting-tabbed-slider-name.*' => 'نام تب‌بندی',
                'inp-setting-tabbed-slider-type.*' => 'نوع تب‌بندی',
                'inp-setting-tabbed-slider-limit.*' => 'حداکثر تعداد برای نمایش',
                'inp-setting-tabbed-slider-category.*' => 'دسته‌بندی تب‌بندی',
                //
                'inp-setting-three-slider-image.*' => 'تصویر، تصاویر سه‌تایی',
                'inp-setting-three-slider-link.*' => 'لینک تصاویر سه‌تایی',
            ])
            ->setOptionalFields([
                'inp-setting-tabbed-slider-name.*',
                'inp-setting-tabbed-slider-type.*',
                'inp-setting-tabbed-slider-limit.*',
                'inp-setting-tabbed-slider-category.*',
                //
                'inp-setting-three-slider-image.*',
                'inp-setting-three-slider-link.*',
            ]);

        // slider title
        $validator
            ->setFields('inp-setting-tabbed-slider-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanLength(50);

        if ($validator->getStatus()) {
            // assemble tabbed slider items
            $title = input()->post('inp-setting-tabbed-slider-title');
            $itemNames = input()->post('inp-setting-tabbed-slider-name');
            $itemTypes = input()->post('inp-setting-tabbed-slider-type');
            $itemLimits = input()->post('inp-setting-tabbed-slider-limit');
            $itemCategories = input()->post('inp-setting-tabbed-slider-category');

            $assembled = [
                'title' => $title->getValue(),
                'items' => [],
            ];
            $counter = 0;
            /**
             * @var InputItem $name
             */
            foreach ($itemNames as $name) {
                if ('' !== trim($name->getValue())) {
                    $assembled['items'][$counter]['name'] = $name->getValue();
                    $assembled['items'][$counter]['type'] = $itemTypes[$counter]->getValue();
                    $assembled['items'][$counter]['category'] = $itemLimits[$counter]->getValue();
                    $assembled['items'][$counter]['limit'] = $itemCategories[$counter]->getValue();
                    ++$counter;
                }
            }

            // put assembled items in session to store it later
            session()->setFlash('assembled_tabbed_slider_items', $assembled);

            // assemble 3 images items
            $itemImages = input()->post('inp-setting-three-slider-image');
            $itemLinks = input()->post('inp-setting-three-slider-link');
            $assembled = [];
            $counter = 0;
            /**
             * @var InputItem $image
             */
            foreach ($itemImages as $image) {
                $assembled[$counter]['image'] = $image->getValue();
                $assembled[$counter]['link'] = $itemLinks[$counter]->getValue();
                ++$counter;
            }
            session()->setFlash('assembled_3_images_items', $assembled);
        }

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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
            $assembledTabbedSlider = session()->getFlash('assembled_tabbed_slider_items');
            $assembled3Images = session()->getFlash('assembled_3_images_items');

            return $settingModel->updateIndexPageSetting([
                SETTING_INDEX_TABBED_SLIDER => $xss->xss_clean(trim(json_encode($assembledTabbedSlider))),
                SETTING_INDEX_3_IMAGES => $xss->xss_clean(trim(json_encode($assembled3Images))),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}