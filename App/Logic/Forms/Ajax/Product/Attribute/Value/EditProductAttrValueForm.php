<?php

namespace App\Logic\Forms\Ajax\Product\Attribute\Value;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BaseModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditProductAttrValueForm implements IPageForm
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
                'inp-edit-product-attr-val' => 'مقادیر ویژگی',
            ]);

        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        $id = session()->getFlash('product-attr-val-curr-e-id', null, false);
        if (!empty($id)) {
            if (0 === $attrModel->count('id=:id', ['id' => $id], BaseModel::TBL_PRODUCT_ATTR_VALUES)) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-product-attr-val', 'شناسه مقادیر ویژگی نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-product-attr-val', 'شناسه مقادیر ویژگی نامعتبر است.');
        }

        if ($validator->getStatus()) {
            /**
             * @var ProductAttributeModel $attrModel
             */
            $attrModel = container()->get(ProductAttributeModel::class);

            // attr val
            $validator
                ->setFields(['inp-edit-product-attr-val'])
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->lessThanEqualLength(250);
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
            $attrVal = input()->post('inp-edit-product-attr-val', '')->getValue();
            $id = session()->getFlash('product-attr-val-curr-e-id', null);

            if (is_null($id)) return false;

            return $attrModel->updateAssignedValueToAttr($id, $xss->xss_clean($attrVal));
        } catch (\Exception $e) {
            return false;
        }
    }
}
