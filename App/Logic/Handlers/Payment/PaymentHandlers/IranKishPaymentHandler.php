<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerConnectionOutput;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Handlers\Payment\PaymentHandlerResultOutput;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderReserveModel;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Factories\IranKish;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\IranKish\IranKishAdviceResultProvider;
use Sim\Payment\Providers\IranKish\IranKishHandlerProvider;
use Sim\Payment\Providers\IranKish\IranKishRequestProvider;
use Sim\Payment\Providers\IranKish\IranKishRequestResultProvider;

class IranKishPaymentHandler extends AbstractPaymentHandler implements PaymentHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function connection(PaymentHandlerConnectionInput $input): PaymentHandlerConnectionOutput
    {
        $res = true;
        $gatewayRes = null;

        /**
         * @var IranKish $gateway
         */
        // $terminal, $password, $acceptorId, $publicKey
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_IRAN_KISH,
            $this->credentials['terminal'],
            $this->credentials['password'],
            $this->credentials['acceptor_id'],
            $this->credentials['public_key']
        );
        // provider
        $provider = new IranKishRequestProvider();
        $provider
            ->setRevertUrl($input->getCallbackUrl())
            ->setAmount(($input->getPrice() * 10))
            ->setRequestId($input->getOrderCode());
        // events
        $gateway->createRequestOkClosure(
            function (
                IEvent                        $event,
                IranKishRequestResultProvider $result
            ) use ($input, &$gatewayRes) {
                // store gateway info to store all order things at once
                $gatewayRes = $result;
                $this->gatewayModel->insert([
                    'code' => $input->getUniqueCode(),
                    'order_code' => $input->getOrderCode(),
                    'user_id' => $input->getUserId(),
                    'price' => $input->getPrice(),
                    'is_success' => DB_NO,
                    'method_type' => METHOD_TYPE_GATEWAY_IRAN_KISH,
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
                self::logConnectionError(METHOD_TYPE_GATEWAY_IRAN_KISH, $code, $msg);
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
         * @var IranKish $gateway
         */
        // $terminal, $password, $acceptorId, $publicKey
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_IRAN_KISH,
            $this->credentials['terminal'],
            $this->credentials['password'],
            $this->credentials['acceptor_id'],
            $this->credentials['public_key']
        );
        $gateway
            ->handleResultNotOkClosure(function (IranKishHandlerProvider $resultProvider) use (&$res) {
                if ($resultProvider->getResponseCode() == 17) {
                    $msg = 'تراکنش توسط کاربر لغو شد.';
                } else {
                    $msg = GATEWAY_INVALID_PARAMETERS_MESSAGE;
                }
                $res = [false, $msg, null];
            })->sendAdviceOkClosure(
                function (
                    IEvent                       $event,
                    IranKishAdviceResultProvider $adviceProvider,
                    IranKishHandlerProvider      $resultProvider
                ) use ($orderModel, $orderReserveModel, $input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getResultSystemTraceAuditNumber(''),
                        'status' => $adviceProvider->getResponseCode(),
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

                    $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getResultSystemTraceAuditNumber('')];
                }
            )->sendAdviceNotOkClosure(
                function (
                    IEvent                       $event,
                                                 $code,
                                                 $msg,
                    IranKishAdviceResultProvider $adviceProvider,
                    IranKishHandlerProvider      $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getResultSystemTraceAuditNumber(''),
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

                    $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getResultSystemTraceAuditNumber('')];
                }
            )->sendAdvice();

        return new PaymentHandlerResultOutput($res[0], $res[1], $res[2]);
    }
}
