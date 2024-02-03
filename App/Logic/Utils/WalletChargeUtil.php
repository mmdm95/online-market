<?php

namespace App\Logic\Utils;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerFactory;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Models\GatewayModel;
use App\Logic\Models\PaymentMethodModel;
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

class WalletChargeUtil
{
    /**
     * @param array $methodInfo
     * @return array|null
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function getGatewayConnectionOptions(array $methodInfo): ?array
    {
        // connect to gateway and get needed info
        [$gatewayInfo, $infoRes] = self::getGatewayInfo(
            $methodInfo['method_type'],
            $methodInfo['code'],
            json_decode(cryptographer()->decrypt($methodInfo['meta_parameters']), true)
        );

        if (
            !$infoRes ||
            empty($gatewayInfo) ||
            !in_array(
                (int)$methodInfo['method_type'], [
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
        $walletArr = session()->get(SESSION_WALLET_CHARGE_ARR_INFO);
        if (is_null($walletArr) || !count($walletArr)) return [null, false];

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');

        $newCode = self::getUniqueGatewayFlowCode();
        $userId = $auth->getCurrentUser()['id'] ?? 0;
        $orderCode = $walletArr['order_code'];
        $price = $walletArr['deposit_price'];
        $callbackUrl = rtrim(get_base_url(), '/') . url('user.wallet.finish', [
                'type' => (int)$gatewayType,
                'method' => $gatewayCode,
                'code' => $newCode,
            ])->getRelativeUrlTrimmed();

        $handler = PaymentHandlerFactory::getInstance((int)$gatewayType, $gatewayInfo);
        $output = $handler->connection(new PaymentHandlerConnectionInput(
            $userId, $orderCode, $price, $callbackUrl, $newCode
        ));

        return [$output->getConnectionResponse(), $output->getConnectionResult()];
    }

    /**
     * Check result of gateway and take action
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
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);
        /**
         * @var PaymentMethodModel $methodModel
         */
        $methodModel = container()->get(PaymentMethodModel::class);

        $res = [false, 'کد شارژ کیف پول نامعتبر می‌باشد.', null];

        $flow = $gatewayModel->getFirst([
            'order_code',
            'method_type',
            'is_success',
        ], 'code=:code', ['code' => $gatewayCode]);

        // check flow and see if it is a valid gateway flow record
        if (!count($flow)) {
            return $res;
        }

        $walletOrder = $walletFlowModel->getFirst(['*'], 'order_code=:code', ['code' => $flow['order_code']]);

        // check the order and see if it is a valid order
        if (!count($walletOrder)) {
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

        $handler = PaymentHandlerFactory::getInstance((int)$type, $gatewayInfo);
        $output = $handler
            ->onSuccessResultEvent(function (
                IEvent                    $event,
                PaymentHandlerResultInput $input
            ) use ($walletModel, $walletOrder) {
                $walletModel->increaseBalance($walletOrder['username'], $input->getPrice());
            })
            ->result(new PaymentHandlerResultInput(
                $gatewayCode, $flow['order_code'], $walletOrder['deposit_price']
            ));

        return [$output->getResult(), $output->getMessage(), $output->getReferenceId()];
    }

    /**
     * @param int $length
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getUniqueWalletOrderCode(int $length = 18): string
    {
        /**
         * @var WalletFlowModel $flowModel
         */
        $flowModel = container()->get(WalletFlowModel::class);
        do {
            $uniqueStr = StringUtil::randomString($length, StringUtil::RS_NUMBER, ['0']);
        } while ($flowModel->count('order_code=:code', ['code' => $uniqueStr]));
        return $uniqueStr;
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
}
