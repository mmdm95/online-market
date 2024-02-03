<?php

namespace App\Logic\Forms\Admin\Product;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ColorModel;
use App\Logic\Models\ProductAttributeModel;
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
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class EditProductForm implements IPageForm
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
                'inp-edit-product-img' => 'تصویر شاخص',
                'inp-edit-product-title' => 'عنوان',
                'inp-edit-product-simple-properties' => 'ویژگی‌های سریع',
                'inp-edit-product-keywords' => 'کلمات کلیدی',
                'inp-edit-product-brand' => 'برند',
                'inp-edit-product-category' => 'دسته‌بندی',
                'inp-edit-product-unit' => 'واحد کالا',
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
                'inp-edit-product-gallery-img.*' => 'تصویر گالری',
                'inp-edit-product-desc' => 'توضیحات',
                'inp-edit-product-alert-product' => 'تعداد کالا برای هشدار',
                'inp-edit-product-related.*' => 'محصولات مرتبط',
            ])
            ->setOptionalFields([
                'inp-edit-product-unit',
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
                'inp-edit-product-desc',
                'inp-edit-product-alert-product',
                'inp-edit-product-related.*',
            ]);

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // image
        $validator
            ->setFields('inp-edit-product-img')
            ->imageExists();
        // title
        $validator
            ->setFields('inp-edit-product-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250)
            ->custom(function (FormValue $value) use ($productModel) {
                $title = session()->getFlash('product-prev-title');
                if (
                    $title != trim($value->getValue()) &&
                    0 !== $productModel->count('title=:title', ['title' => trim($value->getValue())])
                ) {
                    return false;
                }
                return true;
            }, 'محصول با این عنوان وجود دارد.');
        // brand
        $validator
            ->setFields('inp-edit-product-brand')
            ->custom(function (FormValue $value) {
                /**
                 * @var BrandModel $brandModel
                 */
                $brandModel = container()->get(BrandModel::class);
                if (0 === $brandModel->count('id=:id', ['id' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // category
        $validator
            ->setFields('inp-edit-product-category')
            ->custom(function (FormValue $value) {
                /**
                 * @var CategoryModel $categoryModel
                 */
                $categoryModel = container()->get(CategoryModel::class);
                if (0 === $categoryModel->count('id=:id', ['id' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // unit
        $validator
            ->setFields('inp-edit-product-unit')
            ->custom(function (FormValue $value) {
                /**
                 * @var UnitModel $unitModel
                 */
                $unitModel = container()->get(UnitModel::class);
                if (trim($value->getValue()) != DEFAULT_OPTION_VALUE && 0 === $unitModel->count('id=:id', ['id' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
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
        // alert product
        $validator
            ->setFields('inp-edit-product-alert-product')
            ->isInteger()
            ->greaterThan(0);
        // related products
        $validator
            ->setFields('inp-edit-product-related.*')
            ->custom(function (FormValue $value) use ($productModel) {
                if (0 === $productModel->count('id=:id', ['id' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده نامعتبر است!');

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

                if (trim($value->getValue()) != DEFAULT_OPTION_VALUE && 0 === $colorModel->count('id=:id', ['id' => $value->getValue()])) {
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

        // properties
        $properties = input()->post('inp-item-product-properties');
        $subProperties = input()->post('inp-item-product-sub-properties');
        /**
         * @var ProductUtil $productUtil
         */
        $productUtil = container()->get(ProductUtil::class);
        $theProperties = $productUtil->assembleProductProperties($properties, $subProperties);

        session()->setFlash('product-properties-in-edit-assembled', $theProperties);

        if (!count($validator->getUniqueErrors())) {
            // create gallery object
            $gallery = $productUtil->createGalleryArray(true);
            if (!count($gallery)) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-product-gallery-img.*', 'ورود حداقل یک تصویر معتبر برای گالری تصاویر اجباری است.');
            } else {
                session()->setFlash('image-gallery-object-edit', $gallery);
            }

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

        $id = session()->getFlash('product-curr-id', null, false);
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
        /**
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('product-curr-id', null);
            $products = session()->getFlash('all-products-object-edit');
            $pub = input()->post('inp-edit-product-status', '')->getValue();
            $availability = input()->post('inp-edit-product-availability', '')->getValue();
            $special = input()->post('inp-edit-product-special', '')->getValue();
//            $returnable = input()->post('inp-edit-product-returnable', '')->getValue();
            $commenting = input()->post('inp-edit-product-commenting', '')->getValue();
            $comingSoon = input()->post('inp-edit-product-coming-soon', '')->getValue();
            $callForMore = input()->post('inp-edit-product-call-for-more', '')->getValue();
            $image = input()->post('inp-edit-product-img', '')->getValue();
            $title = input()->post('inp-edit-product-title', '')->getValue();
            $simpleProp = input()->post('inp-edit-product-simple-properties', '')->getValue();
            $keywords = input()->post('inp-edit-product-keywords', '')->getValue();
            $brand = input()->post('inp-edit-product-brand', '')->getValue();
            $category = input()->post('inp-edit-product-category', '')->getValue();
            $desc = input()->post('inp-edit-product-desc', '')->getValue();
            $alertProduct = input()->post('inp-edit-product-alert-product', '')->getValue();
            $relatedProducts = input()->post('inp-edit-product-related');
            // this is gallery
            $gallery = session()->getFlash('image-gallery-object-edit');

            $theProperties = json_encode(session()->getFlash('product-properties-in-edit-assembled') ?: []);
            if (is_null($theProperties)) return false;

            //
            $updateArr = [
                'title' => $xss->xss_clean(trim($title)),
                'fa_title' => $xss->xss_clean(StringUtil::toPersian(trim($title))),
                'slug' => $xss->xss_clean(StringUtil::slugify(trim($title))),
                'image' => $xss->xss_clean(get_image_name($image)),
                'brand_id' => $brand,
                'category_id' => $category,
                'body' => $xss->xss_clean($desc) ?: null,
                'properties' => $xss->xss_clean($theProperties) ?: null,
                'baby_property' => $xss->xss_clean($simpleProp) ?: null,
                'keywords' => $xss->xss_clean($keywords) ?: null,
                'min_product_alert' => $xss->xss_clean($alertProduct) ?: null,
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'is_special' => is_value_checked($special) ? DB_YES : DB_NO,
                'is_available' => is_value_checked($availability) ? DB_YES : DB_NO,
//                'is_returnable' => is_value_checked($returnable) ? DB_YES : DB_NO,
                'is_returnable' => DB_YES,
                'allow_commenting' => is_value_checked($commenting) ? DB_YES : DB_NO,
                'show_coming_soon' => is_value_checked($comingSoon) ? DB_YES : DB_NO,
                'call_for_more' => is_value_checked($callForMore) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ];

            // get unit title and sign
            $unit = input()->post('inp-edit-product-unit', '')->getValue();
            $unitInfo = $unitModel->getFirst(['title', 'sign'], 'id=:id', ['id' => $unit]);

            if (count($unitInfo)) {
                $updateArr['unit_title'] = $unitInfo['title'];
                $updateArr['unit_sign'] = $unitInfo['sign'];
            }

            // insert all products and main product information
            $res = $productModel->updateProduct(
                $id,
                $updateArr,
                $gallery,
                $products,
                is_array($relatedProducts) ? $relatedProducts : []
            );

            // remove all product's attribute values if category has been changed
            $prevCategory = session()->getFlash('product-prev-category', null);
            if ($res && !empty($prevCategory)) {
                if ($prevCategory != $category) {
                    /**
                     * @var ProductAttributeModel $attrModel
                     */
                    $attrModel = container()->get(ProductAttributeModel::class);
                    $attrModel->removeAllProductAttrValues($id);
                }
            }

            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}
