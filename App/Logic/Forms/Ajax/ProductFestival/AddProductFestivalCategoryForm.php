<?php

namespace App\Logic\Forms\Ajax\ProductFestival;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\FestivalModel;
use App\Logic\Models\ProductFestivalModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddProductFestivalCategoryForm implements IPageForm
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
        $validator
            ->setFieldsAlias([
                'inp-modify-product-festival-category' => 'شناسه دسته‌بندی',
                'inp-modify-product-festival-percent' => 'درصد تخفیف',
            ])
            ->setOptionalFields([
                'inp-add-unit-sign',
            ]);

        // category
        $validator
            ->setFields('inp-modify-product-festival-category')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var CategoryModel $categoryModel
                 */
                $categoryModel = container()->get(CategoryModel::class);

                if (
                    $categoryModel->count('id=:id', ['id' => trim($value->getValue())]) !== 0
                ) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'نامعتبر است.');
        // percentage
        $validator
            ->setFields('inp-modify-product-festival-percent')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->between(0, 100);

        $fId = session()->getFlash('product-festival-category-f-id', null, false);
        if (!empty($fId)) {
            /**
             * @var FestivalModel $festivalModel
             */
            $festivalModel = container()->get(FestivalModel::class);

            if (0 === $festivalModel->count('id=:id', ['id' => $fId])) {
                $validator->setError('inp-modify-product-festival-category', 'شناسه جشنواره نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-modify-product-festival-category', 'شناسه جشنواره نامعتبر است.');
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
         * @var ProductFestivalModel $festivalModel
         */
        $festivalModel = container()->get(ProductFestivalModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $fId = session()->getFlash('product-festival-category-f-id', null);
            $category = input()->post('inp-modify-product-festival-category', '')->getValue();
            $percent = input()->post('inp-modify-product-festival-percent', '')->getValue();

            $res = $festivalModel->addCategoryToFestival($xss->xss_clean($fId), $xss->xss_clean($category), $xss->xss_clean($percent));
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}