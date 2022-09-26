<?php

namespace App\Logic\Forms\Admin\Coupon;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CouponModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditCouponForm implements IPageForm
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
                'inp-edit-coupon-code' => 'کد',
                'inp-edit-coupon-title' => 'عنوان',
                'inp-edit-coupon-price' => 'قیمت',
                'inp-edit-coupon-min-price' => 'قیمت کمینه اعمال',
                'inp-edit-coupon-max-price' => 'قیمت بیشینه اعمال',
                'inp-edit-coupon-count' => 'تعداد',
                'inp-edit-coupon-use-after' => 'استفاده بعد از تعداد روز',
                'inp-edit-coupon-start-date' => 'تاریخ شروع',
                'inp-edit-coupon-end-date' => 'تاریخ پایان',
            ])
            ->setOptionalFields([
                'inp-edit-coupon-min-price',
                'inp-edit-coupon-max-price',
                'inp-edit-coupon-use-after',
                'inp-edit-coupon-start-date',
                'inp-edit-coupon-end-date',
            ])
            ->toEnglishValue(true, true)
            ->toEnglishValueExceptFields([
                'inp-edit-coupon-code',
                'inp-edit-coupon-title',
            ]);

        /**
         * @var CouponModel $couponModel
         */
        $couponModel = container()->get(CouponModel::class);

        // code
        $validator
            ->setFields('inp-edit-coupon-code')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/[0-9a-zA-Z-_]+/', '{alias} ' . 'باید از حروف انگلیسی، اعداد، خط تیره و آندرلاین تشکیل شده باشد.')
            ->lessThanEqualLength(20)
            ->custom(function (FormValue $value) use ($couponModel) {
                $code = session()->getFlash('coupon-prev-code', null);
                if (
                    $code != trim($value->getValue()) &&
                    $couponModel->count('code=:code', ['code' => trim($value->getValue())]) !== 0
                ) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده تکراری است!');
        // title
        $validator
            ->setFields('inp-edit-coupon-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // price
        $validator
            ->setFields('inp-edit-coupon-price')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.')
            ->greaterThan(0);
        // min & max price
        $validator
            ->setFields([
                'inp-edit-coupon-max-price',
                'inp-edit-coupon-min-price',
            ])
            ->regex('/[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.')
            ->greaterThan(0);
        // min price
        if ($validator->getFieldValue('inp-edit-coupon-max-price', 0) > 0) {
            $validator
                ->setFields('inp-edit-coupon-min-price')
                ->lessThan(
                    $validator->getFieldValue('inp-edit-coupon-max-price'),
                    '{alias} ' . 'باید از ' . $validator->getFieldAlias('inp-edit-coupon-max-price') . ' کمتر باشد.'
                );
        }
        // count
        $validator
            ->setFields('inp-edit-coupon-count')
            ->required();
        // count & use after
        $validator
            ->setFields([
                'inp-edit-coupon-count',
                'inp-edit-coupon-use-after'
            ])
            ->regex('/[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.');
        // start & end date
        $validator
            ->setFields([
                'inp-edit-coupon-start-date',
                'inp-edit-coupon-end-date'
            ])
            ->timestamp()
            ->custom(function (FormValue $value) {
                if (false === date(DEFAULT_TIME_FORMAT, $value->getValue())) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'یک زمان وارد شده نامعتبر است.');
        // start date
        if ($validator->getFieldValue('inp-edit-coupon-end-date', 0) > 0) {
            $validator
                ->setFields('inp-edit-coupon-start-date')
                ->lessThan(
                    $validator->getFieldValue('inp-edit-coupon-end-date'),
                    '{alias} ' . 'باید از ' . $validator->getFieldAlias('inp-edit-coupon-end-date') . ' کمتر باشد.'
                );
        }

        $id = session()->getFlash('coupon-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $couponModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-coupon-title', 'شناسه کوپن نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-coupon-title', 'شناسه کوپن نامعتبر است.');
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
         * @var CouponModel $couponModel
         */
        $couponModel = container()->get(CouponModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $pub = input()->post('inp-edit-coupon-status', '')->getValue();
            $code = input()->post('inp-edit-coupon-code', '')->getValue();
            $title = input()->post('inp-edit-coupon-title', '')->getValue();
            $price = input()->post('inp-edit-coupon-price', 0)->getValue();
            $minPrice = input()->post('inp-edit-coupon-min-price', null)->getValue();
            $maxPrice = input()->post('inp-edit-coupon-max-price', null)->getValue();
            $count = input()->post('inp-edit-coupon-count', 0)->getValue();
            $useAfter = input()->post('inp-edit-coupon-use-after', null)->getValue();
            $startAt = input()->post('inp-edit-coupon-start-date', null)->getValue();
            $endAt = input()->post('inp-edit-coupon-end-date', null)->getValue();
            $id = session()->getFlash('coupon-curr-id', null);
            if (is_null($id)) return false;

            return $couponModel->update([
                'code' => $xss->xss_clean(trim($code)),
                'title' => $xss->xss_clean(trim($title)),
                'price' => $xss->xss_clean($price),
                'min_price' => $xss->xss_clean($minPrice) ?: null,
                'max_price' => $xss->xss_clean($maxPrice) ?: null,
                'use_count' => $xss->xss_clean($count),
                'start_at' => $xss->xss_clean($startAt) ?: null,
                'expire_at' => $xss->xss_clean($endAt) ?: null,
                'reusable_after' => $xss->xss_clean($useAfter) ?: null,
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}