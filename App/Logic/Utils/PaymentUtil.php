<?php

namespace App\Logic\Utils;

use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderModel;
use Sim\Auth\DBAuth;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Factories\BehPardakht;
use Sim\Payment\Factories\IDPay;
use Sim\Payment\Factories\Mabna;
use Sim\Payment\Factories\Sadad;
use Sim\Payment\Factories\Zarinpal;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\BehPardakht\BehPardakhtAdviceResultProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtHandlerProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtRequestProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtRequestResultProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtSettleResultProvider;
use Sim\Payment\Providers\IDPay\IDPayAdviceResultProvider;
use Sim\Payment\Providers\IDPay\IDPayHandlerProvider;
use Sim\Payment\Providers\IDPay\IDPayRequestProvider;
use Sim\Payment\Providers\IDPay\IDPayRequestResultProvider;
use Sim\Payment\Providers\Mabna\MabnaAdviceResultProvider;
use Sim\Payment\Providers\Mabna\MabnaHandlerProvider;
use Sim\Payment\Providers\Mabna\MabnaRequestProvider;
use Sim\Payment\Providers\Mabna\MabnaRequestResultProvider;
use Sim\Payment\Providers\Sadad\SadadAdviceResultProvider;
use Sim\Payment\Providers\Sadad\SadadHandlerProvider;
use Sim\Payment\Providers\Sadad\SadadRequestProvider;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalAdviceProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalAdviceResultProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalHandlerProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalRequestProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalRequestResultProvider;
use Sim\Utils\StringUtil;

