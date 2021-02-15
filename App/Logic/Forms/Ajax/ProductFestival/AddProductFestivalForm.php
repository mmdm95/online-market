<?php

namespace App\Logic\Forms\Ajax\ProductFestival;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\FestivalModel;
use App\Logic\Models\ProductFestivalModel;
use App\Logic\Models\ProductModel;
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

class AddProductFestivalForm implements IPageForm
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
                'inp-add-product-festival-product' => 'شناسه محصول',
                'inp-add-product-festival-percent' => 'درصد تخفیف',
            ])
            ->toEnglishValue(true, true);

        // product
        $validator
            ->setFields('inp-add-product-festival-product')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var ProductModel $productModel
                 */
                $productModel = container()->get(ProductModel::class);

                if (
                    $productModel->count('id=:id', ['id' => trim($value->getValue())]) !== 0
                ) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'نامعتبر است.');
        // percentage
        $validator
            ->setFields('inp-add-product-festival-percent')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->between(0, 100);

        $fId = session()->getFlash('product-festival-f-id', null, false);
        if (!empty($fId)) {
            /**
             * @var FestivalModel $festivalModel
             */
            $festivalModel = container()->get(FestivalModel::class);

            if (0 === $festivalModel->count('id=:id', ['id' => $fId])) {
                $validator->setError('inp-add-product-festival-product', 'شناسه جشنواره نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-add-product-festival-product', 'شناسه جشنواره نامعتبر است.');
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
            $fId = session()->getFlash('product-festival-f-id', null);
            $product = input()->post('inp-add-product-festival-product', '')->getValue();
            $percent = input()->post('inp-add-product-festival-percent', '')->getValue();

            if (0 !== $festivalModel->count('product_id=:pId AND festival_id=fId', ['pId' => $xss->xss_clean($product), 'fId' => $xss->xss_clean($fId)])) {
                return true;
            }

            $res = $festivalModel->insert([
                'product_id' => $xss->xss_clean($product),
                'festival_id' => $xss->xss_clean($fId),
                'discount' => $xss->xss_clean($percent),
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}