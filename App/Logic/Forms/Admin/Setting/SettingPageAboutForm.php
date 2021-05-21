<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class SettingPageAboutForm implements IPageForm
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
                'inp-setting-img' => 'تصویر درباره ما',
                'inp-setting-title' => 'عنوان درباره ما',
                'inp-setting-desc' => 'توضیحات درباره ما',
            ]);

        // image, title, description
        $validator
            ->setFields([
                'inp-setting-img',
                'inp-setting-title',
                'inp-setting-desc',
            ])
            ->required();
        // image
        $validator
            ->setFields('inp-setting-img')
            ->imageExists();

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
            $image = input()->post('inp-setting-img', '')->getValue();
            $title = input()->post('inp-setting-title', '')->getValue();
            $desc = input()->post('inp-setting-desc', '')->getValue();
            $combined = [
                'image' => $xss->xss_clean(trim($image)),
                'title' => $xss->xss_clean(trim($title)),
                'desc' => trim($desc),
            ];

            return $settingModel->update([
                'setting_value' => json_encode($combined),
            ], 'setting_name=:name', ['name' => SETTING_ABOUT_SECTION]);
        } catch (\Exception $e) {
            return false;
        }
    }
}