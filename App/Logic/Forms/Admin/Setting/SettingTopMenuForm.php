<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
use App\Logic\Utils\MenuUtil;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class SettingTopMenuForm implements IPageForm
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
                'inp-setting-menu.*.title',
                'inp-setting-menu.*.link',
                'inp-setting-menu.*.priority' => 'اولویت منو',
                'inp-setting-sub-menu.*.sub-title',
                'inp-setting-sub-menu.*.sub-link',
                'inp-setting-sub-menu.*.sub-priority' => 'اولویت زیر منو',
            ])
            ->setOptionalFields([
                'inp-setting-menu.*.title',
                'inp-setting-menu.*.link',
                'inp-setting-menu.*.priority',
                'inp-setting-sub-menu.*.sub-title',
                'inp-setting-sub-menu.*.sub-link',
                'inp-setting-sub-menu.*.sub-priority',
            ])
            ->toEnglishValueFields([
                'inp-setting-menu.*.priority',
                'inp-setting-sub-menu.*.sub-priority',
            ]);

        // priority
        $validator
            ->setFields([
                'inp-setting-menu.*.priority',
                'inp-setting-sub-menu.*.sub-priority',
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();

        $menu = input()->post('inp-setting-menu');
        $subMenu = input()->post('inp-setting-sub-menu');

        /**
         * @var MenuUtil $menuUtil
         */
        $menuUtil = container()->get(MenuUtil::class);
        $theMenu = $menuUtil->assembleTopMenu($menu, $subMenu);

        session()->setFlash('setting-top-menu-assembled', $theMenu);

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
            $theMenu = json_encode(session()->getFlash('setting-top-menu-assembled') ?: []);
            if (is_null($theMenu)) return false;

            return $settingModel->update([
                'setting_value' => $xss->xss_clean(trim($theMenu)),
            ], 'setting_name=:name', ['name' => SETTING_TOP_MENU]);
        } catch (\Exception $e) {
            return false;
        }
    }
}