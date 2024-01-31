<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerConnectionOutput;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Handlers\Payment\PaymentHandlerResultOutput;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderReserveModel;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Factories\Zarinpal;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\Zarinpal\ZarinpalAdviceProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalAdviceResultProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalHandlerProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalRequestProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalRequestResultProvider;

class ZarinpalPaymentHandler extends AbstractPaymentHandler implements PaymentHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function connection(PaymentHandlerConnectionInput $input): PaymentHandlerConnectionOutput
    {
        $res = true;
        $gatewayRes = null;

        /**
         * @var Zarinpal $gateway
         */
        // $merchantId
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_ZARINPAL,
            $this->credentials['merchant']
        );
        // provider
        $provider = new ZarinpalRequestProvider();
        $provider
            ->setCallbackUrl()
            ->setAmount(($input->getPrice() * 10));
        // events
        $gateway->createRequestOkClosure(
            function (
                IEvent                        $event,
                ZarinpalRequestResultProvider $result
            ) use ($input, &$gatewayRes) {
                // store gateway info to store all order things at once
                $gatewayRes = $result;
                $this->gatewayModel->insert([
                    'code' => $input->getUniqueCode(),
                    'order_code' => $input->getOrderCode(),
                    'user_id' => $input->getUserId(),
                    'price' => $input->getPrice(),
                    'is_success' => DB_NO,
                    'method_type' => METHOD_TYPE_GATEWAY_ZARINPAL,
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
                self::logConnectionError(METHOD_TYPE_GATEWAY_ZARINPAL, $code, $msg);
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
         * @var Zarinpal $gateway
         */
        // $merchantId
        $gateway = PaymentFactory::instance(
            PaymentFactory::GATEWAY_ZARINPAL,
            $this->credentials['merchant']
        );
        $gateway
            ->handleResultNotOkClosure(function () use (&$res) {
                $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
            })->sendAdviceOkClosure(
                function (
                    IEvent                       $event,
                    ZarinpalAdviceResultProvider $adviceProvider,
                    ZarinpalHandlerProvider      $resultProvider
                ) use ($orderModel, $orderReserveModel, $input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getRefID(''),
                        'status' => $adviceProvider->getStatus(),
                        'msg' => 'عملیات با موفقیت انجام شد.',
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

                    $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getRefID('')];
                }
            )->sendAdviceNotOkClosure(
                function (
                    IEvent                       $event,
                                                 $code,
                                                 $msg,
                    ZarinpalAdviceResultProvider $adviceProvider,
                    ZarinpalHandlerProvider      $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'payment_code' => $adviceProvider->getRefID(''),
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

                    $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getRefID('')];
                }
            )->failedSendAdviceClosure(
                function (
                    IEvent                  $event,
                                            $code,
                                            $msg,
                    ZarinpalHandlerProvider $resultProvider
                ) use ($input, &$res) {
                    $this->gatewayModel->update([
                        'status' => $resultProvider->getStatus(),
                        'msg' => $msg,
                        'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                        'payment_date' => time(),
                        'extra_info' => json_encode(['result' => $resultProvider->getParameters()]),
                    ], 'code=:code AND is_success=:suc', ['code' => $input->getGatewayCode(), 'suc' => DB_NO]);

                    $this->orderPayModel->update([
                        'payment_status' => PAYMENT_STATUS_FAILED,
                    ], 'code=:code', ['code' => $input->getGatewayCode()]);

                    $res = [false, GATEWAY_ERROR_MESSAGE, null];
                }
            )->sendAdvice(
                (new ZarinpalAdviceProvider())->setAmount($input->getPrice())
            );

        return new PaymentHandlerResultOutput($res[0], $res[1], $res[2]);
    }
}
