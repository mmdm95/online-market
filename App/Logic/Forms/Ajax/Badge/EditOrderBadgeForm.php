<?php

namespace App\Logic\Forms\Ajax\Badge;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\UnitModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditOrderBadgeForm implements IPageForm
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
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-badge-title', 'شناسه وضعیت مورد نظر نامعتبر است.');
            } else {
                // title
                $validator
                    ->setFields('inp-edit-badge-title')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true)
                    ->lessThanEqualLength(250)
                    ->custom(function (FormValue $value) use ($validator, $badgeModel, $id) {
                        $badge = $badgeModel->getFirst(['title', 'can_edit_title'], 'id=:id', ['id' => $id]);
                        $title = $badge['title'];
                        $canEditTitle = $badge['can_edit_title'];

                        if (
                            $title !== trim($value->getValue()) &&
                            $canEditTitle != DB_YES
                        ) {
                            $validator->setError(
                                'inp-edit-badge-title',
                                'امکان تغییر ' . $validator->getFieldAlias('inp-edit-badge-title') . ' وجود ندارد.'
                            );
                            return false;
                        }

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
            $canReturnOrder = input()->post('inp-edit-badge-allow-return', '')->getValue();

            return $badgeModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'color' => $xss->xss_clean($color),
                'can_return_order' => is_value_checked($canReturnOrder) ? DB_YES : DB_NO,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
