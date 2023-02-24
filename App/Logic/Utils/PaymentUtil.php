<?php

namespace App\Logic\Utils;

use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderReserveModel;
use App\Logic\Models\PaymentMethodModel;
use Sim\Auth\DBAuth;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Factories\BehPardakht;
use Sim\Payment\Factories\IDPay;
use Sim\Payment\Factories\IranKish;
use Sim\Payment\Factories\Mabna;
use Sim\Payment\Factories\Sadad;
use Sim\Payment\Factories\TAP\TapPayment;
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
use Sim\Payment\Providers\IranKish\IranKishAdviceResultProvider;
use Sim\Payment\Providers\IranKish\IranKishHandlerProvider;
use Sim\Payment\Providers\IranKish\IranKishRequestProvider;
use Sim\Payment\Providers\IranKish\IranKishRequestResultProvider;
use Sim\Payment\Providers\Mabna\MabnaAdviceResultProvider;
use Sim\Payment\Providers\Mabna\MabnaHandlerProvider;
use Sim\Payment\Providers\Mabna\MabnaRequestProvider;
use Sim\Payment\Providers\Mabna\MabnaRequestResultProvider;
use Sim\Payment\Providers\Sadad\SadadAdviceResultProvider;
use Sim\Payment\Providers\Sadad\SadadHandlerProvider;
use Sim\Payment\Providers\Sadad\SadadRequestProvider;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;
use Sim\Payment\Providers\TAP\Payment\TapAdviceResultProvider;
use Sim\Payment\Providers\TAP\Payment\TapHandlerProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestResultProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalAdviceProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalAdviceResultProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalHandlerProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalRequestProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalRequestResultProvider;
use Sim\Utils\StringUtil;

class PaymentUtil
{
    /**
     * @todo Need refactor because it's not optimised
     *
     * @param $gatewayType
     * @param $gatewayCode
     * @param $gatewayInfo
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Exception
     */
    public static function getGatewayInfo($gatewayType, $gatewayCode, $gatewayInfo): array
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
        $gatewayRes = null;
        $provider = null;

        $newCode = self::getUniqueGatewayFlowCode();

        $res = true;

