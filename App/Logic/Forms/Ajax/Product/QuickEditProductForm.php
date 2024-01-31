<?php

namespace App\Logic\Forms\Ajax\Product;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ColorModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UnitModel;
use App\Logic\Utils\ProductUtil;
use App\Logic\Validations\ExtendedValidator;
use Pecee\Http\Input\InputItem;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Form\Validations\TimestampValidation;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class QuickEditProductForm implements IPageForm
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
                'inp-edit-product-stock-count.*' => 'تعداد کالا',
                'inp-edit-product-max-count.*' => 'تعداد در سبد خرید',
                'inp-edit-product-color.*' => 'رنگ',
                'inp-edit-product-size.*' => 'سایز',
                'inp-edit-product-guarantee.*' => 'گارانتی',
                'inp-edit-product-weight.*' => 'وزن',
                'inp-edit-product-price.*' => 'قیمت کالا',
                'inp-edit-product-discount-price.*' => 'قیمت تخفیف کالا',
                'inp-edit-product-discount-date-from.*' => 'تاریخ شروع تخفیف',
                'inp-edit-product-discount-date.*' => 'تاریخ اتمام تخفیف',
                'inp-edit-product-separate-consignment.*' => 'مرسوله مجزا',
                'inp-edit-product-product-availability.*' => 'موجودی کالا',
            ])
            ->setOptionalFields([
                'inp-edit-product-size.*',
                'inp-edit-product-color.*',
                'inp-edit-product-guarantee.*',
                'inp-edit-product-price.*',
                'inp-edit-product-discount-price.*',
                'inp-edit-product-discount-date-from.*',
                'inp-edit-product-discount-date.*',
                'inp-edit-product-consider-discount-date-from.*',
                'inp-edit-product-consider-discount-date.*',
                'inp-edit-product-separate-consignment.*',
                'inp-edit-product-product-availability.*',
            ]);

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // stock count, max cart count, price, discount price, weight
        $validator
            ->setFields([
                'inp-edit-product-stock-count.*',
                'inp-edit-product-max-count.*',
                'inp-edit-product-price.*',
                'inp-edit-product-discount-price.*',
                'inp-edit-product-weight.*',
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger()
            ->greaterThan(0);

        // check each stock count with max cart count
        $stockCounts = input()->post('inp-edit-product-stock-count');
        if (!is_array($stockCounts)) {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-product-stock-count.*', $validator->getFieldAlias('inp-edit-product-stock-count.*') . ' به درستی وارد نشده است.');
        } else {
            $i = 0;
            $validator
                ->setFields('inp-edit-product-max-count')
                ->custom(function (FormValue $value) use ($stockCounts, &$i) {
                    /**
                     * @var InputItem $currStock
                     */
                    $currStock = $stockCounts[$i++] ?? null;

                    if (is_null($currStock) || (int)$value->getValue() > (int)$currStock->getValue()) {
                        return false;
                    }
                    return true;
                }, '{alias} ' . 'از ' . $validator->getFieldAlias('inp-edit-product-stock-count.*') . ' بیشتر است.');
        }
        // check each price with discount price
        $prices = input()->post('inp-edit-product-price');
        if (!is_array($prices)) {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-product-price.*', $validator->getFieldAlias('inp-edit-product-price.*') . ' به درستی وارد نشده است.');
        } else {
            $i = 0;
            $validator
                ->setFields('inp-edit-product-discount-price.*')
                ->custom(function (FormValue $value) use ($prices, &$i) {
                    /**
                     * @var InputItem $currPrice
                     */
                    $currPrice = $prices[$i++] ?? null;

                    if (is_null($currPrice) || (int)$value->getValue() > (int)$currPrice->getValue()) {
                        return false;
                    }
                    return true;
                }, '{alias} ' . 'از ' . $validator->getFieldAlias('inp-edit-product-price.*') . ' بیشتر است.');
        }
        // color
        $validator
            ->setFields('inp-edit-product-color.*')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var ColorModel $colorModel
                 */
                $colorModel = container()->get(ColorModel::class);

                if (trim($value->getValue()) != DEFAULT_OPTION_VALUE && 0 === $colorModel->count('hex=:hex', ['hex' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده وجود ندارد.');

        // discount from date
        $allowedDatesFrom = input()->post('inp-edit-product-consider-discount-date-from');
        $counter = 0;
        $discountDatesFrom = input()->post('inp-edit-product-discount-date-from');
        if (is_array($allowedDatesFrom) && is_array($discountDatesFrom)) {
            /**
             * @var InputItem $allow
             */
            foreach ($allowedDatesFrom as $allow) {
                if (is_value_checked($allow->getValue()) && isset($discountDatesFrom[$counter])) {
                    $timestampRule = new TimestampValidation();
                    if (!$timestampRule->validate($discountDatesFrom[$counter]->getValue()) ||
                        (
                            '' !== $discountDatesFrom[$counter]->getValue() &&
                            false === date(DEFAULT_TIME_FORMAT, $discountDatesFrom[$counter]->getValue())
                        )
                    ) {
                        $validator
                            ->setStatus(false)
                            ->setError(
                                'inp-edit-product-discount-date-from.*',
                                $validator->getFieldAlias('inp-edit-product-discount-date-from.*') . ' ' . 'یک زمان وارد شده نامعتبر است.'
                            );
                    }
                }
                ++$counter;
            }
        }

        // discount until date
        $allowedDates = input()->post('inp-edit-product-consider-discount-date');
        $counter = 0;
        $discountDates = input()->post('inp-edit-product-discount-date');
        if (is_array($allowedDates) && is_array($discountDates)) {
            /**
             * @var InputItem $allow
             */
            foreach ($allowedDates as $allow) {
                if (is_value_checked($allow->getValue()) && isset($discountDates[$counter])) {
                    $timestampRule = new TimestampValidation();
                    if (!$timestampRule->validate($discountDates[$counter]->getValue()) ||
                        (
                            '' !== $discountDates[$counter]->getValue() &&
                            false === date(DEFAULT_TIME_FORMAT, $discountDates[$counter]->getValue())
                        )
                    ) {
                        $validator
                            ->setStatus(false)
                            ->setError(
                                'inp-edit-product-discount-date.*',
                                $validator->getFieldAlias('inp-edit-product-discount-date.*') . ' ' . 'یک زمان وارد شده نامعتبر است.'
                            );
                    }
                }
                ++$counter;
            }
        }

        if (!count($validator->getUniqueErrors())) {
            /**
             * @var ProductUtil $productUtil
             */
            $productUtil = container()->get(ProductUtil::class);

            // create products object
            $productObj = $productUtil->createProductObject(true);
            if (!count($productObj)) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-product-price', 'ورود حداقل یک محصول معتبر اجباری است.');
            } else {
                session()->setFlash('all-products-object-edit', $productObj);
            }
        }

        $id = session()->getFlash('product-quick-edit-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $productModel->count('id=:id', ['id' => $id])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-product-title', 'شناسه محصول نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-product-title', 'شناسه محصول نامعتبر است.');
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

        try {
            $id = session()->getFlash('product-quick-edit-curr-id', null);
            $products = session()->getFlash('all-products-object-edit');

            if (is_null($id)) return false;

            return $productModel->updateProductProperty($id, $products);
        } catch (\Exception $e) {
            return false;
        }
    }
}
