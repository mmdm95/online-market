<?php

namespace App\Logic\Forms\Admin\Product;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ProductModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class BatchEditProductPriceForm implements IPageForm
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
                'inp-edit-product-increase-price' => 'درصد تغییر قیمت',
                'inp-edit-product-increase-price-radio' => 'نوع تغییر قیمت',
            ]);

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // check for all ids and if there is no product with ids, give an error
        $ids = session()->getFlash('product-curr-ids', '');
        if (!is_string($ids) || empty($ids)) {
            $validator->setError('p-ids', 'محصولات انتخاب شده نامعتبر می‌باشند!');
        }
        $ids = explode('/', str_replace('\\', '/', $ids));
        $newIds = [];
        foreach ($ids as $id) {
            // $id + 0 cast it to integer
            if (is_integer($id + 0) && !in_array($id, $newIds) && $productModel->count('id=:id', ['id' => $id])) {
                $newIds[] = $id;
            }
        }
        if (empty($newIds)) {
            $validator->setError('p-ids', 'محصولات انتخاب شده نامعتبر می‌باشند!')->setStatus(false);
        }

        if ($validator->getStatus()) {
            session()->setFlash('product-curr-ids', $newIds);
            // price
            $validator
                ->setFields('inp-edit-product-increase-price')
                ->isInteger()
                ->between(1, 99);
            // change type
            $validator
                ->setFields('inp-edit-product-increase-price-radio')
                ->isIn([1, 2], '{alias} ' . 'انتخاب شده نامعبتر است.');
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
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $ids = session()->getFlash('product-curr-ids');
            if (is_null($ids)) return false;

            $changePriceType = input()->post('inp-edit-product-increase-price-radio', 1)->getValue();
            $price = input()->post('inp-edit-product-increase-price', '')->getValue();
            $price = $xss->xss_clean(intval($price));

            if (!is_integer($price)) return false;
            //
            $updateArr = [
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ];
            //
            $rawUpdateArr = [];
            if ($changePriceType == 2) {
                $st1 = "price - (price * {$price} / 100)";
                $st2 = "discounted_price - (discounted_price * {$price} / 100)";
                $rawUpdateArr['price'] = "(CASE WHEN ({$st1} > 0) THEN {$st1} ELSE price END)";
                $rawUpdateArr['discounted_price'] = "(CASE WHEN ({$st2} > 0) THEN {$st2} ELSE discounted_price END)";
            } else {
                $rawUpdateArr['price'] = "price + (price * {$price} / 100)";
                $rawUpdateArr['discounted_price'] = "discounted_price + (discounted_price * {$price} / 100)";
            }

            // insert all products and main product information
            return $productModel->updateBatchProductsPrice($ids, $rawUpdateArr, $updateArr);
        } catch (\Exception $e) {
            return false;
        }
    }
}
