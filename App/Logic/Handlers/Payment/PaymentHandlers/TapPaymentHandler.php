<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerConnectionOutput;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Handlers\Payment\PaymentHandlerResultOutput;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Factories\TAP\TapPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\TAP\Payment\TapAdviceResultProvider;
use Sim\Payment\Providers\TAP\Payment\TapHandlerProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestResultProvider;

class TapPaymentHandler extends AbstractPaymentHandler
{
    /**
     * @inheritDoc
     */
    public function connection(PaymentHandlerConnectionInput $input): PaymentHandlerConnectionOutput
    {
        $res = true;
        $gatewayRes = null;

        /**
         * @var TapPayment $gateway
         */
        // $loginAccount
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_TAP,
            $this->credentials['login_account']
        );
        // provider
        $provider = new TapRequestProvider();
        $provider
            ->setCallBackUrl($input->getCallbackUrl())
            ->setAmount(($input->getPrice() * 10))
            ->setOrderId((int)$input->getUniqueCode());
        // events
        $gateway->createRequestOkClosure(
            function (
                IEvent                   $event,
                TapRequestResultProvider $result
            ) use ($input, &$gatewayRes) {
                // store gateway info to store all order things at once
                $gatewayRes = $result;
                $this->gatewayModel->insert([
                    'code' => $input->getUniqueCode(),
                    'order_code' => $input->getOrderCode(),
                    'user_id' => $input->getUserId(),
                    'price' => $input->getPrice(),
                    'is_success' => DB_NO,
                    'method_type' => METHOD_TYPE_GATEWAY_TAP,
                    'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                    'issue_date' => time(),
                    'extra_info' => json_encode([
                        'result' => $result->getParameters(),
                    ]),
                ]);

                $this->emitter->dispatch(self::EVENT_CONNECTION_SUCCESS, [$input]);
            }
        )->createRequestNotOkClosure(
            function (IEvent $event, $code, $msg) use ($input, &$res) {
                $this->emitter->dispatch(self::EVENT_CONNECTION_FAILED, [$input, $msg, $code]);

                $res = false;
                self::logConnectionError(METHOD_TYPE_GATEWAY_TAP, $code, $msg);
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
         * @var TapPayment $gateway
         */
        // $loginAccount
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_TAP,
            $this->credentials['login_account']
        );
        $gateway
            ->handleResultNotOkClosure(function () use (&$res) {
                $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
            })->sendAdviceOkClosure(
                function (
                    IEvent                  $event,
                    TapAdviceResultProvider $adviceProvider,
                    TapHandlerProvider      $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getRRN(''),
                        'status' => $adviceProvider->getStatus(),
                        'msg' => $adviceProvider->getMessage(),
                        'is_success' => DB_YES,
                        'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_ADVICE,
                        'payment_date' => time(),
                        'extra_info' => json_encode([
                            'result' => $resultProvider->getParameters(),
                            'advice' => $adviceProvider->getParameters(),
                        ]),
                    ], 'code=:code', ['code' => $input->getGatewayCode()]);

                    $this->emitter->dispatch(self::EVENT_RESULT_SUCCESS, [$input]);

                    $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getRRN('')];
                }
            )->sendAdviceNotOkClosure(
                function (
                    IEvent                  $event,
                                            $code,
                                            $msg,
                    TapAdviceResultProvider $adviceProvider,
                    TapHandlerProvider      $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getRRN(''),
                        'status' => $code,
                        'msg' => $msg,
                        'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                        'payment_date' => time(),
                        'extra_info' => json_encode([
                            'result' => $resultProvider->getParameters(),
                            'advice' => $adviceProvider->getParameters(),
                        ]),
                    ], 'code=:code AND is_success=:suc', ['code' => $input->getGatewayCode(), 'suc' => DB_NO]);

                    $this->emitter->dispatch(self::EVENT_RESULT_FAILED, [$input, $msg, $code]);

                    $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getRRN('')];
                }
            )->sendAdvice();

        return new PaymentHandlerResultOutput($res[0], $res[1], $res[2]);
    }
}
