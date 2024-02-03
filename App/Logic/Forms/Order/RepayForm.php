<?php

namespace App\Logic\Forms\Order;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\OrderPaymentModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class RepayForm implements IPageForm
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
        $validator->resetBagValues();

        return [
            $validator->getStatus(),
            $validator->getUniqueErrors(),
        ];
    }

    /**
     * {@inheritdoc}
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Cart\Interfaces\IDBException
     */
    public function store(): bool
    {
        $res = false;
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var OrderPaymentModel $orderPayModel
         */
        $orderPayModel = container()->get(OrderPaymentModel::class);

        try {
            // if user is logged in, fetch his info
            if ($auth->isLoggedIn()) {
                $order = session()->getFlash(SESSION_REPAY_ORDER_RECORD);
                $gateway = session()->getFlash(SESSION_REPAY_GATEWAY_RECORD);
                $uniqueCode = session()->get(SESSION_REPAY_GATEWAY_UNIQUE_CODE);

                // insert to database
                $res = $orderPayModel->insert([
                    'code' => $uniqueCode,
                    'order_code' => $order['code'],
                    'method_code' => $gateway['code'],
                    'method_title' => $gateway['title'],
                    'method_type' => $gateway['type'],
                    'payment_status' => PAYMENT_STATUS_WAIT,
                ]);
            }
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }
}
