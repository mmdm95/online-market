<?php

namespace App\Logic\Forms\Admin\Stepped;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use voku\helper\AntiXSS;

class EditSteppedForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
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
                'inp-edit-pay-method-img' => 'تصویر',
                'inp-edit-pay-method-title' => 'عنوان',
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
            $pub = input()->post('inp-edit-pay-method-status', '')->getValue();
            $id = session()->getFlash('pay-method-curr-id', null);
            if (is_null($id)) return false;

            return $payModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}