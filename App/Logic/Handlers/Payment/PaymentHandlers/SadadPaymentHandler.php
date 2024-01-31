<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerConnectionOutput;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Handlers\Payment\PaymentHandlerResultOutput;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderReserveModel;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Factories\Sadad;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\Sadad\SadadAdviceResultProvider;
use Sim\Payment\Providers\Sadad\SadadHandlerProvider;
use Sim\Payment\Providers\Sadad\SadadRequestProvider;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;

class SadadPaymentHandler extends AbstractPaymentHandler implements PaymentHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function connection(PaymentHandlerConnectionInput $input): PaymentHandlerConnectionOutput
    {
        $res = true;
        $gatewayRes = null;

        /**
         * @var Sadad $gateway
         */
        // $key, $merchantId, $terminalId
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_SADAD,
            $this->credentials['key'],
            $this->credentials['merchant'],
            $this->credentials['terminal']
        );
        // provider
        $provider = new SadadRequestProvider();
        $provider
            ->setReturnUrl($input->getCallbackUrl())
            ->setAmount(($input->getPrice() * 10))
            ->setOrderId((int)$input->getOrderCode());
        // events
        $gateway->createRequestOkClosure(
            function (
                IEvent                     $event,
                SadadRequestResultProvider $result
            ) use ($input, &$gatewayRes) {
                // store gateway info to store all order things at once
                $gatewayRes = $result;
                $this->gatewayModel->insert([
                    'code' => $input->getUniqueCode(),
                    'order_code' => $input->getOrderCode(),
                    'user_id' => $input->getUserId(),
                    'price' => $input->getPrice(),
                    'is_success' => DB_NO,
                    'method_type' => METHOD_TYPE_GATEWAY_SADAD,
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
                self::logConnectionError(METHOD_TYPE_GATEWAY_SADAD, $code, $msg);
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
         * @var Sadad $gateway
         */
        // $key, $merchantId, $terminalId
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_SADAD,
            $this->credentials['key'],
            $this->credentials['merchant'],
            $this->credentials['terminal']
        );
        $gateway
            ->handleResultNotOkClosure(function () use (&$res) {
                $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
            })->sendAdviceOkClosure(
                function (
                    IEvent                    $event,
                    SadadAdviceResultProvider $adviceProvider,
                    SadadHandlerProvider      $resultProvider
                ) use ($orderModel, $orderReserveModel, $input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getSystemTraceNo(''),
                        'status' => $adviceProvider->getResCode(),
                        'msg' => $adviceProvider->getDescription(),
                        'is_success' => DB_YES,
                        'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_ADVICE,
                        'payment_date' => time(),
                        'extra_info' => json_encode([
                            'result' => $resultProvider->getParameters(),
                            'advice' => $adviceProvider->getParameters(),
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

                    $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getSystemTraceNo('')];
                }
            )->sendAdviceNotOkClosure(
                function (
                    IEvent                    $event,
                                              $code,
                                              $msg,
                    SadadAdviceResultProvider $adviceProvider,
                    SadadHandlerProvider      $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getSystemTraceNo(''),
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

                    $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getSystemTraceNo('')];
                }
            )->sendAdvice();

        return new PaymentHandlerResultOutput($res[0], $res[1], $res[2]);
    }
}
