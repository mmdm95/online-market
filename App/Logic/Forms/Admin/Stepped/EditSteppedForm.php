<?php

namespace App\Logic\Forms\Admin\Stepped;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ProductModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditSteppedForm implements IPageForm
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
                'inp-edit-stepped-min-count' => 'حداقل تعداد در سبد خرید',
                'inp-edit-stepped-max-count' => 'حداثر تعداد در سبد خرید',
                'inp-edit-stepped-price' => 'قیمت',
                'inp-edit-stepped-discounted-price' => 'قیمت با تخفیف',
            ])
            ->toEnglishValue(true, true)
            ->setOptionalFields([
                'inp-edit-stepped-min-count',
                'inp-edit-stepped-max-count',
            ]);

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // price & discount
        $validator
            ->setFields([
                'inp-edit-stepped-price',
                'inp-edit-stepped-discounted-price'
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();

        $min = $validator->getFieldValue('inp-edit-stepped-min-count');
        $max = $validator->getFieldValue('inp-edit-stepped-max-count');

        $prevInfo = session()->getFlash('stepped-edit-prev-info', []);

        if (!is_null($min)) {
            if ((($prevInfo['min_count'] ?? null) != $min) && $productModel->getSteppedPricesCount('min_count=:min', ['min' => $min]) > 0) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-stepped-min-count', 'مقدار برابر با مقدار وارد شده ' . $validator->getFieldAlias('inp-edit-stepped-min-count') . ' وجود دارد.');
            }
        }
        if (!is_null($max)) {
            if ((($prevInfo['max_count'] ?? null) != $max) && $productModel->getSteppedPricesCount('max_count=:max', ['max' => $max]) > 0) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-stepped-max-count', 'مقدار برابر با مقدار وارد شده ' . $validator->getFieldAlias('inp-edit-stepped-max-count') . ' وجود دارد.');
            }
        }
        if (is_null($min) && is_null($max)) {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-stepped-min-count', 'وارد کردن یکی از ' . $validator->getFieldAlias('inp-edit-stepped-min-count') . ' یا ' . $validator->getFieldAlias('inp-edit-stepped-max-count') . ' الزامی است.');
        }
        if (!is_null($min) && !is_null($max) && $min > $max) {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-stepped-min-count', $validator->getFieldAlias('inp-edit-stepped-min-count') . ' باید از ' . $validator->getFieldAlias('inp-edit-stepped-max-count') . ' بیشتر باشد.');
        }

        $code = session()->getFlash('stepped-edit-curr-code', null, false);
        if (!empty($code)) {
            $count = $productModel->getProductPropertyWithInfoCount('code=:code', ['code' => $code]);
            if (0 === $count) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-stepped-price', 'شناسه کالای قیمت پلکانی نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-stepped-price', 'شناسه قیمت پلکانی نامعتبر است.');
        }

        $id = session()->getFlash('product-stepped-curr-id', null, false);
        if (!empty($id)) {
            $count = $productModel->getSteppedPricesCount('id=:id', ['id' => $id]);
            if (0 === $count) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-stepped-price', 'شناسه قیمت پلکانی نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-stepped-price', 'شناسه قیمت پلکانی نامعتبر است.');
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
            $code = session()->getFlash('stepped-edit-curr-code', null);
            $id = session()->getFlash('product-stepped-curr-id', null);
            $min = input()->post('inp-edit-stepped-min-count', '')->getValue();
            $max = input()->post('inp-edit-stepped-max-count', '')->getValue();
            $price = input()->post('inp-edit-stepped-price', '')->getValue();
            $discount = input()->post('inp-edit-stepped-discounted-price', '')->getValue();
            if (is_null($code) || is_null($id)) return false;

            return $productModel->updateSteppedPrice([
                'min_count' => $xss->xss_clean(trim($min)),
                'max_count' => $xss->xss_clean(trim($max)),
                'price' => $xss->xss_clean(trim($price)),
                'discounted_price' => $xss->xss_clean(trim($discount)),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id AND product_code=:code', ['id' => $id, 'code' => $code]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
