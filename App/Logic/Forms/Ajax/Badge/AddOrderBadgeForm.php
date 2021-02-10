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
use Sim\Form\FormValue;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddOrderBadgeForm implements IPageForm
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
                if(0 !== $badgeModel->count('title=:title', ['title' => trim($value->getValue())])) {
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
            $code = StringUtil::uniqidReal(12);
            $title = input()->post('inp-add-badge-title', '')->getValue();
            $color = input()->post('inp-add-badge-color', '')->getValue();

            $res = $badgeModel->insert([
                'code' => $code,
                'title' => $xss->xss_clean(trim($title)),
                'color' => $xss->xss_clean($color),
                'created_at' => time(),
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}