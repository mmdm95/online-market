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

class AddSteppedForm implements IPageForm
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
                'inp-add-stepped-min-count' => 'حداقل تعداد در سبد خرید',
                'inp-add-stepped-max-count' => 'حداثر تعداد در سبد خرید',
                'inp-add-stepped-price' => 'قیمت',
                'inp-add-stepped-discounted-price' => 'قیمت با تخفیف',
            ])
            ->toEnglishValue(true, true)
            ->setOptionalFields([
                'inp-add-stepped-min-count',
                'inp-add-stepped-max-count',
            ]);

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // price & discount
        $validator
            ->setFields([
                'inp-add-stepped-price',
                'inp-add-stepped-discounted-price'
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();

        $min = $validator->getFieldValue('inp-add-stepped-min-count');
        $max = $validator->getFieldValue('inp-add-stepped-max-count');

        if (!is_null($min)) {
            if ($productModel->getSteppedPricesCount('min_count=:min', ['min' => $min]) > 0) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-stepped-min-count', 'مقدار برابر با مقدار وارد شده ' . $validator->getFieldAlias('inp-add-stepped-min-count') . ' وجود دارد.');
            }
        }
        if (!is_null($max)) {
            if ($productModel->getSteppedPricesCount('max_count=:max', ['max' => $max]) > 0) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-stepped-max-count', 'مقدار برابر با مقدار وارد شده ' . $validator->getFieldAlias('inp-add-stepped-max-count') . ' وجود دارد.');
            }
        }
        if (is_null($min) && is_null($max)) {
            $validator
                ->setStatus(false)
                ->setError('inp-add-stepped-min-count', 'وارد کردن یکی از ' . $validator->getFieldAlias('inp-add-stepped-min-count') . ' یا ' . $validator->getFieldAlias('inp-add-stepped-max-count') . ' الزامی است.');
        }
        if (!is_null($min) && !is_null($max) && $min > $max) {
            $validator
                ->setStatus(false)
                ->setError('inp-add-stepped-min-count', $validator->getFieldAlias('inp-add-stepped-min-count') . ' باید از ' . $validator->getFieldAlias('inp-add-stepped-max-count') . ' بیشتر باشد.');
        }

        $code = session()->getFlash('stepped-add-curr-code', null, false);
        if (!empty($code)) {
            $count = $productModel->getProductPropertyWithInfoCount('code=:code', ['code' => $code]);
            if (0 === $count) {
                $validator->setError('inp-add-stepped-price', 'شناسه کالای قیمت پلکانی نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-add-stepped-price', 'شناسه قیمت پلکانی نامعتبر است.');
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
            $code = session()->getFlash('stepped-add-curr-code', null);
            $min = input()->post('inp-add-stepped-min-count', '')->getValue();
            $max = input()->post('inp-add-stepped-max-count', '')->getValue();
            $price = input()->post('inp-add-stepped-price', '')->getValue();
            $discount = input()->post('inp-add-stepped-discounted-price', '')->getValue();

            return $productModel->insertSteppedPrice([
                'product_code' => $code,
                'min_count' => $xss->xss_clean(trim($min)),
                'max_count' => $xss->xss_clean(trim($max)),
                'price' => $xss->xss_clean(trim($price)),
                'discounted_price' => $xss->xss_clean(trim($discount)),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
