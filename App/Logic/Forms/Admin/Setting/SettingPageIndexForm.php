<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
use App\Logic\Utils\SettingUtil;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class SettingPageIndexForm implements IPageForm
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
                'inp-setting-tabbed-slider-side-image' => 'تصویر کنار اسلایدر تب‌بندی شده',
                'inp-setting-tabbed-slider-side-image-link' => 'لینک تصویر کنار اسلایدر تب‌بندی شده',

                'inp-setting-tabbed-slider-title' => 'عنوان اسلایدر تب‌بندی شده',
                'inp-setting-tabbed-slider-name.*' => 'نام تب‌بندی',
                'inp-setting-tabbed-slider-type.*' => 'نوع تب‌بندی',
                'inp-setting-tabbed-slider-limit.*' => 'حداکثر تعداد برای نمایش',
                'inp-setting-tabbed-slider-category.*' => 'دسته‌بندی تب‌بندی',
                //
                'inp-setting-general-slider-image-link.*' => 'لینک تصویر کنار اسلایدر',
                'inp-setting-general-slider-image.*' => 'تصویر کنار اسلایدر',
                'inp-setting-general-slider-title.*' => 'عنوان اسلایدر',
                'inp-setting-general-slider-type.*' => 'نوع محصولات اسلایدر',
                'inp-setting-general-slider-limit.*' => 'حداکثر تعداد برای نمایش',
                'inp-setting-general-slider-category.*' => 'دسته‌بندی تب‌بندی',
                'inp-setting-general-slider-link.*' => 'لینک مشاهده بیشتر محصولات',
                //
                'inp-setting-three-slider-image.*' => 'تصویر تصاویر سه‌تایی',
                'inp-setting-three-slider-link.*' => 'لینک تصاویر سه‌تایی',
                //
                'inp-setting-main-slider-side-title.*' => 'نوشته‌ی تصاویر کنار اسلایدر اصلی',
                'inp-setting-main-slider-side-image.*' => 'تصویر تصاویر کنار اسلایدر اصلی',
                'inp-setting-main-slider-side-color.*' => 'رنگ تصاویر کنار اسلایدر اصلی',
                'inp-setting-main-slider-side-link.*' => 'لینک تصاویر کنار اسلایدر اصلی',
            ])
            ->setOptionalFields([
                'inp-setting-tabbed-slider-side-image',
                'inp-setting-tabbed-slider-side-image-link',
                //
                'inp-setting-tabbed-slider-name.*',
                'inp-setting-tabbed-slider-type.*',
                'inp-setting-tabbed-slider-limit.*',
                'inp-setting-tabbed-slider-category.*',
                //
                'inp-setting-general-slider-image-link.*',
                'inp-setting-general-slider-image.*',
                'inp-setting-general-slider-title.*',
                'inp-setting-general-slider-limit.*',
                'inp-setting-general-slider-category.*',
                'inp-setting-general-slider-link.*',
                //
                'inp-setting-three-slider-image.*',
                'inp-setting-three-slider-link.*',
                //
                'inp-setting-main-slider-side-title.*',
                'inp-setting-main-slider-side-sub-title.*',
                'inp-setting-main-slider-side-image.*',
                'inp-setting-main-slider-side-color.*',
                'inp-setting-main-slider-side-link.*',
            ]);

        // slider(s) title
        $validator
            ->setFields([
                'inp-setting-tabbed-slider-title',
                'inp-setting-general-slider-title',
            ])
            ->lessThanLength(50);

        if ($validator->getStatus()) {
            // assemble tabbed slider items
            $title = input()->post('inp-setting-tabbed-slider-title');
            $itemNames = input()->post('inp-setting-tabbed-slider-name');
            $itemTypes = input()->post('inp-setting-tabbed-slider-type');
            $itemLimits = input()->post('inp-setting-tabbed-slider-limit');
            $itemCategories = input()->post('inp-setting-tabbed-slider-category');

            $assembled = SettingUtil::assembleTabbedSlider($title, $itemNames, $itemTypes, $itemLimits, $itemCategories);

            // put assembled items in session to store it later
            session()->setFlash('assembled_tabbed_slider_items', $assembled);

            // assemble tabbed slider side image
            $image = input()->post('inp-setting-tabbed-slider-side-image');
            $link = input()->post('inp-setting-tabbed-slider-side-image-link');
            $assembled = [];

            if (is_image_exists($image)) {
                $assembled = [
                    'image' => $image,
                    'link' => $link,
                ];
            }

            // put assembled items in session to store it later
            session()->setFlash('assembled_tabbed_slider_items_side_image', $assembled);

            // assemble general slider items
            $itemImagesLink = input()->post('inp-setting-general-slider-image-link');
            $itemImages = input()->post('inp-setting-general-slider-image');
            $itemTitles = input()->post('inp-setting-general-slider-title');
            $itemTypes = input()->post('inp-setting-general-slider-type');
            $itemLimits = input()->post('inp-setting-general-slider-limit');
            $itemCategories = input()->post('inp-setting-general-slider-category');
            $itemLinks = input()->post('inp-setting-general-slider-link');

            $assembled = SettingUtil::assembleGeneralSlider(
                $itemImagesLink,
                $itemImages,
                $itemTitles,
                $itemTypes,
                $itemLimits,
                $itemCategories,
                $itemLinks
            );

            // put assembled items in session to store it later
            session()->setFlash('assembled_general_slider_items', $assembled);

            // assemble 3 images items
            $itemImages = input()->post('inp-setting-three-slider-image');
            $itemLinks = input()->post('inp-setting-three-slider-link');

            $assembled = SettingUtil::assembleThreeImages($itemImages, $itemLinks);

            session()->setFlash('assembled_3_images_items', $assembled);

            // assemble main slider side images items
            $itemTitles = input()->post('inp-setting-main-slider-side-title');
            $itemSubTitles = input()->post('inp-setting-main-slider-side-sub-title');
            $itemImages = input()->post('inp-setting-main-slider-side-image');
            $itemColors = input()->post('inp-setting-main-slider-side-color');
            $itemLinks = input()->post('inp-setting-main-slider-side-link');

            $assembled = SettingUtil::assembleMainSliderSideImages(
                $itemTitles,
                $itemSubTitles,
                $itemImages,
                $itemColors,
                $itemLinks
            );

            session()->setFlash('assembled_main_slider_side_images_items', $assembled);
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
            $assembledTabbedSlider = session()->getFlash('assembled_tabbed_slider_items');
            $assembledTabbedSliderSideImage = session()->getFlash('assembled_tabbed_slider_items_side_image');
            $assembledGeneralSlider = session()->getFlash('assembled_general_slider_items');
            $assembled3Images = session()->getFlash('assembled_3_images_items');
            $assembledMainSliderSideImages = session()->getFlash('assembled_main_slider_side_images_items');

            return $settingModel->updateIndexPageSetting([
                SETTING_INDEX_TABBED_SLIDER => $xss->xss_clean(trim(json_encode($assembledTabbedSlider))),
                SETTING_INDEX_TABBED_SLIDER_SIDE_IMAGE => $xss->xss_clean(trim(json_encode($assembledTabbedSliderSideImage))),
                SETTING_INDEX_GENERAL_SLIDERS => $xss->xss_clean(trim(json_encode($assembledGeneralSlider))),
                SETTING_INDEX_3_IMAGES => $xss->xss_clean(trim(json_encode($assembled3Images))),
                SETTING_INDEX_MAIN_SLIDER_SIDE_IMAGES => $xss->xss_clean(trim(json_encode($assembledMainSliderSideImages))),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