class PaymentUtil
{
    /**
     * @param $gatewayType
     * @param $gatewayCode
     * @param $gatewayInfo
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Exception
     */
    public static function getGatewayInfo($gatewayType, $gatewayCode, $gatewayInfo)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);
        $gateway = null;
        $provider = null;

        $code = StringUtil::uniqidReal(20);

        $orderArr = session()->get(SESSION_ORDER_ARR_INFO);
        if (is_null($orderArr) || !count($orderArr)) return;

        switch ((int)$gatewayType) {
            case METHOD_TYPE_GATEWAY_BEH_PARDAKHT:
                /**
                 * @var BehPardakht $gateway
                 */
                // $terminalId, $username, $password
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_BEH_PARDAKHT,
                    $gatewayInfo['terminal'],
                    $gatewayInfo['username'],
                    $gatewayInfo['password']
                );
                // provider
                $provider = new BehPardakhtRequestProvider();
                $provider
                    ->setCallbackUrl(rtrim(get_base_url(), '/') . url('home.finish', [
                            'type' => METHOD_RESULT_TYPE_BEH_PARDAKHT,
                            'code' => $gatewayCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount($orderArr['final_price'])
                    ->setOrderId($orderArr['code'])
                    ->setPayerId(0);
                // events
                $gateway->createRequestOkClosure(
                    function (IEvent $event, BehPardakhtRequestResultProvider $result) use ($auth, $gatewayModel, $orderArr, $code) {
                        // store gateway info in session to store all order things at once
                        session()->setFlash(SESSION_GATEWAY_INFO, $result);
                        $gatewayModel->insert([
                            'code' => $code,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_BEH_PARDAKHT,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                        ]);
                    }
                )->createRequestNotOkClosure(function (IEvent $event, $code, $msg) use ($gatewayModel, $code) {
                    $gatewayModel->update([
                        'status' => $code,
                        'msg' => $msg,
                    ], 'code=:code', ['code' => $code]);
                });
                //
                $gateway->createRequest($provider);
                break;
            case METHOD_TYPE_GATEWAY_IDPAY:
                /**
                 * @var IDPay $gateway
                 */
                // $apiKey
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_ID_PAY,
                    $gatewayInfo['api_key']
                );
                // provider
                $provider = new IDPayRequestProvider();
                $provider
                    ->setCallbackUrl(rtrim(get_base_url(), '/') . url('home.finish', [
                            'type' => METHOD_RESULT_TYPE_IDPAY,
                            'code' => $gatewayCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount($orderArr['final_price'])
                    ->setOrderId($orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (IEvent $event, IDPayRequestResultProvider $result) use ($auth, $gatewayModel, $orderArr, $code) {
                        // store gateway info in session to store all order things at once
                        session()->setFlash(SESSION_GATEWAY_INFO, $result);
                        $gatewayModel->insert([
                            'code' => $code,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_IDPAY,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                        ]);
                    }
                )->createRequestNotOkClosure(function (IEvent $event, $code, $msg) use ($gatewayModel, $code) {
                    $gatewayModel->update([
                        'status' => $code,
                        'msg' => $msg,
                    ], 'code=:code', ['code' => $code]);
                });
                //
                $gateway->createRequest($provider);
                break;
            case METHOD_TYPE_GATEWAY_MABNA:
                /**
                 * @var Mabna $gateway
                 */
                // $terminalId
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_MABNA,
                    $gatewayInfo['terminal']
                );
                // provider
                $provider = new MabnaRequestProvider();
                $provider
                    ->setCallbackUrl(rtrim(get_base_url(), '/') . url('home.finish', [
                            'type' => METHOD_RESULT_TYPE_MABNA,
                            'code' => $gatewayCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount($orderArr['final_price'])
                    ->setInvoiceId($orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (IEvent $event, MabnaRequestResultProvider $result) use ($auth, $gatewayModel, $orderArr, $code) {
                        // store gateway info in session to store all order things at once
                        session()->setFlash(SESSION_GATEWAY_INFO, $result);
                        $gatewayModel->insert([
                            'code' => $code,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_MABNA,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                        ]);
                    }
                )->createRequestNotOkClosure(function (IEvent $event, $code, $msg) use ($gatewayModel, $code) {
                    $gatewayModel->update([
                        'status' => $code,
                        'msg' => $msg,
                    ], 'code=:code', ['code' => $code]);
                });
                //
                $gateway->createRequest($provider);
                break;
            case METHOD_TYPE_GATEWAY_ZARINPAL:
                /**
                 * @var Zarinpal $gateway
                 */
                // $merchantId
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_ZARINPAL,
                    $gatewayInfo['merchant']
                );
                // provider
                $provider = new ZarinpalRequestProvider();
                $provider
                    ->setCallbackUrl(rtrim(get_base_url(), '/') . url('home.finish', [
                            'type' => METHOD_RESULT_TYPE_ZARINPAL,
                            'code' => $gatewayCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount($orderArr['final_price']);
                // events
                $gateway->createRequestOkClosure(
                    function (IEvent $event, ZarinpalRequestResultProvider $result) use ($auth, $gatewayModel, $orderArr, $code) {
                        // store gateway info in session to store all order things at once
                        session()->setFlash(SESSION_GATEWAY_INFO, $result);
                        $gatewayModel->insert([
                            'code' => $code,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_ZARINPAL,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                        ]);
                    }
                )->createRequestNotOkClosure(function (IEvent $event, $code, $msg) use ($gatewayModel, $code) {
                    $gatewayModel->update([
                        'status' => $code,
                        'msg' => $msg,
                    ], 'code=:code', ['code' => $code]);
                });
                //
                $gateway->createRequest($provider);
                break;
            case METHOD_TYPE_GATEWAY_SADAD:
                /**
                 * @var Sadad $gateway
                 */
                // $key, $merchantId, $terminalId
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_SADAD,
                    $gatewayInfo['key'],
                    $gatewayInfo['merchant'],
                    $gatewayInfo['terminal']
                );
                // provider
                $provider = new SadadRequestProvider();
                $provider
                    ->setReturnUrl(rtrim(get_base_url(), '/') . url('home.finish', [
                            'type' => METHOD_RESULT_TYPE_SADAD,
                            'code' => $gatewayCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount($orderArr['final_price'])
                    ->setOrderId($orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (IEvent $event, SadadRequestResultProvider $result) use ($auth, $gatewayModel, $orderArr, $code) {
                        // store gateway info in session to store all order things at once
                        session()->setFlash(SESSION_GATEWAY_INFO, $result);
                        $gatewayModel->insert([
                            'code' => $code,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_SADAD,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                        ]);
                    }
                )->createRequestNotOkClosure(function (IEvent $event, $code, $msg) use ($gatewayModel, $code) {
                    $gatewayModel->update([
                        'status' => $code,
                        'msg' => $msg,
                    ], 'code=:code', ['code' => $code]);
                });
                //
                $gateway->createRequest($provider);
                break;
        }
    }

    /**
     * Check result of gateway and take action
     *
     * @param $gatewayType
     * @param $gatewayCode
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getResultProvider($gatewayType, $gatewayCode): array
    {
        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        $order = $orderModel->getOrderFromGatewayCode($gatewayCode);
        $gatewayInfo = [];
        $gateway = null;
        $provider = null;

        $res = [false, 'سفارش نامعتبر می‌باشد.', null];

        // check the order and see if it is a valid order
        if (0 == count($order)) {
            return $res;
        }

        switch ((int)$gatewayType) {
            case METHOD_TYPE_GATEWAY_BEH_PARDAKHT:
                /**
                 * @var BehPardakht $gateway
                 */
                // $terminalId, $username, $password
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_BEH_PARDAKHT,
                    $gatewayInfo['terminal'],
                    $gatewayInfo['username'],
                    $gatewayInfo['password']
                );
                $gateway
                    ->handleResultNotOkClosure(function () use (&$res) {
                        $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
                    })->duplicateSendAdviceClosure(function () use (&$res) {
                        $res = [true, GATEWAY_SUCCESS_MESSAGE, null];
                    })->sendAdviceNotOkClosure(
                        function (
                            IEvent $event,
                            $code,
                            $msg,
                            BehPardakhtAdviceResultProvider $adviceProvider,
                            BehPardakhtHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $resultProvider->getSaleReferenceId(''),
                                'status' => $code,
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getSaleReferenceId('')];
                        }
                    )->sendSettleOKClosure(
                        function (
                            IEvent $event,
                            BehPardakhtSettleResultProvider $settleProvider,
                            BehPardakhtAdviceResultProvider $adviceProvider,
                            BehPardakhtHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $resultProvider->getSaleReferenceId(''),
                                'status' => $adviceProvider->getStatus(),
                                'msg' => 'عملیات با موفقیت انجام شد.',
                                'is_success' => DB_YES,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_SETTLE,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                    'settle' => $settleProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $resultProvider->getSaleReferenceId('')];
                        }
                    )->sendSettleNotOkClosure(
                        function (
                            IEvent $event,
                            $code,
                            $msg,
                            BehPardakhtSettleResultProvider $settleProvider,
                            BehPardakhtAdviceResultProvider $adviceProvider,
                            BehPardakhtHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
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
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getSaleReferenceId('')];
                        }
                    )->sendAdvice();
                break;
            case METHOD_TYPE_GATEWAY_IDPAY:
                /**
                 * @var IDPay $gateway
                 */
                // $apiKey
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_ID_PAY,
                    $gatewayInfo['api_key']
                );
                $gateway
                    ->handleResultNotOkClosure(function () use (&$res) {
                        $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
                    })->sendAdviceOkClosure(
                        function (
                            IEvent $event,
                            IDPayAdviceResultProvider $adviceProvider,
                            IDPayHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $adviceProvider->getTrackId(''),
                                'status' => $adviceProvider->getStatus(),
                                'msg' => 'عملیات با موفقیت انجام شد.',
                                'is_success' => DB_YES,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_ADVICE,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getTrackId('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent $event,
                            $code,
                            $msg,
                            IDPayAdviceResultProvider $adviceProvider,
                            IDPayHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $adviceProvider->getTrackId(''),
                                'status' => $code,
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getTrackId('')];
                        }
                    );
                break;
            case METHOD_TYPE_GATEWAY_MABNA:
                /**
                 * @var Mabna $gateway
                 */
                // $terminalId
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_MABNA,
                    $gatewayInfo['terminal']
                );
                $gateway
                    ->handleResultNotOkClosure(function () use (&$res) {
                        $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
                    })->sendAdviceOkClosure(
                        function (
                            IEvent $event,
                            MabnaAdviceResultProvider $adviceProvider,
                            MabnaHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            // most of information are in $resultProvider
                            $gatewayModel->update([
                                'payment_code' => $resultProvider->getTraceNumber(''),
                                'status' => $adviceProvider->getStatus(),
                                'msg' => $adviceProvider->getMessage(''),
                                'is_success' => DB_YES,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_ADVICE,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $resultProvider->getTraceNumber('')];
                        }
                    )->duplicateSendAdviceClosure(function () use (&$res) {
                        $res = [true, GATEWAY_SUCCESS_MESSAGE, null];
                    })->sendAdviceNotOkClosure(
                        function (
                            IEvent $event,
                            $code,
                            $msg,
                            MabnaAdviceResultProvider $adviceProvider,
                            MabnaHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $resultProvider->getTraceNumber(''),
                                'status' => $code,
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getTraceNumber('')];
                        }
                    );
                break;
            case METHOD_TYPE_GATEWAY_ZARINPAL:
                /**
                 * @var Zarinpal $gateway
                 */
                // $merchantId
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_ZARINPAL,
                    $gatewayInfo['merchant']
                );
                $gateway
                    ->handleResultNotOkClosure(function () use (&$res) {
                        $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
                    })->sendAdviceOkClosure(
                        function (
                            IEvent $event,
                            ZarinpalAdviceResultProvider $adviceProvider,
                            ZarinpalHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
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
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getRefID('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent $event,
                            $code,
                            $msg,
                            ZarinpalAdviceResultProvider $adviceProvider,
                            ZarinpalHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $adviceProvider->getRefID(''),
                                'status' => $code,
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getRefID('')];
                        }
                    )->failedSendAdviceClosure(
                        function (
                            IEvent $event,
                            $code,
                            $msg,
                            ZarinpalHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'status' => $resultProvider->getStatus(),
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode(['result' => $resultProvider->getParameters()]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, null];
                        }
                    )->sendAdvice(
                        (new ZarinpalAdviceProvider())->setAmount($order['final_price'])
                    );
                break;
            case METHOD_TYPE_GATEWAY_SADAD:
                /**
                 * @var Sadad $gateway
                 */
                // $key, $merchantId, $terminalId
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_SADAD,
                    $gatewayInfo['key'],
                    $gatewayInfo['merchant'],
                    $gatewayInfo['terminal']
                );
                $gateway
                    ->handleResultNotOkClosure(function () use (&$res) {
                        $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
                    })->sendAdviceOkClosure(
                        function (
                            IEvent $event,
                            SadadAdviceResultProvider $adviceProvider,
                            SadadHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
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
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getSystemTraceNo('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent $event,
                            $code,
                            $msg,
                            SadadAdviceResultProvider $adviceProvider,
                            SadadHandlerProvider $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $adviceProvider->getSystemTraceNo(''),
                                'status' => $code,
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code', ['code' => $gatewayCode]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getSystemTraceNo('')];
                        }
                    )->sendAdvice();
                break;
        }

        return $res;
    }
}
