<?php

namespace App\Logic\Forms\Admin\ReturnOrder;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ReturnOrderModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ReturnOrderStatusForm implements IPageForm
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
                'inp-return-order-status' => 'وضعیت مرجوعی',
            ]);

        /**
         * @var ReturnOrderModel $returnModel
         */
        $returnModel = container()->get(ReturnOrderModel::class);

        $id = session()->getFlash('curr_return_order_detail_id', null, false);
        if (!empty($id) && $returnModel->getReturnOrdersCount('id=:id', ['id' => $id]) > 0) {
            // status
            $validator
                ->setFields('inp-return-order-status')
                ->isIn(RETURN_ORDER_STATUSES, 'وضعیت انتخاب شده نامعتبر است.');
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-return-order-status', 'شناسه سفارش مرجوعی نامعتبر است.');
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getError(),
            $validator->getUniqueErrors(),
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
         * @var ReturnOrderModel $returnOrderModel
         */
        $returnOrderModel = container()->get(ReturnOrderModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('curr_return_order_detail_id', null);
            $code = session()->getFlash('curr_return_order_detail_code', null);
            $orderCode = session()->getFlash('curr_return_order_detail_order_code', null);
            $status = input()->post('inp-return-order-status', '')->getValue();

            if (is_null($id) || is_null($code)) return false;

            return $returnOrderModel->modifyReturnOrderStatus([
                'code' => $code,
                'order_code' => $orderCode,
                'status' => $status,
                'status_changed_at' => time(),
                'status_changed_by' => $auth->getCurrentUser()['id'] ?? null,
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
