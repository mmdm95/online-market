<?php

namespace App\Logic\Forms\Admin\PaymentMethod;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditPaymentMethodForm implements IPageForm
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
                'inp-edit-pay-method-img' => 'تصویر',
                'inp-edit-pay-method-title' => 'عنوان',
                'inp-edit-pay-method-method' => 'نوع روش پرداخت',
                'inp-edit-pay-method-beh-pardakht-terminal' => 'شماره ترمینال به پرداخت',
                'inp-edit-pay-method-beh-pardakht-username' => 'نام کاربری به پرداخت',
                'inp-edit-pay-method-beh-pardakht-password' => 'کلمه عبور به پرداخت',
                'inp-edit-pay-method-idpay-api-key' => 'کلید API آی‌دی پی',
                'inp-edit-pay-method-mabna-terminal' => 'شماره ترمینال پرداخت الکترونیک سپهر',
                'inp-edit-pay-method-zarinpal-merchant' => 'شماره مرچنت زرین پال',
            ]);

        // title
        $validator
            ->setFields('inp-edit-pay-method-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // image
        $validator
            ->setFields('inp-edit-pay-method-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists();
        // method type
        $validator
            ->setFields('inp-edit-pay-method-method')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isIn(METHOD_TYPES, '{alias} ' . 'وارد شده نامعتبر است.');

        $method = $validator->getFieldValue('inp-edit-pay-method-method');
        switch ($method) {
            case METHOD_TYPE_GATEWAY_BEH_PARDAKHT:
                $validator
                    ->setFields([
                        'inp-edit-pay-method-beh-pardakht-terminal',
                        'inp-edit-pay-method-beh-pardakht-username',
                        'inp-edit-pay-method-beh-pardakht-password',
                    ])
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true);
                break;
            case METHOD_TYPE_GATEWAY_IDPAY:
                $validator
                    ->setFields('inp-edit-pay-method-idpay-api-key')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true);
                break;
            case METHOD_TYPE_GATEWAY_MABNA:
                $validator
                    ->setFields('inp-edit-pay-method-mabna-terminal')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true);
                break;
            case METHOD_TYPE_GATEWAY_ZARINPAL:
                $validator
                    ->setFields('inp-edit-pay-method-zarinpal-merchant')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true);
                break;
        }

        /**
         * @var PaymentMethodModel $payModel
         */
        $payModel = container()->get(PaymentMethodModel::class);

        $id = session()->getFlash('pay-method-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $payModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-pay-method-title', 'شناسه روش پرداخت نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-pay-method-title', 'شناسه روش پرداخت نامعتبر است.');
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
         * @var PaymentMethodModel $payModel
         */
        $payModel = container()->get(PaymentMethodModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $image = input()->post('inp-edit-pay-method-img', '')->getValue();
            $title = input()->post('inp-edit-pay-method-title', '')->getValue();
            $method = input()->post('inp-edit-pay-method-method', '')->getValue();
            $pub = input()->post('inp-edit-pay-method-status', '')->getValue();
            $id = session()->getFlash('pay-method-curr-id', null);
            if (is_null($id)) return false;

            $meta = '';
            switch ($method) {
                case METHOD_TYPE_GATEWAY_BEH_PARDAKHT:
                    $behTerminal = input()->post('inp-edit-pay-method-beh-pardakht-terminal', '')->getValue();
                    $behUsername = input()->post('inp-edit-pay-method-beh-pardakht-username', '')->getValue();
                    $behPassword = input()->post('inp-edit-pay-method-beh-pardakht-password', '')->getValue();
                    //
                    $meta = json_encode([
                        'terminal' => $behTerminal,
                        'username' => $behUsername,
                        'password' => $behPassword,
                    ]);
                    break;
                case METHOD_TYPE_GATEWAY_IDPAY:
                    $idpayApiKey = input()->post('inp-edit-pay-method-idpay-api-key', '')->getValue();
                    //
                    $meta = json_encode([
                        'api_key' => $idpayApiKey,
                    ]);
                    break;
                case METHOD_TYPE_GATEWAY_MABNA:
                    $mabnaTerminal = input()->post('inp-edit-pay-method-mabna-terminal', '')->getValue();
                    //
                    $meta = json_encode([
                        'terminal' => $mabnaTerminal,
                    ]);
                    break;
                case METHOD_TYPE_GATEWAY_ZARINPAL:
                    $zarinpalMerchant = input()->post('inp-edit-pay-method-zarinpal-merchant', '')->getValue();
                    //
                    $meta = json_encode([
                        'merchant' => $zarinpalMerchant,
                    ]);
                    break;
            }

            // meta should have value
            if (empty($meta)) {
                return false;
            }

            return $payModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'method_type' => $xss->xss_clean(trim($method)),
                'meta_parameters' => trim($meta),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}