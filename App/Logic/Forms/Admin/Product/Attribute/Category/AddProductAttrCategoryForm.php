<?php

namespace App\Logic\Forms\Admin\Product\Attribute\Category;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Validations\ExtendedValidator;
use DI\DependencyException;
use DI\NotFoundException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddProductAttrCategoryForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
     * @throws ConfigNotRegisteredException
     * @throws DependencyException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws NotFoundException
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
                'inp-add-product-attr-id' => 'ویژگی',
                'inp-add-product-cat-id' => 'دسته‌بندی',
            ]);

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
            ->setFields(['inp-add-product-attr-id'])
            ->required()
            ->custom(function (FormValue $value) use ($attrModel) {
                if (0 !== $attrModel->count('id=:id', ['id' => trim($value->getValue())])) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');

        // category id
        $validator
            ->setFields(['inp-add-product-cat-id'])
            ->required()
            ->custom(function (FormValue $value) use ($categoryModel) {
                if (0 !== $categoryModel->count('id=:id', ['id' => trim($value->getValue())])) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');

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
            $attrId = input()->post('inp-add-product-attr-id', '')->getValue();
            $catId = input()->post('inp-add-product-cat-id', '')->getValue();

            return $attrModel->assignAttrToCategory($xss->xss_clean($attrId), $xss->xss_clean($catId));
        } catch (\Exception $e) {
            return false;
        }
    }
}
