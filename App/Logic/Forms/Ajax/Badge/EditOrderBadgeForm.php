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
use Sim\Form\Exceptions\FormException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class EditOrderBadgeForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws FormException
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

        // title
        $validator
            ->setFields('inp-edit-badge-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // color
        $validator
            ->setFields('inp-edit-badge-color')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->hexColor();

        $id = session()->getFlash('order-badge-edit-id', null, false);
        if (!empty($id)) {
            /**
             * @var UnitModel $unitModel
             */
            $unitModel = container()->get(UnitModel::class);

            if (0 === $unitModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-badge-title', 'شناسه وضعیت مورد نظر نامعتبر است.');
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

            $res = $badgeModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'color' => $xss->xss_clean($color),
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}