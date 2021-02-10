<?php

namespace App\Logic\Forms\Admin\Product;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ColorModel;
use App\Logic\Models\FestivalModel;
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

class EditProductForm implements IPageForm
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
                'inp-edit-product-img' => 'تصویر شاخض',
                'inp-edit-product-title' => 'عنوان',
                'inp-edit-product-simple-properties' => 'ویژگی‌های سریع',
                'inp-edit-product-keywords' => 'کلمات کلیدی',
                'inp-edit-product-brand' => 'برند',
                'inp-edit-product-category' => 'دسته‌بندی',
                'inp-edit-product-unit' => 'واحد کالا',
                'inp-edit-product-stock-count.*' => 'تعداد کالا',
                'inp-edit-product-max-count.*' => 'نعداد در سبد خرید',
                'inp-edit-product-color.*' => 'رنگ',
                'inp-edit-product-size.*' => 'سایز',
                'inp-edit-product-guarantee.*' => 'گارانتی',
                'inp-edit-product-price.*' => 'قیمت کالا',
                'inp-edit-product-discount-price.*' => 'قیمت تخفیف کالا',
                'inp-edit-product-discount-date.*' => 'تاریخ اتمام تخفیف',
                'inp-edit-product-product-availability.*' => 'موجودی کالا',
                'inp-edit-product-gallery-img.*' => 'تصویر گالری',
                'inp-edit-product-desc' => 'توضیحات',
                'inp-edit-product-properties' => 'ویژگی‌ها',
                'inp-edit-product-alert-product' => 'تعداد کالا برای هشدار',
                'inp-edit-product-related.*' => 'محصولات مرتبط',
            ])
            ->setOptionalFields([
                'inp-edit-product-size.*',
                'inp-edit-product-guarantee.*',
                'inp-edit-product-price.*',
                'inp-edit-product-discount-price.*',
                'inp-edit-product-discount-date.*',
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
                if (0 === $categoryModel->count('id=:id AND level=:lvl', ['id' => $value->getValue(), 'lvl' => MAX_CATEGORY_LEVEL])) {
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
                if (0 === $unitModel->count('id=:id', ['id' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // stock count, max cart count, price, discount price
        $validator
            ->setFields([
                'inp-edit-product-stock-count.*',
                'inp-edit-product-max-count.*',
                'inp-edit-product-price.*',
                'inp-edit-product-discount-price.*'
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();
        // alert product
        $validator
            ->setFields('inp-edit-product-alert-product')
            ->isInteger();
        // properties
        $validator
            ->setFields('inp-edit-product-properties')
            ->required();
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
        $stockCounts = input()->post('inp-edit-product-stock-count.*');
        if (!is_array($stockCounts)) {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-product-stock-count.*', $validator->getFieldAlias('inp-edit-product-stock-count.*') . ' به درستی وارد نشده است.');
        } else {
            $i = 0;
            $validator
                ->setFields('inp-edit-product-max-count.*')
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
        $prices = input()->post('inp-edit-product-price.*');
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

                if (0 === $colorModel->count('hex=:hex', ['hex' => $value->getValue()])) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده وجود ندارد.');
        // discount until date
        $validator
            ->setFields('inp-edit-product-discount-date.*')
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
                    ->setError('inp-edit-product-gallery-img.*', 'ورود حداقل یک تصویر معتبر برای گالری تصاویر اجباری است.');
            } else {
                session()->setFlash('image-gallery-object-edit', $gallery);
            }

            // create products object
            $productObj = $productUtil->createProductObject();
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
                $validator->setError('inp-edit-product-title', 'شناسه محصول نامعتبر است.');
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
            $id = session()->getFlash('product-curr-id', null);
            $products = session()->getFlash('all-products-object-edit');
            $pub = input()->post('inp-edit-product-status', '')->getValue();
            $availability = input()->post('inp-edit-product-availability', '')->getValue();
            $special = input()->post('inp-edit-product-special', '')->getValue();
            $returnable = input()->post('inp-edit-product-returnable', '')->getValue();
            $commenting = input()->post('inp-edit-product-commenting', '')->getValue();
            $image = input()->post('inp-edit-product-img', '')->getValue();
            $title = input()->post('inp-edit-product-title', '')->getValue();
            $simpleProp = input()->post('inp-edit-product-simple-properties', '')->getValue();
            $keywords = input()->post('inp-edit-product-keywords', '')->getValue();
            $brand = input()->post('inp-edit-product-brand', '')->getValue();
            $category = input()->post('inp-edit-product-category', '')->getValue();
            $properties = input()->post('inp-edit-product-properties', '')->getValue();
            $desc = input()->post('inp-edit-product-desc', '')->getValue();
            $alertProduct = input()->post('inp-edit-product-alert-product', '')->getValue();
            $relatedProducts = input()->post('inp-edit-product-related');
            // this is gallery
            $gallery = session()->getFlash('image-gallery-object-edit');

            // get unit title and sign
            $unit = input()->post('inp-edit-product-unit', '')->getValue();
            $unitInfo = $unitModel->getFirst(['title', 'sign'], 'id=:id', ['id' => $unit]);

            // insert all products and main product information
            return $productModel->updateProduct($id, [
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
                'is_returnable' => is_value_checked($availability) ? DB_YES : DB_NO,
                'allow_commenting' => is_value_checked($commenting) ? DB_YES : DB_NO,
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ], $gallery, $products, $relatedProducts ?: []);
        } catch (\Exception $e) {
            return false;
        }
    }
}