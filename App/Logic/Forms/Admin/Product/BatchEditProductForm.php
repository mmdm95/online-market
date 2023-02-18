<?php

namespace App\Logic\Forms\Admin\Product;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UnitModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class BatchEditProductForm implements IPageForm
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
                'inp-edit-product-brand' => 'برند',
                'inp-edit-product-category' => 'دسته‌بندی',
                'inp-edit-product-unit' => 'واحد کالا',
                'inp-edit-product-alert-product' => 'تعداد کالا برای هشدار',
            ])
            ->setOptionalFields([
                'inp-edit-product-unit',
                'inp-edit-product-alert-product',
                'inp-edit-product-status-chk',
                'inp-edit-product-availability-chk',
                'inp-edit-product-special-chk',
                'inp-edit-product-returnable-chk',
                'inp-edit-product-commenting-chk',
                'inp-edit-product-coming-soon-chk',
                'inp-edit-product-call-for-more-chk',
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
            // $id + 0 make it integer
            if (is_integer($id + 0) && !in_array($id, $newIds) && $productModel->count('id=:id', ['id' => $id])) {
                $newIds[] = $id;
            }
        }
        if (empty($newIds)) {
            $validator->setError('p-ids', 'محصولات انتخاب شده نامعتبر می‌باشند!')->setStatus(false);
        }

        if ($validator->getStatus()) {
            session()->setFlash('product-curr-ids', $newIds);

            // brand
            $validator
                ->setFields('inp-edit-product-brand')
                ->custom(function (FormValue $value) {
                    /**
                     * @var BrandModel $brandModel
                     */
                    $brandModel = container()->get(BrandModel::class);
                    if (
                        trim($value->getValue()) != DEFAULT_OPTION_VALUE &&
                        0 === $brandModel->count('id=:id', ['id' => $value->getValue()])
                    ) {
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
                    if (
                        trim($value->getValue()) != DEFAULT_OPTION_VALUE &&
                        0 === $categoryModel->count('id=:id', ['id' => $value->getValue()])
                    ) {
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
                    if (
                        trim($value->getValue()) != DEFAULT_OPTION_VALUE &&
                        0 === $unitModel->count('id=:id', ['id' => $value->getValue()])
                    ) {
                        return false;
                    }
                    return true;
                }, '{alias} ' . 'انتخاب شده نامعتبر است.');
            // alert product
            $validator
                ->setFields('inp-edit-product-alert-product')
                ->isInteger()
                ->greaterThan(0);
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
            $ids = session()->getFlash('product-curr-ids');
            if (is_null($ids)) return false;

            $pub = input()->post('inp-edit-product-status', '')->getValue();
            $availability = input()->post('inp-edit-product-availability', '')->getValue();
            $special = input()->post('inp-edit-product-special', '')->getValue();
            $returnable = input()->post('inp-edit-product-returnable', '')->getValue();
            $commenting = input()->post('inp-edit-product-commenting', '')->getValue();
            $comingSoon = input()->post('inp-edit-product-coming-soon', '')->getValue();
            $callForMore = input()->post('inp-edit-product-call-for-more', '')->getValue();
            $brand = input()->post('inp-edit-product-brand', '')->getValue();
            $category = input()->post('inp-edit-product-category', '')->getValue();
            $alertProduct = input()->post('inp-edit-product-alert-product', '')->getValue();

            //
            $updateArr = [
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ];

            if ($brand != DEFAULT_OPTION_VALUE) {
                $updateArr['brand_id'] = $brand;
            }
            if ($category != DEFAULT_OPTION_VALUE) {
                $updateArr['category_id'] = $category;
            }
            if (!empty($alertProduct)) {
                $updateArr['min_product_alert'] = $xss->xss_clean($alertProduct) ?: null;
            }
            //
            if (!is_value_checked(input()->post('inp-edit-product-status-chk', '')->getValue())) {
                $updateArr['publish'] = is_value_checked($pub) ? DB_YES : DB_NO;
            }
            if (!is_value_checked(input()->post('inp-edit-product-availability-chk', '')->getValue())) {
                $updateArr['is_available'] = is_value_checked($availability) ? DB_YES : DB_NO;
            }
            if (!is_value_checked(input()->post('inp-edit-product-special-chk', '')->getValue())) {
                $updateArr['is_special'] = is_value_checked($special) ? DB_YES : DB_NO;
            }
            if (!is_value_checked(input()->post('inp-edit-product-returnable-chk', '')->getValue())) {
                $updateArr['is_returnable'] = is_value_checked($returnable) ? DB_YES : DB_NO;
            }
            if (!is_value_checked(input()->post('inp-edit-product-commenting-chk', '')->getValue())) {
                $updateArr['allow_commenting'] = is_value_checked($commenting) ? DB_YES : DB_NO;
            }
            if (!is_value_checked(input()->post('inp-edit-product-coming-soon-chk', '')->getValue())) {
                $updateArr['show_coming_soon'] = is_value_checked($comingSoon) ? DB_YES : DB_NO;
            }
            if (!is_value_checked(input()->post('inp-edit-product-call-for-more-chk', '')->getValue())) {
                $updateArr['call_for_more'] = is_value_checked($callForMore) ? DB_YES : DB_NO;
            }

            // get unit title and sign
            $unit = input()->post('inp-edit-product-unit', '')->getValue();
            $unitInfo = $unitModel->getFirst(['title', 'sign'], 'id=:id', ['id' => $unit]);

            if ($unit !== DEFAULT_OPTION_VALUE && count($unitInfo)) {
                $updateArr['unit_title'] = $unitInfo['title'];
                $updateArr['unit_sign'] = $unitInfo['sign'];
            }

            // insert all products and main product information
            return $productModel->updateBatchProducts($ids, $updateArr);
        } catch (\Exception $e) {
            return false;
        }
    }
}
