<?php

namespace App\Logic\Forms\Ajax\Badge;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\UnitModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class EditOrderBadgeForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws FormException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
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
        $validator->setFieldsAlias([
            'inp-edit-badge-title' => 'عنوان وضعیت',
            'inp-edit-badge-color' => 'رنگ وضعیت',
        ]);

        /**
         * @var OrderBadgeModel $badgeModel
         */
        $badgeModel = container()->get(OrderBadgeModel::class);

        $id = session()->getFlash('order-badge-edit-id', null, false);
        if (!empty($id)) {
            /**
             * @var UnitModel $unitModel
             */
            $unitModel = container()->get(UnitModel::class);

            if (0 === $unitModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-badge-title', 'شناسه وضعیت مورد نظر نامعتبر است.');
            } else {
                // title
                $validator
                    ->setFields('inp-edit-badge-title')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true)
                    ->lessThanEqualLength(250)
                    ->custom(function (FormValue $value) use ($badgeModel, $id) {
                        $title = $badgeModel->getFirst(['title'], 'id=:id', ['id' => $id])['title'];
                        if (
                            $title !== trim($value->getValue()) &&
                            0 !== $badgeModel->count('title=:title', ['title' => trim($value->getValue())])
                        ) {
                            return false;
                        }
                        return true;
                    }, '{alias} ' . 'تکراری می‌باشد.');
                // color
                $validator
                    ->setFields('inp-edit-badge-color')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true)
                    ->hexColor();
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-badge-title', 'شناسه وضعیت مورد نظر نامعتبر است.');
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

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
         * @var OrderBadgeModel $badgeModel
         */
        $badgeModel = container()->get(OrderBadgeModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $id = session()->getFlash('order-badge-edit-id', null);
            $title = input()->post('inp-edit-badge-title', '')->getValue();
            $color = input()->post('inp-edit-badge-color', '')->getValue();
            $allowReturn = input()->post('inp-edit-badge-allow-return', '')->getValue();

            $res = $badgeModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'color' => $xss->xss_clean($color),
                'allow_return_order' => is_value_checked($allowReturn) ? DB_YES : DB_NO,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}