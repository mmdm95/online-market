<?php

namespace App\Logic\Forms\Admin\Product;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ColorModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UnitModel;
use App\Logic\Utils\ProductUtil;
use App\Logic\Validations\ExtendedValidator;
use Pecee\Http\Input\InputItem;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddProductForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws FormException
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
                'inp-add-product-img' => 'تصویر شاخض',
                'inp-add-product-title' => 'عنوان',
                'inp-add-product-simple-properties' => 'ویژگی‌های سریع',
                'inp-add-product-keywords' => 'کلمات کلیدی',
                'inp-add-product-brand' => 'برند',
                'inp-add-product-category' => 'دسته‌بندی',
                'inp-add-product-unit' => 'واحد کالا',
                'inp-add-product-stock-count.*' => 'تعداد کالا',
                'inp-add-product-max-count.*' => 'نعداد در سبد خرید',
                'inp-add-product-color.*' => 'رنگ',
                'inp-add-product-size.*' => 'سایز',
                'inp-add-product-guarantee.*' => 'گارانتی',
                'inp-add-product-price.*' => 'قیمت کالا',
                'inp-add-product-discount-price.*' => 'قیمت تخفیف کالا',
                'inp-add-product-discount-date.*' => 'تاریخ اتمام تخفیف',
                'inp-add-product-product-availability.*' => 'موجودی کالا',
                'inp-add-product-gallery-img.*' => 'تصویر گالری',
                'inp-add-product-desc' => 'توضیحات',
                'inp-add-product-properties' => 'ویژگی‌ها',
                'inp-add-product-alert-product' => 'تعداد کالا برای هشدار',
                'inp-add-product-related.*' => 'محصولات مرتبط',
            ])
            ->setOptionalFields([
                'inp-add-product-size.*',
                'inp-add-product-guarantee.*',
                'inp-add-product-price.*',
                'inp-add-product-discount-price.*',
                'inp-add-product-discount-date.*',
                'inp-add-product-product-availability.*',
                'inp-add-product-desc',
                'inp-add-product-alert-product',
                'inp-add-product-related.*',
            ]);

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // image
        $validator
            ->setFields('inp-add-product-img')
            ->imageExists();
        // title
        $validator
            ->setFields('inp-add-product-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250)
            ->custom(function (FormValue $value) use ($productModel) {
                if (0 !== $productModel->count('title=:title', ['title' => trim($value->getValue())])) {
                    return false;
                }
                return true;
            }, 'محصول با این عنوان وجود دارد.');
        // brand
        $validator
            ->setFields('inp-add-product-brand')
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
            ->setFields('inp-add-product-category')
            ->custom(function (FormValue $value) {
                /**
                 * @var CategoryModel $categoryModel
                 */
                $categoryModel = container()->get(CategoryModel::class);
                if (0 === $categoryModel->count('id=:id AND level=:lvl', ['id' => $value->getValue(), 'lvl' => MAX_CATEGORY_LEVEL])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // unit
        $validator
            ->setFields('inp-add-product-unit')
            ->custom(function (FormValue $value) {
                /**
                 * @var UnitModel $unitModel
                 */
                $unitModel = container()->get(UnitModel::class);
                if (0 === $unitModel->count('id=:id', ['id' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // stock count, max cart count, price, discount price
        $validator
            ->setFields([
                'inp-add-product-stock-count.*',
                'inp-add-product-max-count.*',
                'inp-add-product-price.*',
                'inp-add-product-discount-price.*'
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();
        // alert product
        $validator
            ->setFields('inp-add-product-alert-product')
            ->isInteger();
        // properties
        $validator
            ->setFields('inp-add-product-properties')
            ->required();
        // related products
        $validator
            ->setFields('inp-add-product-related.*')
            ->custom(function (FormValue $value) use ($productModel) {
                if (0 === $productModel->count('id=:id', ['id' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده نامعتبر است!');

        // check each stock count with max cart count
        $stockCounts = input()->post('inp-add-product-stock-count.*');
        if (!is_array($stockCounts)) {
            $validator
                ->setStatus(false)
                ->setError('inp-add-product-stock-count.*', $validator->getFieldAlias('inp-add-product-stock-count.*') . ' به درستی وارد نشده است.');
        } else {
            $i = 0;
            $validator
                ->setFields('inp-add-product-max-count.*')
                ->custom(function (FormValue $value) use ($stockCounts, &$i) {
                    /**
                     * @var InputItem $currStock
                     */
                    $currStock = $stockCounts[$i++] ?? null;

                    if (is_null($currStock) || (int)$value->getValue() > (int)$currStock->getValue()) {
                        return false;
                    }
                    return true;
                }, '{alias} ' . 'از ' . $validator->getFieldAlias('inp-add-product-stock-count.*') . ' بیشتر است.');
        }
        // check each price with discount price
        $prices = input()->post('inp-add-product-price.*');
        if (!is_array($prices)) {
            $validator
                ->setStatus(false)
                ->setError('inp-add-product-price.*', $validator->getFieldAlias('inp-add-product-price.*') . ' به درستی وارد نشده است.');
        } else {
            $i = 0;
            $validator
                ->setFields('inp-add-product-discount-price.*')
                ->custom(function (FormValue $value) use ($prices, &$i) {
                    /**
                     * @var InputItem $currPrice
                     */
                    $currPrice = $prices[$i++] ?? null;

                    if (is_null($currPrice) || (int)$value->getValue() > (int)$currPrice->getValue()) {
                        return false;
                    }
                    return true;
                }, '{alias} ' . 'از ' . $validator->getFieldAlias('inp-add-product-price.*') . ' بیشتر است.');
        }
        // color
        $validator
            ->setFields('inp-add-product-color.*')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var ColorModel $colorModel
                 */
                $colorModel = container()->get(ColorModel::class);

                if (0 === $colorModel->count('hex=:hex', ['hex' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده وجود ندارد.');
        // discount until date
        $validator
            ->setFields('inp-add-product-discount-date.*')
            ->timestamp()
            ->custom(function (FormValue $value) {
                if (false === date(DEFAULT_TIME_FORMAT, $value->getValue())) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'یک زمان وارد شده نامعتبر است.');

        if (!count($validator->getUniqueErrors())) {
            /**
             * @var ProductUtil $productUtil
             */
            $productUtil = container()->get(ProductUtil::class);

            // create gallery object
            $gallery = $productUtil->createGalleryArray();
            if (!count($gallery)) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-product-gallery-img.*', 'ورود حداقل یک تصویر معتبر برای گالری تصاویر اجباری است.');
            } else {
                session()->setFlash('image-gallery-object', $gallery);
            }

            // create products object
            $productObj = $productUtil->createProductObject();
            if (!count($productObj)) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-product-price', 'ورود حداقل یک محصول معتبر اجباری است.');
            } else {
                session()->setFlash('all-products-object', $productObj);
            }
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
            $products = session()->getFlash('all-products-object');
            $pub = input()->post('inp-add-product-status', '')->getValue();
            $availability = input()->post('inp-add-product-availability', '')->getValue();
            $special = input()->post('inp-add-product-special', '')->getValue();
            $returnable = input()->post('inp-add-product-returnable', '')->getValue();
            $commenting = input()->post('inp-add-product-commenting', '')->getValue();
            $image = input()->post('inp-add-product-img', '')->getValue();
            $title = input()->post('inp-add-product-title', '')->getValue();
            $simpleProp = input()->post('inp-add-product-simple-properties', '')->getValue();
            $keywords = input()->post('inp-add-product-keywords', '')->getValue();
            $brand = input()->post('inp-add-product-brand', '')->getValue();
            $category = input()->post('inp-add-product-category', '')->getValue();
            $properties = input()->post('inp-add-product-properties', '')->getValue();
            $desc = input()->post('inp-add-product-desc', '')->getValue();
            $alertProduct = input()->post('inp-add-product-alert-product', '')->getValue();
            $relatedProducts = input()->post('inp-add-product-related');
            // this is gallery
            $gallery = session()->getFlash('image-gallery-object');

            // get unit title and sign
            $unit = input()->post('inp-add-product-unit', '')->getValue();
            $unitInfo = $unitModel->getFirst(['title', 'sign'], 'id=:id', ['id' => $unit]);

            // insert all products and main product information
            return $productModel->insertProduct([
                'title' => $xss->xss_clean(trim($title)),
                'fa_title' => $xss->xss_clean(StringUtil::toPersian(trim($title))),
                'slug' => $xss->xss_clean(StringUtil::slugify(trim($title))),
                'image' => $xss->xss_clean(get_image_name($image)),
                'brand_id' => $brand,
                'category_id' => $category,
                'body' => $xss->xss_clean($desc) ?: null,
                'properties' => $xss->xss_clean($properties) ?: null,
                'baby_property' => $xss->xss_clean($simpleProp) ?: null,
                'unit_title' => $unitInfo['title'],
                'unit_sign' => $unitInfo['sign'],
                'keywords' => $xss->xss_clean($keywords) ?: null,
                'min_product_alert' => $xss->xss_clean($alertProduct) ?: null,
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'is_special' => is_value_checked($special) ? DB_YES : DB_NO,
                'is_available' => is_value_checked($availability) ? DB_YES : DB_NO,
                'is_returnable' => is_value_checked($returnable) ? DB_YES : DB_NO,
                'allow_commenting' => is_value_checked($commenting) ? DB_YES : DB_NO,
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ], $gallery, $products, $relatedProducts ?: []);
        } catch (\Exception $e) {
            return false;
        }
    }
}