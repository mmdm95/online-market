<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerConnectionOutput;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Handlers\Payment\PaymentHandlerResultOutput;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderReserveModel;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Factories\BehPardakht;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\BehPardakht\BehPardakhtAdviceResultProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtHandlerProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtRequestProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtRequestResultProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtSettleResultProvider;

class BehPardakhtPaymentHandler extends AbstractPaymentHandler implements PaymentHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function connection(PaymentHandlerConnectionInput $input): PaymentHandlerConnectionOutput
    {
        $res = true;
        $gatewayRes = null;

        /**
         * @var BehPardakht $gateway
         */
        // $terminalId, $username, $password
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_BEH_PARDAKHT,
            $this->credentials['terminal'],
            $this->credentials['username'],
            $this->credentials['password']
        );
        // provider
        $provider = new BehPardakhtRequestProvider();
        $provider
            ->setCallbackUrl($input->getCallbackUrl())
            ->setAmount(($input->getPrice() * 10))
            ->setOrderId($input->getOrderCode())
            ->setPayerId(0);
        // events
        $gateway->createRequestOkClosure(
            function (
                IEvent                           $event,
                BehPardakhtRequestResultProvider $result
            ) use ($input, &$gatewayRes) {
                // store gateway info to store all order things at once
                $gatewayRes = $result;

                $this->gatewayModel->insert([
                    'code' => $input->getUniqueCode(),
                    'order_code' => $input->getOrderCode(),
                    'user_id' => $input->getUserId(),
                    'price' => $input->getPrice(),
                    'is_success' => DB_NO,
                    'method_type' => METHOD_TYPE_GATEWAY_BEH_PARDAKHT,
                    'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                    'issue_date' => time(),
                    'extra_info' => json_encode([
                        'result' => $result->getParameters(),
                    ]),
                ]);

                $this->orderPayModel->insert([
                    'code' => $input->getUniqueCode(),
                    'order_code' => $input->getOrderCode(),
                    'method_code' => $input->getPaymentMethodInfo()['method_code'],
                    'method_title' => $input->getPaymentMethodInfo()['method_title'],
                    'method_type' => $input->getPaymentMethodInfo()['method_type'],
                    'payment_status' => PAYMENT_STATUS_WAIT,
                ]);
            }
        )->createRequestNotOkClosure(
            function (IEvent $event, $code, $msg) use (&$res) {
                $res = false;
                self::logConnectionError(METHOD_TYPE_GATEWAY_BEH_PARDAKHT, $code, $msg);
            }
        );
        //
        $gateway->createRequest($provider);

        return new PaymentHandlerConnectionOutput($gatewayRes, $res);
    }

    /**
     * @inheritDoc
     */
    public function result(PaymentHandlerResultInput $input): PaymentHandlerResultOutput
    {
        $res = [false, 'سفارش نامعتبر می‌باشد.', null];

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderReserveModel $orderReserveModel
         */
        $orderReserveModel = container()->get(OrderReserveModel::class);

        /**
         * @var BehPardakht $gateway
         */
        // $terminalId, $username, $password
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_BEH_PARDAKHT,
            $this->credentials['terminal'],
            $this->credentials['username'],
            $this->credentials['password']
        );
        $gateway
            ->handleResultNotOkClosure(function () use (&$res) {
                $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
            })->duplicateSendAdviceClosure(function () use (&$res) {
                $res = [true, GATEWAY_SUCCESS_MESSAGE, null];
            })->sendAdviceNotOkClosure(
                function (
                    IEvent                          $event,
                                                    $code,
                                                    $msg,
                    BehPardakhtAdviceResultProvider $adviceProvider,
                    BehPardakhtHandlerProvider      $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $resultProvider->getSaleReferenceId(''),
                        'status' => $code,
                        'msg' => $msg,
                        'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                        'payment_date' => time(),
                        'extra_info' => json_encode([
                            'result' => $resultProvider->getParameters(),
                            'advice' => $adviceProvider->getParameters(),
                        ]),
                    ], 'code=:code AND is_success=:suc', ['code' => $input->getGatewayCode(), 'suc' => DB_NO]);

                    $this->orderPayModel->update([
                        'payment_status' => PAYMENT_STATUS_FAILED,
                    ], 'code=:code', ['code' => $input->getGatewayCode()]);

                    $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getSaleReferenceId('')];
                }
            )->sendSettleOKClosure(
                function (
                    IEvent                          $event,
                    BehPardakhtSettleResultProvider $settleProvider,
                    BehPardakhtAdviceResultProvider $adviceProvider,
                    BehPardakhtHandlerProvider      $resultProvider
                ) use ($orderModel, $orderReserveModel, $input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $resultProvider->getSaleReferenceId(''),
                        'status' => $adviceProvider->getReturn(),
                        'msg' => 'عملیات با موفقیت انجام شد.',
                        'is_success' => DB_YES,
                        'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_SETTLE,
                        'payment_date' => time(),
                        'extra_info' => json_encode([
                            'result' => $resultProvider->getParameters(),
                            'advice' => $adviceProvider->getParameters(),
                            'settle' => $settleProvider->getParameters(),
                        ]),
                    ], 'code=:code', ['code' => $input->getGatewayCode()]);

                    $this->orderPayModel->update([
                        'payment_status' => PAYMENT_STATUS_SUCCESS,
                    ], 'code=:code', ['code' => $input->getGatewayCode()]);

                    $orderModel->update([
                        'payment_status' => PAYMENT_STATUS_SUCCESS,
                        'payed_at' => time(),
                    ], 'code=:code', ['code' => $input->getOrderCode()]);

                    $orderReserveModel->delete('order_code=:code', ['code' => $input->getOrderCode()]);

                    $res = [true, GATEWAY_SUCCESS_MESSAGE, $resultProvider->getSaleReferenceId('')];
                }
            )->sendSettleNotOkClosure(
                function (
                    IEvent                          $event,
                                                    $code,
                                                    $msg,
                    BehPardakhtSettleResultProvider $settleProvider,
                    BehPardakhtAdviceResultProvider $adviceProvider,
                    BehPardakhtHandlerProvider      $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $resultProvider->getSaleReferenceId(''),
                        'status' => $code,
                        'msg' => $msg,
                        'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_ADVICE,
                        'payment_date' => time(),
                        'extra_info' => json_encode([
                            'result' => $resultProvider->getParameters(),
                            'advice' => $adviceProvider->getParameters(),
                            'settle' => $settleProvider->getParameters(),
                        ]),
                    ], 'code=:code AND is_success=:suc', ['code' => $input->getGatewayCode(), 'suc' => DB_NO]);

                    $this->orderPayModel->update([
                        'payment_status' => PAYMENT_STATUS_FAILED,
                    ], 'code=:code', ['code' => $input->getGatewayCode()]);

                    $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getSaleReferenceId('')];
                }
            )->sendAdvice();

        return new PaymentHandlerResultOutput($res[0], $res[1], $res[2]);
    }
}
