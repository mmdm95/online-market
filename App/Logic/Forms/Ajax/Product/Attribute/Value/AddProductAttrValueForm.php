<?php

namespace App\Logic\Forms\Ajax\Product\Attribute\Value;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BaseModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Validations\ExtendedValidator;
use DI\DependencyException;
use DI\NotFoundException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddProductAttrValueForm implements IPageForm
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
                'inp-add-product-attr-val' => 'مقادیر ویژگی',
            ]);

        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        // attr id
        $attrId = session()->getFlash('product-attr-val-curr-a-id', null, false);
        if (!empty($attrId)) {
            if (0 == $attrModel->count('id=:id', ['id' => $attrId])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-product-attr-val', 'ویژگی انتخاب شده نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-add-product-attr-val', 'ویژگی انتخاب شده نامعتبر است.');
        }

        if ($validator->getStatus()) {
            // attr val
            $validator
                ->setFields(['inp-add-product-attr-val'])
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
            $attrId = session()->getFlash('product-attr-val-curr-a-id', null);
            $attrVal = input()->post('inp-add-product-attr-val', '')->getValue();

            if (is_null($attrId)) return false;

            return $attrModel->assignValueToAttr($xss->xss_clean($attrId), $xss->xss_clean($attrVal));
        } catch (\Exception $e) {
            return false;
        }
    }
}
