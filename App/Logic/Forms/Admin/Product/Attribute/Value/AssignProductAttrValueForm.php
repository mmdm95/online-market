<?php

namespace App\Logic\Forms\Admin\Product\Attribute\Value;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BaseModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class AssignProductAttrValueForm implements IPageForm
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

        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        $category = session()->getFlash('product-prev-category', null, false);
        $attrs = $attrModel->getProductAttrValuesOfCategory(
            $category,
            ['pav.attr_val', 'pav.id AS val_id', 'pa.id', 'pa.type', 'pa.title']
        );

        // aliases
        $aliases = [];
        foreach ($attrs as $attr) {
            $aliases['inp-edit-attr_' . $attr['id']] = 'ویژگی ' . '[' . $attr['title'] . ']';
        }
        $validator->setFieldsAlias($aliases);

        // dynamic validation
        $assembled = [];
        foreach ($aliases as $name => $alias) {
            $validator
                ->setFields($name)
                ->required()
                ->custom(function (FormValue $value) use ($attrModel) {
                    if (
                        0 == $attrModel->count('id=:id', ['id' => trim($value->getValue())], BaseModel::TBL_PRODUCT_ATTR_VALUES)
                    ) {
                        return false;
                    }
                    return true;
                }, '{alias} ' . 'انتخاب شده نامعتبر است.');
            //-----
            $v = input()->post($name, null);
            if (!is_null($v->getValue()) && $v->getValue() != DEFAULT_OPTION_VALUE) {
                $assembled[] = $v->getValue();
            }
            session()->setFlash('product-assembled-attrs', $assembled);
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

        try {
            $assembled = session()->getFlash('product-assembled-attrs', null);
            $id = session()->getFlash('product-curr-id', null);

            if (is_null($assembled) || is_null($id)) return false;

            return $attrModel->assignProductAttrValues($id, $assembled);
        } catch (\Exception $e) {
            return false;
        }
    }
}