        $orderArr = session()->get(SESSION_ORDER_ARR_INFO);
        if (is_null($orderArr) || !count($orderArr)) return [$gatewayRes, false];

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
                            'method' => $gatewayCode,
                            'code' => $newCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount(($orderArr['final_price'] * 10))
                    ->setOrderId($orderArr['code'])
                    ->setPayerId(0);
                // events
                $gateway->createRequestOkClosure(
                    function (
                        IEvent                           $event,
                        BehPardakhtRequestResultProvider $result
                    ) use ($auth, $gatewayModel, $orderArr, $newCode, &$gatewayRes) {
                        // store gateway info to store all order things at once
                        $gatewayRes = $result;
                        $gatewayModel->insert([
                            'code' => $newCode,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_BEH_PARDAKHT,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                            'extra_info' => json_encode([
                                'result' => $result->getParameters(),
                            ]),
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
                            'method' => $gatewayCode,
                            'code' => $newCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount(($orderArr['final_price'] * 10))
                    ->setOrderId($orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (
                        IEvent                     $event,
                        IDPayRequestResultProvider $result
                    ) use ($auth, $gatewayModel, $orderArr, $newCode, &$gatewayRes) {
                        // store gateway info to store all order things at once
                        $gatewayRes = $result;
                        $gatewayModel->insert([
                            'code' => $newCode,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_IDPAY,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                            'extra_info' => json_encode([
                                'result' => $result->getParameters(),
                            ]),
                        ]);
                    }
                )->createRequestNotOkClosure(
                    function (IEvent $event, $code, $msg) use (&$res) {
                        $res = false;
                        self::logConnectionError(METHOD_TYPE_GATEWAY_IDPAY, $code, $msg);
                    }
                );
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
                            'method' => $gatewayCode,
                            'code' => $newCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount(($orderArr['final_price'] * 10))
                    ->setInvoiceId($orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (
                        IEvent                     $event,
                        MabnaRequestResultProvider $result
                    ) use ($auth, $gatewayModel, $orderArr, $newCode, &$gatewayRes) {
                        // store gateway info to store all order things at once
                        $gatewayRes = $result;
                        $gatewayModel->insert([
                            'code' => $newCode,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_MABNA,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                            'extra_info' => json_encode([
                                'result' => $result->getParameters(),
                            ]),
                        ]);
                    }
                )->createRequestNotOkClosure(
                    function (IEvent $event, $code, $msg) use (&$res) {
                        $res = false;
                        self::logConnectionError(METHOD_TYPE_GATEWAY_MABNA, $code, $msg);
                    }
                );
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
                            'method' => $gatewayCode,
                            'code' => $newCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount(($orderArr['final_price'] * 10));
                // events
                $gateway->createRequestOkClosure(
                    function (
                        IEvent                        $event,
                        ZarinpalRequestResultProvider $result
                    ) use ($auth, $gatewayModel, $orderArr, $newCode, &$gatewayRes) {
                        // store gateway info to store all order things at once
                        $gatewayRes = $result;
                        $gatewayModel->insert([
                            'code' => $newCode,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_ZARINPAL,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                            'extra_info' => json_encode([
                                'result' => $result->getParameters(),
                            ]),
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
                            'method' => $gatewayCode,
                            'code' => $newCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount(($orderArr['final_price'] * 10))
                    ->setOrderId((int)$orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (
                        IEvent                     $event,
                        SadadRequestResultProvider $result
                    ) use ($auth, $gatewayModel, $orderArr, $newCode, &$gatewayRes) {
                        // store gateway info to store all order things at once
                        $gatewayRes = $result;
                        $gatewayModel->insert([
                            'code' => $newCode,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_SADAD,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                            'extra_info' => json_encode([
                                'result' => $result->getParameters(),
                            ]),
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
                break;
            case METHOD_TYPE_GATEWAY_TAP:
                /**
                 * @var TapPayment $gateway
                 */
                // $loginAccount
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_TAP,
                    $gatewayInfo['login_account']
                );
                // provider
                $provider = new TapRequestProvider();
                $provider
                    ->setCallBackUrl(rtrim(get_base_url(), '/') . url('home.finish', [
                            'type' => METHOD_RESULT_TYPE_TAP,
                            'method' => $gatewayCode,
                            'code' => $newCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount(($orderArr['final_price'] * 10))
                    ->setOrderId((int)$orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (
                        IEvent                   $event,
                        TapRequestResultProvider $result
                    ) use ($auth, $gatewayModel, $orderArr, $newCode, &$gatewayRes) {
                        // store gateway info to store all order things at once
                        $gatewayRes = $result;
                        $gatewayModel->insert([
                            'code' => $newCode,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_TAP,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                            'extra_info' => json_encode([
                                'result' => $result->getParameters(),
                            ]),
                        ]);
                    }
                )->createRequestNotOkClosure(
                    function (IEvent $event, $code, $msg) use (&$res) {
                        $res = false;
                        self::logConnectionError(METHOD_TYPE_GATEWAY_TAP, $code, $msg);
                    }
                );
                //
                $gateway->createRequest($provider);
                break;
            case METHOD_TYPE_GATEWAY_IRAN_KISH:
                /**
                 * @var IranKish $gateway
                 */
                // $terminal, $password, $acceptorId, $publicKey
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_IRAN_KISH,
                    $gatewayInfo['terminal'],
                    $gatewayInfo['password'],
                    $gatewayInfo['acceptor_id'],
                    $gatewayInfo['public_key']
                );
                // provider
                $provider = new IranKishRequestProvider();
                $provider
                    ->setRevertUrl(rtrim(get_base_url(), '/') . url('home.finish', [
                            'type' => METHOD_RESULT_TYPE_IRAN_KISH,
                            'method' => $gatewayCode,
                            'code' => $newCode,
                        ])->getRelativeUrlTrimmed())
                    ->setAmount(($orderArr['final_price'] * 10))
                    ->setRequestId((int)$orderArr['code']);
                // events
                $gateway->createRequestOkClosure(
                    function (
                        IEvent                        $event,
                        IranKishRequestResultProvider $result
                    ) use ($auth, $gatewayModel, $orderArr, $newCode, &$gatewayRes) {
                        // store gateway info to store all order things at once
                        $gatewayRes = $result;
                        $gatewayModel->insert([
                            'code' => $newCode,
                            'order_code' => $orderArr['code'],
                            'user_id' => $auth->getCurrentUser()['id'] ?? 0,
                            'price' => $orderArr['final_price'],
                            'is_success' => DB_NO,
                            'method_type' => METHOD_TYPE_GATEWAY_IRAN_KISH,
                            'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST,
                            'issue_date' => time(),
                            'extra_info' => json_encode([
                                'result' => $result->getParameters(),
                            ]),
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
                break;
            default:
                $res = false;
                break;
        }

        return [$gatewayRes, $res];
    }

    /**
     * Check result of gateway and take action
     *
     * @todo Need refactor because it's not optimised
     *
     * @param $type
     * @param $methodCode
     * @param $gatewayCode
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getResultProvider($type, $methodCode, $gatewayCode): array
    {
        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderReserveModel $orderReserveModel
         */
        $orderReserveModel = container()->get(OrderReserveModel::class);
        /**
         * @var PaymentMethodModel $methodModel
         */
        $methodModel = container()->get(PaymentMethodModel::class);

        $res = [false, 'سفارش نامعتبر می‌باشد.', null];

        $flow = $gatewayModel->getFirst([
            'order_code',
            'method_type',
            'is_success',
        ], 'code=:code', ['code' => $gatewayCode]);

        // check flow and see if it is a valid gateway flow record
        if (!count($flow)) {
            return $res;
        }

        $order = $orderModel->getFirst(['*'], 'code=:code', ['code' => $flow['order_code']]);
        $gateway = null;
        $provider = null;

        // check the order and see if it is a valid order
        if (!count($order)) {
            return $res;
        }

        $method = $methodModel->getFirst(['meta_parameters'], 'code=:code', ['code' => $methodCode]);

        // check the order and see if it is a valid order
        if (!count($method)) {
            return $res;
        }
        // if it was successful before, there is no need to change it
        if ($flow['is_success'] == DB_YES) {
            return [true, GATEWAY_SUCCESS_MESSAGE, null];
        }

        $gatewayInfo = json_decode(cryptographer()->decrypt($method['meta_parameters']), true);

        switch ($type) {
            case METHOD_RESULT_TYPE_BEH_PARDAKHT:
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
                            IEvent                          $event,
                                                            $code,
                                                            $msg,
                            BehPardakhtAdviceResultProvider $adviceProvider,
                            BehPardakhtHandlerProvider      $resultProvider
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
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getSaleReferenceId('')];
                        }
                    )->sendSettleOKClosure(
                        function (
                            IEvent                          $event,
                            BehPardakhtSettleResultProvider $settleProvider,
                            BehPardakhtAdviceResultProvider $adviceProvider,
                            BehPardakhtHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $orderModel, $orderReserveModel, $gatewayCode, $flow, &$res) {
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
                            $orderModel->update([
                                'payment_status' => PAYMENT_STATUS_SUCCESS,
                                'payed_at' => time(),
                            ], 'code=:code', ['code' => $flow['order_code']]);
                            $orderReserveModel->delete('order_code=:code', ['code' => $flow['order_code']]);

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
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getSaleReferenceId('')];
                        }
                    )->sendAdvice();
                break;
            case METHOD_RESULT_TYPE_IDPAY:
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
                            IEvent                    $event,
                            IDPayAdviceResultProvider $adviceProvider,
                            IDPayHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $orderModel, $orderReserveModel, $gatewayCode, $flow, &$res) {
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
                            $orderModel->update([
                                'payment_status' => PAYMENT_STATUS_SUCCESS,
                                'payed_at' => time(),
                            ], 'code=:code', ['code' => $flow['order_code']]);
                            $orderReserveModel->delete('order_code=:code', ['code' => $flow['order_code']]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getTrackId('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent                    $event,
                                                      $code,
                                                      $msg,
                            IDPayAdviceResultProvider $adviceProvider,
                            IDPayHandlerProvider      $resultProvider
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
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getTrackId('')];
                        }
                    );
                break;
            case METHOD_RESULT_TYPE_MABNA:
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
                            IEvent                    $event,
                            MabnaAdviceResultProvider $adviceProvider,
                            MabnaHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $orderModel, $orderReserveModel, $gatewayCode, $flow, &$res) {
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
                            $orderModel->update([
                                'payment_status' => PAYMENT_STATUS_SUCCESS,
                                'payed_at' => time(),
                            ], 'code=:code', ['code' => $flow['order_code']]);
                            $orderReserveModel->delete('order_code=:code', ['code' => $flow['order_code']]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $resultProvider->getTraceNumber('')];
                        }
                    )->duplicateSendAdviceClosure(function () use (&$res) {
                        $res = [true, GATEWAY_SUCCESS_MESSAGE, null];
                    })->sendAdviceNotOkClosure(
                        function (
                            IEvent                    $event,
                                                      $code,
                                                      $msg,
                            MabnaAdviceResultProvider $adviceProvider,
                            MabnaHandlerProvider      $resultProvider
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
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $resultProvider->getTraceNumber('')];
                        }
                    );
                break;
            case METHOD_RESULT_TYPE_ZARINPAL:
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
                            IEvent                       $event,
                            ZarinpalAdviceResultProvider $adviceProvider,
                            ZarinpalHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $orderModel, $orderReserveModel, $gatewayCode, $flow, &$res) {
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
                            $orderModel->update([
                                'payment_status' => PAYMENT_STATUS_SUCCESS,
                                'payed_at' => time(),
                            ], 'code=:code', ['code' => $flow['order_code']]);
                            $orderReserveModel->delete('order_code=:code', ['code' => $flow['order_code']]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getRefID('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent                       $event,
                                                         $code,
                                                         $msg,
                            ZarinpalAdviceResultProvider $adviceProvider,
                            ZarinpalHandlerProvider      $resultProvider
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
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getRefID('')];
                        }
                    )->failedSendAdviceClosure(
                        function (
                            IEvent                  $event,
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
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, null];
                        }
                    )->sendAdvice(
                        (new ZarinpalAdviceProvider())->setAmount($order['final_price'])
                    );
                break;
            case METHOD_RESULT_TYPE_SADAD:
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
                            IEvent                    $event,
                            SadadAdviceResultProvider $adviceProvider,
                            SadadHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $orderModel, $orderReserveModel, $gatewayCode, $flow, &$res) {
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
                            $orderModel->update([
                                'payment_status' => PAYMENT_STATUS_SUCCESS,
                                'payed_at' => time(),
                            ], 'code=:code', ['code' => $flow['order_code']]);
                            $orderReserveModel->delete('order_code=:code', ['code' => $flow['order_code']]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getSystemTraceNo('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent                    $event,
                                                      $code,
                                                      $msg,
                            SadadAdviceResultProvider $adviceProvider,
                            SadadHandlerProvider      $resultProvider
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
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getSystemTraceNo('')];
                        }
                    )->sendAdvice();
                break;
            case METHOD_RESULT_TYPE_TAP:
                /**
                 * @var TapPayment $gateway
                 */
                // $loginAccount
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_TAP,
                    $gatewayInfo['login_account']
                );
                $gateway
                    ->handleResultNotOkClosure(function () use (&$res) {
                        $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
                    })->sendAdviceOkClosure(
                        function (
                            IEvent                  $event,
                            TapAdviceResultProvider $adviceProvider,
                            TapHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $orderModel, $orderReserveModel, $gatewayCode, $flow, &$res) {
                            $gatewayModel->update([
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
                            ], 'code=:code', ['code' => $gatewayCode]);
                            $orderModel->update([
                                'payment_status' => PAYMENT_STATUS_SUCCESS,
                                'payed_at' => time(),
                            ], 'code=:code', ['code' => $flow['order_code']]);
                            $orderReserveModel->delete('order_code=:code', ['code' => $flow['order_code']]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getRRN('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent                  $event,
                                                    $code,
                                                    $msg,
                            TapAdviceResultProvider $adviceProvider,
                            TapHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $adviceProvider->getRRN(''),
                                'status' => $code,
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getRRN('')];
                        }
                    )->sendAdvice();
                break;
            case METHOD_RESULT_TYPE_IRAN_KISH:
                /**
                 * @var IranKish $gateway
                 */
                // $terminal, $password, $acceptorId, $publicKey
                $gateway = PaymentFactory::instance(
                    PaymentFactory::GATEWAY_IRAN_KISH,
                    $gatewayInfo['terminal'],
                    $gatewayInfo['password'],
                    $gatewayInfo['acceptor_id'],
                    $gatewayInfo['public_key']
                );
                $gateway
                    ->handleResultNotOkClosure(function () use (&$res) {
                        $res = [false, GATEWAY_INVALID_PARAMETERS_MESSAGE, null];
                    })->sendAdviceOkClosure(
                        function (
                            IEvent                       $event,
                            IranKishAdviceResultProvider $adviceProvider,
                            IranKishHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $orderModel, $orderReserveModel, $gatewayCode, $flow, &$res) {
                            $gatewayModel->update([
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
                            ], 'code=:code', ['code' => $gatewayCode]);
                            $orderModel->update([
                                'payment_status' => PAYMENT_STATUS_SUCCESS,
                                'payed_at' => time(),
                            ], 'code=:code', ['code' => $flow['order_code']]);
                            $orderReserveModel->delete('order_code=:code', ['code' => $flow['order_code']]);

                            $res = [true, GATEWAY_SUCCESS_MESSAGE, $adviceProvider->getResultSystemTraceAuditNumber('')];
                        }
                    )->sendAdviceNotOkClosure(
                        function (
                            IEvent                       $event,
                                                         $code,
                                                         $msg,
                            IranKishAdviceResultProvider $adviceProvider,
                            IranKishHandlerProvider      $resultProvider
                        ) use ($gatewayModel, $gatewayCode, &$res) {
                            $gatewayModel->update([
                                'payment_code' => $adviceProvider->getResultSystemTraceAuditNumber(''),
                                'status' => $code,
                                'msg' => $msg,
                                'in_step' => PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT,
                                'payment_date' => time(),
                                'extra_info' => json_encode([
                                    'result' => $resultProvider->getParameters(),
                                    'advice' => $adviceProvider->getParameters(),
                                ]),
                            ], 'code=:code AND is_success=:suc', ['code' => $gatewayCode, 'suc' => DB_NO]);

                            $res = [false, GATEWAY_ERROR_MESSAGE, $adviceProvider->getResultSystemTraceAuditNumber('')];
                        }
                    )->sendAdvice();
                break;
        }

        return $res;
    }

    /**
     * @param int $length
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private static function getUniqueGatewayFlowCode(int $length = 20): string
    {
        /**
         * @var GatewayModel $flowModel
         */
        $flowModel = container()->get(GatewayModel::class);
        do {
            $uniqueStr = StringUtil::randomString($length, StringUtil::RS_NUMBER | StringUtil::RS_LOWER_CHAR);
        } while ($flowModel->count('code=:code', ['code' => $uniqueStr]));
        return $uniqueStr;
    }

    /**
     * @param $type
     * @param $code
     * @param $msg
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private static function logConnectionError($type, $code, $msg)
    {
        logger_gateway()->error([
            'section' => 'product',
            'gateway_type' => $type,
            'code' => $code,
            'message' => $msg,
        ]);
    }
}
