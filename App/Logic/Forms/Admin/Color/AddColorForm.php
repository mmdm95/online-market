<?php

namespace App\Logic\Forms\Admin\Color;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ColorModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddColorForm implements IPageForm
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
                'inp-add-color-name' => 'نام رنگ',
                'inp-add-color-color' => 'انتخاب رنگ',
            ]);

        // name
        $validator
            ->setFields('inp-add-color-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // color
        $validator
            ->setFields('inp-add-color-color')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->hexColor()
            ->custom(function (FormValue $value) {
                /**
                 * @var ColorModel $colorModel
                 */
                $colorModel = container()->get(ColorModel::class);
                if (
                    0 !== $colorModel->count('hex=:hex', ['hex' => trim($value->getValue())])
                ) {
                    return false;
                }
                return true;
            }, 'کد رنگی انتخاب شده وجود دارد.');

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getError(),
            $validator->getUniqueErrors(),
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
         * @var ColorModel $colorModel
         */
        $colorModel = container()->get(ColorModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $name = input()->post('inp-add-color-name', '')->getValue();
            $color = input()->post('inp-add-color-color', '')->getValue();
            $pub = input()->post('inp-add-color-status', '')->getValue();

            return $colorModel->insert([
                'name' => $xss->xss_clean(trim($name)),
                'hex' => $xss->xss_clean(strtolower($color)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}