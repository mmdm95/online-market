<?php

namespace App\Logic\Forms\Admin;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\OrderModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ChangeInvoiceStatus implements IPageForm
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
                'inp-change-order-invoice-status' => 'وضعیت پرداخت',
            ]);

        // status
        $validator
            ->setFields('inp-change-order-invoice-status')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isIn(PAYMENT_STATUSES, '{alias} ' . 'نامعتبر است.');

        // check for id is not necessary here, but you can do it when needed
        // ...

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
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('curr_order_detail_id', null);
            $status = input()->post('inp-change-order-invoice-status', '')->getValue();

            return $orderModel->update([
                'payment_status' => $xss->xss_clean(trim($status)),
                'invoice_status_changed_by' => $auth->getCurrentUser()['id'] ?? null,
                'invoice_status_changed_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}