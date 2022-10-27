<?php

namespace App\Logic\Forms\Admin\Product\Attribute;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditProductAttrForm implements IPageForm
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
                'inp-edit-product-attr-title' => 'عنوان ویژگی',
                'inp-edit-product-attr-type' => 'نوع ویژگی',
            ]);

        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        $id = session()->getFlash('product-attr-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $attrModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-product-attr-title', 'شناسه ویژگی جستجو نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-product-attr-title', 'شناسه ویژگی جستجو نامعتبر است.');
        }

        if ($validator->getStatus()) {
            // title
            $validator
                ->setFields('inp-edit-product-attr-title')
                ->required()
                ->lessThanEqualLength(250)
                ->custom(function (FormValue $value) use ($attrModel, $id) {
                    if (
                        0 !== $attrModel->count('title=:title AND id<>:id', ['title' => trim($value->getValue()), 'id' => $id])
                    ) {
                        return false;
                    }
                    return true;
                }, 'ویژگی با این عنوان وجود دارد.');
            // type
            $validator
                ->setFields('inp-edit-product-attr-type')
                ->required()
                ->isIn(
                    [PRODUCT_SIDE_SEARCH_ATTR_TYPE_MULTI_SELECT, PRODUCT_SIDE_SEARCH_ATTR_TYPE_SINGLE_SELECT],
                    '{alias} ' . 'انتخاب شده نامعتبر است.'
                );
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
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $title = input()->post('inp-edit-product-attr-title', '')->getValue();
            $type = input()->post('inp-edit-product-attr-type', '')->getValue();
            $id = session()->getFlash('product-attr-curr-id', null);

            if (is_null($id)) return false;

            return $attrModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'type' => $type,
                'updated_at' => time(),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
