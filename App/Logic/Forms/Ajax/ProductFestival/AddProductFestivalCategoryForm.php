<?php

namespace App\Logic\Forms\Ajax\ProductFestival;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\FestivalModel;
use App\Logic\Models\ProductFestivalModel;
use App\Logic\Validations\ExtendedValidator;
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
                $validator
                    ->setStatus(false)
                    ->setError('inp-modify-product-festival-category', 'شناسه جشنواره نامعتبر است.');
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