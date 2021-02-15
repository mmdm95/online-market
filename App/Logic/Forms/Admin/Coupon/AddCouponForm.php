<?php

namespace App\Logic\Forms\Admin\Coupon;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CouponModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddCouponForm implements IPageForm
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
                'inp-add-coupon-code' => 'کد',
                'inp-add-coupon-title' => 'عنوان',
                'inp-add-coupon-price' => 'قیمت',
                'inp-add-coupon-min-price' => 'قیمت کمینه اعمال',
                'inp-add-coupon-max-price' => 'قیمت بیشینه اعمال',
                'inp-add-coupon-count' => 'تعداد',
                'inp-add-coupon-use-after' => 'استفاده بعد از تعداد روز',
                'inp-add-coupon-start-date' => 'تاریخ شروع',
                'inp-add-coupon-end-date' => 'تاریخ پایان',
            ])
            ->setOptionalFields([
                'inp-add-coupon-min-price',
                'inp-add-coupon-max-price',
                'inp-add-coupon-use-after',
                'inp-add-coupon-start-date',
                'inp-add-coupon-end-date',
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
            ->setFields('inp-add-coupon-code')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/[0-9a-zA-Z-_]+/g', '{alias} ' . 'باید از حروف انگلیسی، اعداد، خط تیره و آندرلاین تشکیل شده باشد.')
            ->lessThanEqualLength(20)
            ->custom(function (FormValue $value) use ($couponModel) {
                if (0 === $couponModel->count('code=:code', ['code' => trim($value->getValue())])) return true;
                return false;
            }, '{alias} ' . 'وارد شده تکراری است!');
        // title
        $validator
            ->setFields('inp-add-coupon-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // price
        $validator
            ->setFields('inp-add-coupon-price')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.')
            ->greaterThan(0);
        // min & max price
        $validator
            ->setFields([
                'inp-add-coupon-max-price',
                'inp-add-coupon-min-price',
            ])
            ->regex('/[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.')
            ->greaterThan(0);
        // min price
        if ($validator->getFieldValue('inp-add-coupon-max-price', 0) > 0) {
            $validator
                ->setFields('inp-add-coupon-min-price')
                ->lessThan(
                    $validator->getFieldValue('inp-add-coupon-max-price'),
                    '{alias} ' . 'باید از ' . $validator->getFieldAlias('inp-add-coupon-max-price') . ' کمتر باشد.'
                );
        }
        // count
        $validator
            ->setFields('inp-add-coupon-count')
            ->required();
        // count & use after
        $validator
            ->setFields([
                'inp-add-coupon-count',
                'inp-add-coupon-use-after'
            ])
            ->regex('/[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.');
        // start & end date
        $validator
            ->setFields([
                'inp-add-coupon-start-date',
                'inp-add-coupon-end-date'
            ])
            ->timestamp()
            ->custom(function (FormValue $value) {
                if (false === date(DEFAULT_TIME_FORMAT, $value->getValue())) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'یک زمان وارد شده نامعتبر است.');
        // start date
        if ($validator->getFieldValue('inp-add-coupon-end-date', 0) > 0) {
            $validator
                ->setFields('inp-add-coupon-start-date')
                ->lessThan(
                    $validator->getFieldValue('inp-add-coupon-end-date'),
                    '{alias} ' . 'باید از ' . $validator->getFieldAlias('inp-add-coupon-end-date') . ' کمتر باشد.'
                );
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
            $pub = input()->post('inp-add-coupon-status', '')->getValue();
            $code = input()->post('inp-add-coupon-code', '')->getValue();
            $title = input()->post('inp-add-coupon-title', '')->getValue();
            $price = input()->post('inp-add-coupon-price', 0)->getValue();
            $minPrice = input()->post('inp-add-coupon-min-price', null)->getValue();
            $maxPrice = input()->post('inp-add-coupon-max-price', null)->getValue();
            $count = input()->post('inp-add-coupon-count', 0)->getValue();
            $useAfter = input()->post('inp-add-coupon-use-after', null)->getValue();
            $startAt = input()->post('inp-add-coupon-start-date', null)->getValue();
            $endAt = input()->post('inp-add-coupon-end-date', null)->getValue();

            return $couponModel->insert([
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
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}