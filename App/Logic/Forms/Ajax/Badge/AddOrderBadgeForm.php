<?php

namespace App\Logic\Forms\Ajax\Badge;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddOrderBadgeForm implements IPageForm
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
                'inp-add-badge-title' => 'عنوان وضعیت',
                'inp-add-badge-color' => 'رنگ وضعیت',
            ]);

        /**
         * @var OrderBadgeModel $badgeModel
         */
        $badgeModel = container()->get(OrderBadgeModel::class);

        // title
        $validator
            ->setFields('inp-add-badge-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250)
            ->custom(function (FormValue $value) use ($badgeModel) {
                if (0 !== $badgeModel->count('title=:title', ['title' => trim($value->getValue())])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'تکراری می‌باشد.');
        // color
        $validator
            ->setFields('inp-add-badge-color')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->hexColor();

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
            $title = input()->post('inp-add-badge-title', '')->getValue();
            $color = input()->post('inp-add-badge-color', '')->getValue();
            $canReturnOrder = input()->post('inp-add-badge-allow-return', '')->getValue();

            $res = $badgeModel->insert([
                'code' => StringUtil::uniqidReal(12),
                'title' => $xss->xss_clean(trim($title)),
                'color' => $xss->xss_clean($color),
                'can_return_order' => is_value_checked($canReturnOrder) ? DB_YES : DB_NO,
                'created_at' => time(),
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}