<?php

namespace App\Logic\Utils;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerFactory;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Models\DepositTypeModel;
use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderPaymentModel;
use App\Logic\Models\OrderReserveModel;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\SendMethodModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Models\WalletModel;
use DI\DependencyException;
use DI\NotFoundException;
use Sim\Auth\DBAuth;
use Sim\Event\Interfaces\IEvent;
use Sim\Payment\Providers\BehPardakht\BehPardakhtRequestResultProvider;
use Sim\Payment\Providers\IranKish\IranKishRequestResultProvider;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestResultProvider;
use Sim\Utils\StringUtil;

class PaymentUtil
{
    /**
     * @param array $methodInfo
     * @return array|string|null
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function getGatewayConnectionOptions(array $methodInfo)
    {
        if ($methodInfo['method_type'] == METHOD_TYPE_WALLET) {
            [$gatewayInfo, $infoRes] = self::walletCheck($methodInfo['code']);
        } else {
            // connect to gateway and get needed info
            [$gatewayInfo, $infoRes] = self::getGatewayInfo(
                $methodInfo['method_type'],
                $methodInfo['code'],
                json_decode(cryptographer()->decrypt($methodInfo['meta_parameters']), true)
            );
        }

        if (
            !$infoRes ||
            empty($gatewayInfo) ||
            !in_array(
                (int)$methodInfo['method_type'], [
                    METHOD_TYPE_WALLET,
                    //
                    METHOD_TYPE_GATEWAY_BEH_PARDAKHT,
                    METHOD_TYPE_GATEWAY_IDPAY,
                    METHOD_TYPE_GATEWAY_MABNA,
                    METHOD_TYPE_GATEWAY_ZARINPAL,
                    METHOD_TYPE_GATEWAY_SADAD,
                    METHOD_TYPE_GATEWAY_TAP,
                    METHOD_TYPE_GATEWAY_IRAN_KISH,
                ]
            )
        ) {
            if (!$infoRes && $methodInfo['method_type'] == METHOD_TYPE_WALLET) {
                return $gatewayInfo['error']['message'] ?? '';
            }
            return null;
        }

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        $user = $auth->getCurrentUser();

        $url = '#';
        $inputs = [];
        $redirect = false;
        $isMultipart = false;

        switch ((int)$methodInfo['method_type']) {
            case METHOD_TYPE_WALLET:
                $url = $gatewayInfo['url'];
                $redirect = true;
                break;
            case METHOD_TYPE_GATEWAY_BEH_PARDAKHT:
                /**
                 * @var BehPardakhtRequestResultProvider $gatewayInfo
                 */
                $refId = $gatewayInfo->getRefId();
                $url = $gatewayInfo->getUrl();
                $inputs = [
                    [
                        'name' => 'RefId',
                        'value' => $refId,
                    ],
                ];
                if ($user['username']) {
                    $inputs[] = [
                        'name' => 'MobileNo',
                        'value' => $user['username'],
                    ];
                }
                break;
            case METHOD_TYPE_GATEWAY_SADAD:
            case METHOD_TYPE_GATEWAY_TAP:
                /**
                 * @var SadadRequestResultProvider|TapRequestResultProvider $gatewayInfo
                 */
                $url = $gatewayInfo->getUrl();
                $redirect = true;
                break;
            case METHOD_TYPE_GATEWAY_IRAN_KISH:
                /**
                 * @var IranKishRequestResultProvider $gatewayInfo
                 */
                $inputs = [
                    [
                        'name' => 'tokenIdentity',
                        'value' => $gatewayInfo->getToken(),
                    ],
                ];
                $url = $gatewayInfo->getUrl();
                $isMultipart = true;
                break;
        }

        return compact('url', 'inputs', 'redirect', 'isMultipart');
    }

    /**
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
        $orderArr = session()->get(SESSION_ORDER_ARR_INFO);
        if (is_null($orderArr) || !count($orderArr)) {
            $orderArr = session()->get(SESSION_REPAY_ORDER_RECORD);
            if (is_null($orderArr) || !count($orderArr)) {
                return [null, false];
            }
        }

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var OrderPaymentModel $orderPayModel
         */
        $orderPayModel = container()->get(OrderPaymentModel::class);

        $newCode = self::getUniqueGatewayFlowCode();
        // set new unique code in session to use it outside and other places
        session()->set(SESSION_REPAY_GATEWAY_UNIQUE_CODE, $newCode);

        $userId = $auth->getCurrentUser()['id'] ?? 0;
        $orderCode = $orderArr['code'];
        $price = $orderArr['final_price'];
        $callbackUrl = rtrim(get_base_url(), '/') . url('home.finish', [
                'type' => (int)$gatewayType,
                'method' => $gatewayCode,
                'code' => $newCode,
            ])->getRelativeUrlTrimmed();
        $paymentMethodInfo = [
            'method_code' => $orderArr['method_code'],
            'method_title' => $orderArr['method_title'],
            'method_type' => $orderArr['method_type'],
        ];

        $handler = PaymentHandlerFactory::getInstance((int)$gatewayType, $gatewayInfo);
        $output = $handler
            ->onSuccessConnectionEvent(function (
                IEvent                        $event,
                PaymentHandlerConnectionInput $input
            ) use ($orderPayModel, $paymentMethodInfo) {
                $orderPayModel->insert([
                    'code' => $input->getUniqueCode(),
                    'order_code' => $input->getOrderCode(),
                    'method_code' => $paymentMethodInfo['method_code'],
                    'method_title' => $paymentMethodInfo['method_title'],
                    'method_type' => $paymentMethodInfo['method_type'],
                    'payment_status' => PAYMENT_STATUS_WAIT,
                    'created_at' => time(),
                ]);
            })
            ->connection(new PaymentHandlerConnectionInput(
                $userId, $orderCode, $price, $callbackUrl, $newCode
            ));

        return [$output->getConnectionResponse(), $output->getConnectionResult()];
    }

    /**
     * Check result of gateway and take action
     *
     * It'll return an array with below items:
     * <code>
     *     [
     *         status -> true|false,
     *         message -> string,
     *         referenceId -> from gateway if it was paid
     *     ]
     * </code>
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
         * @var PaymentMethodModel $methodModel
         */
        $methodModel = container()->get(PaymentMethodModel::class);
        /**
         * @var OrderPaymentModel $orderPayModel
         */
        $orderPayModel = container()->get(OrderPaymentModel::class);
        /**
         * @var OrderReserveModel $orderReserveModel
         */
        $orderReserveModel = container()->get(OrderReserveModel::class);

        $res = [false, 'سفارش نامعتبر می‌باشد.', null];

        $flow = $gatewayModel->getFirst([
            'order_code',
            'payment_code',
            'is_success',
            'method_type',
        ], 'code=:code', ['code' => $gatewayCode]);

        // check flow and see if it is a valid gateway flow record
        if (!count($flow)) {
            return $res;
        }

        $order = $orderModel->getFirst(['*'], 'code=:code', ['code' => $flow['order_code']]);

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
            return [true, GATEWAY_SUCCESS_MESSAGE, $flow['payment_code']];
        }

        $gatewayInfo = json_decode(cryptographer()->decrypt($method['meta_parameters']), true);

        $handler = PaymentHandlerFactory::getInstance((int)$type, $gatewayInfo);
        $output = $handler
            ->onSuccessResultEvent(function (
                IEvent                    $event,
                PaymentHandlerResultInput $input
            ) use ($orderPayModel, $orderModel, $orderReserveModel) {
                $orderPayModel->update([
                    'payment_status' => PAYMENT_STATUS_SUCCESS,
                ], 'code=:code', ['code' => $input->getGatewayCode()]);

                $orderModel->update([
                    'payment_status' => PAYMENT_STATUS_SUCCESS,
                    'payed_at' => time(),
                ], 'code=:code', ['code' => $input->getOrderCode()]);

                $orderReserveModel->delete('order_code=:code', ['code' => $input->getOrderCode()]);
            })
            ->onFailedResultEvent(function (
                IEvent                    $event,
                PaymentHandlerResultInput $input
            ) use ($orderPayModel) {
                $orderPayModel->update([
                    'payment_status' => PAYMENT_STATUS_FAILED,
                ], 'code=:code', ['code' => $input->getGatewayCode()]);
            })
            ->result(new PaymentHandlerResultInput(
                $gatewayCode, $flow['order_code'], $order['final_price']
            ));

        return [$output->getResult(), $output->getMessage(), $output->getReferenceId()];
    }

    /**
     * @param string $methodCode
     * @return array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function walletCheck(string $methodCode): array
    {
        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        /**
         * @var DepositTypeModel $depositModel
         */
        $depositModel = container()->get(DepositTypeModel::class);

        $orderArr = session()->get(SESSION_ORDER_ARR_INFO);
        if (is_null($orderArr) || !count($orderArr)) {
            return [[
                'error' => [
                    'code' => 101,
                    'message' => 'سفارش نامعتبر می‌باشد.',
                ],
            ], false];
        }

        $currentUser = get_current_authenticated_user(auth_home());
        if (!count($currentUser)) {
            return [[
                'error' => [
                    'code' => 102,
                    'message' => 'کاربر نامعتبر می‌باشد.',
                ],
            ], false];
        }

        // get wallet balance and check if order can pay by wallet
        $wallet = $walletModel->getWalletInfo(
            'u.username=:un', ['un' => $currentUser['username']],
            ['w.id DESC'], 1, 0, ['w.balance', 'w.is_available']
        )[0] ?? [];

        if (!count($wallet) || $wallet['is_available'] != DB_YES) {
            return [[
                'error' => [
                    'code' => 103,
                    'message' => 'کیف پول شما در دسترس نمی‌باشد، برای اطلاعات بیشتر با ما در تماس باشد.',
                ],
            ], false];
        }

        if ($wallet['balance'] < $orderArr['final_price']) {
            return [[
                'error' => [
                    'code' => 104,
                    'message' => 'مبلغ کیف پول شما کافی نمی‌باشد، لطفا ابتدا کیف پول خود را در پنل کاربری شارژ نمایید.',
                ],
            ], false];
        }

        // put a wallet flow in database and decrease the balance
        $depositTitle = $depositModel->getFirst(['title'], 'code=:code', ['code' => WALLET_PAYMENT_CODE])['title'] ?? 'نامشخص';
        $res = $walletFlowModel->payOrderWithWallet([
            'order_code' => $orderArr['code'],
            'username' => $currentUser['username'],
            'payer_id' => $currentUser['id'],
            'deposit_price' => -(int)$orderArr['final_price'],
            'deposit_type_code' => WALLET_PAYMENT_CODE,
            'deposit_type_title' => $depositTitle,
            'deposit_at' => time(),
        ]);

        if (!$res) {
            return [[
                'error' => [
                    'code' => 105,
                    'message' => 'عملیات پرداخت با خطا روبرو شد لطفا دوباره تلاش نمایید.',
                ],
            ], false];
        }

        return [[
            'error' => [
                'code' => null,
                'message' => null,
            ],
            'url' => url('home.wallet.payment', ['code' => $orderArr['code']])->getRelativeUrl(),
        ], true];
    }

    /**
     * @param $orderCode
     * @return bool
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function walletPayConfirmation($orderCode): bool
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderReserveModel $orderReserveModel
         */
        $orderReserveModel = container()->get(OrderReserveModel::class);

        $res = $orderModel->update([
            'payment_status' => PAYMENT_STATUS_SUCCESS,
            'payed_at' => time(),
        ], 'code=:code', ['code' => $orderCode]);
        $res2 = $orderReserveModel->delete('order_code=:code', ['code' => $orderCode]);

        return $res && $res2;
    }

    /**
     * @param $methodCode
     * @return array|null
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function validateAndGetPaymentMethod($methodCode): ?array
    {
        if (is_null($methodCode)) return null;

        /**
         * @var PaymentMethodModel $payMethodModel
         */
        $payMethodModel = container()->get(PaymentMethodModel::class);
        $gatewayMethod = $payMethodModel->getFirst([
            'code',
            'title',
            'method_type',
            'meta_parameters',
        ], 'code=:code AND publish=:pub', [
            'code' => $methodCode,
            'pub' => DB_YES,
        ]);

        return !count($gatewayMethod) ? null : $gatewayMethod;
    }

    /**
     * @param $methodCode
     * @return array|null
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function validateAndGetSendMethod($methodCode): ?array
    {
        if (is_null($methodCode)) return null;

        /**
         * @var SendMethodModel $sendMethodModel
         */
        $sendMethodModel = container()->get(SendMethodModel::class);
        $sendMethod = $sendMethodModel->getFirst([
            'id',
            'code',
            'title',
            '`desc`',
            'price',
            'determine_price_by_location',
        ], 'code=:code AND publish=:pub', [
            'code' => $methodCode,
            'pub' => DB_YES,
        ]);

        return !count($sendMethod) ? null : $sendMethod;
    }

    /**
     * @param int $length
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getUniqueGatewayFlowCode(int $length = 20): string
    {
        /**
         * @var GatewayModel $flowModel
         */
        $flowModel = container()->get(GatewayModel::class);
        do {
            $uniqueStr = StringUtil::randomString($length, StringUtil::RS_NUMBER);
        } while ($flowModel->count('code=:code', ['code' => $uniqueStr]));
        return $uniqueStr;
    }
}
