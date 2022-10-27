<?php

namespace App\Logic\Forms\Admin\Product\Attribute\Category;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditProductAttrCategoryForm implements IPageForm
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
                'inp-edit-product-attr-id' => 'ویژگی',
                'inp-edit-product-cat-id' => 'دسته‌بندی',
            ]);

        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        $id = session()->getFlash('product-attr-cat-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $attrModel->count('id=:id', ['id' => $id], BaseModel::TBL_PRODUCT_ATTR_CATEGORY)) {
                $validator->setError('inp-edit-product-attr-id', 'شناسه تخصیص ویژگی به دسته‌بندی نامعتبر است.');
            } else {
                $attrId = input()->post('inp-edit-product-attr-id', '')->getValue();
                $catId = input()->post('inp-edit-product-cat-id', '')->getValue();
                if (
                    $attrModel->count(
                        'p_attr_id=:aId AND c_id=:cId', ['aId' => $attrId, 'cId' => $catId], BaseModel::TBL_PRODUCT_ATTR_CATEGORY
                    )
                ) {
                    $validator
                        ->setStatus(false)
                        ->setError('inp-edit-product-attr-id', 'این دسته‌بندی دارای این ویژگی می‌باشد، مجددا تلاش نمایید.');
                }
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-product-attr-id', 'شناسه تخصیص ویژگی به دسته‌بندی نامعتبر است.');
        }

        if ($validator->getStatus()) {
            /**
             * @var ProductAttributeModel $attrModel
             */
            $attrModel = container()->get(ProductAttributeModel::class);
            /**
             * @var CategoryModel $categoryModel
             */
            $categoryModel = container()->get(CategoryModel::class);

            // attr id
            $validator
                ->setFields(['inp-edit-product-attr-id'])
                ->required()
                ->custom(function (FormValue $value) use ($attrModel) {
                    if (0 !== $attrModel->count('id=:id', ['id' => trim($value->getValue())])) {
                        return true;
                    }
                    return false;
                }, '{alias} ' . 'انتخاب شده نامعتبر است.');

            // category id
            $validator
                ->setFields(['inp-edit-product-cat-id'])
                ->required()
                ->custom(function (FormValue $value) use ($categoryModel) {
                    if (0 !== $categoryModel->count('id=:id', ['id' => trim($value->getValue())])) {
                        return true;
                    }
                    return false;
                }, '{alias} ' . 'انتخاب شده نامعتبر است.');
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
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $attrId = input()->post('inp-edit-product-attr-id', '')->getValue();
            $catId = input()->post('inp-edit-product-cat-id', '')->getValue();
            $id = session()->getFlash('product-attr-cat-curr-id', null);

            if (is_null($id)) return false;

            return $attrModel->updateAssignedAttrCategory($id, $xss->xss_clean($attrId), $xss->xss_clean($catId));
        } catch (\Exception $e) {
            return false;
        }
    }
}
