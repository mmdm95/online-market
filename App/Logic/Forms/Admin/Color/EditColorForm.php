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

class EditColorForm implements IPageForm
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
                'inp-edit-color-name' => 'نام رنگ',
                'inp-edit-color-color' => 'انتخاب رنگ',
            ]);

        // name
        $validator
            ->setFields('inp-edit-color-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // color
        $validator
            ->setFields('inp-edit-color-color')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->hexColor()
            ->custom(function (FormValue $value) {
                /**
                 * @var ColorModel $colorModel
                 */
                $colorModel = container()->get(ColorModel::class);
                $prevHex = session()->getFlash('color-prev-hex', null);
                if (empty($prevHex)) return false;
                if (
                    $prevHex != trim($value->getValue()) &&
                    0 !== $colorModel->count('hex=:hex', ['hex' => trim($value->getValue())])
                ) {
                    return false;
                }
                return true;
            }, 'کد رنگی انتخاب شده وجود دارد.');

        $id = session()->getFlash('color-curr-id', null, false);
        if (!empty($id)) {
            /**
             * @var ColorModel $colorModel
             */
            $colorModel = container()->get(ColorModel::class);

            if (0 === $colorModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-color-name', 'شناسه رنگ نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-color-name', 'شناسه رنگ نامعتبر است.');
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
            $id = session()->getFlash('color-curr-id', null);
            $name = input()->post('inp-edit-color-name', '')->getValue();
            $color = input()->post('inp-edit-color-color', '')->getValue();
            $pub = input()->post('inp-edit-color-status', '')->getValue();

            return $colorModel->update([
                'name' => $xss->xss_clean(trim($name)),
                'hex' => $xss->xss_clean(strtolower($color)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}