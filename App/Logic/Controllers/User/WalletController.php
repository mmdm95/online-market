<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Forms\User\Wallet\WalletCharge;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\SMSUtil;
use App\Logic\Utils\WalletChargeUtil;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestResultProvider;
use Sim\SMS\Exceptions\SMSException;

class WalletController extends AbstractUserController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function index()
    {
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        /**
         * @var PaymentMethodModel $methodModel
         */
        $methodModel = container()->get(PaymentMethodModel::class);

        $user = $this->getDefaultArguments()['user'];

        $wallet_flow = $walletFlowModel->get(
            [
                'order_code',
                'deposit_price',
                'deposit_type_title',
                'deposit_at',
            ],
            'username=:username',
            ['username' => $user['username']],
            ['id DESC']
        );

        $paymentMethods = $methodModel->get(
            ['code', 'title', 'image'],
            'publish=:pub AND method_type NOT IN (:t1, :t2, :t3)',
            [
                'pub' => DB_YES,
                't1' => METHOD_TYPE_WALLET,
                't2' => METHOD_TYPE_IN_PLACE,
                't3' => METHOD_TYPE_RECEIPT,
            ]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/wallet/index');
        return $this->render([
            'payment_methods' => $paymentMethods,
            'wallet_flow' => $wallet_flow,
        ]);
    }

    /**
     * @param $type
     * @param $method
     * @param $code
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function chargeResult($type, $method, $code)
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/user/wallet/charge');

        [$res, $msg, $paymentCode] = WalletChargeUtil::getResultProvider($type, $method, $code);

        if ($res) {
            /**
             * @var WalletFlowModel $walletFlowModel
             */
            $walletFlowModel = container()->get(WalletFlowModel::class);
            $info = $walletFlowModel->getFirstUserAndWalletInfoByCode($code);
            $username = $info['username'] ?? null;
            $walletCode = $info['order_code'] ?? null;
            $balance = $info['balance'] ?? null;
            $depositPrice = $info['deposit_price'] ?? 0;
            if (!is_null($username) && !is_null($balance)) {
                // send success wallet charge sms
                $body = replaced_sms_body(SMS_TYPE_WALLET_CHARGE, [
                    SMS_REPLACEMENTS['mobile'] => $username,
                    SMS_REPLACEMENTS['balance'] => local_number(number_format($balance)) . ' تومان',
                ]);
                try {
                    $smsRes = SMSUtil::send([$username], $body);
                    SMSUtil::logSMS([$username], $body, $smsRes, SMS_LOG_TYPE_WALLET_CHARGE, SMS_LOG_SENDER_SYSTEM);
                } catch (DependencyException|NotFoundException|SMSException $e) {
                    // do nothing
                }
            }
        }

        return $this->render([
            'result' => $res,
            'message' => $msg,
            'wallet_code' => $walletCode ?? null,
            'payment_code' => $paymentCode,
            'price' => $depositPrice ?? 0,
        ]);
    }

    /**
     * @return void
     */
    public function chargeCheck()
    {
        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                // check gateway code and get information about it
                $gatewayCode = input()->post('inp-wallet-payment-method-option')->getValue();
                if (null == $gatewayCode) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('روش پرداخت انتخاب شده نامعتبر است.');
                    response()->json($resourceHandler->getReturnData());
                }
                /**
                 * @var PaymentMethodModel $methodModel
                 */
                $methodModel = container()->get(PaymentMethodModel::class);
                $gatewayMethod = $methodModel->getFirst([
                    'code',
                    'title',
                    'method_type',
                    'meta_parameters',
                ], 'code=:code AND publish=:pub', [
                    'code' => $gatewayCode,
                    'pub' => DB_YES,
                ]);
                if (!count($gatewayMethod)) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('روش پرداخت انتخاب شده نامعتبر است.');
                    response()->json($resourceHandler->getReturnData());
                }
                // store to flash session to retrieve in form store
                session()->setFlash(SESSION_GATEWAY_CHARGE_RECORD, $gatewayMethod);

                // issue a factor for order
                $formHandler = new GeneralAjaxFormHandler();
                $resourceHandler = $formHandler
                    ->handle(WalletCharge::class);

                // return needed response for gateway redirection if everything is ok
                if ($resourceHandler->getReturnData()['type'] == RESPONSE_TYPE_SUCCESS) {
                    // connect to gateway and get needed info
                    [$gatewayInfo, $infoRes] = WalletChargeUtil::getGatewayInfo(
                        $gatewayMethod['method_type'],
                        $gatewayMethod['code'],
                        json_decode(cryptographer()->decrypt($gatewayMethod['meta_parameters']), true)
                    );

                    if (
                        $infoRes &&
                        !empty($gatewayInfo) &&
                        in_array(
                            (int)$gatewayMethod['method_type'], [
                                METHOD_TYPE_GATEWAY_BEH_PARDAKHT,
                                METHOD_TYPE_GATEWAY_IDPAY,
                                METHOD_TYPE_GATEWAY_MABNA,
                                METHOD_TYPE_GATEWAY_ZARINPAL,
                                METHOD_TYPE_GATEWAY_SADAD,
                                METHOD_TYPE_GATEWAY_TAP,
                            ]
                        )
                    ) {
                        $canContinue = true;

                        $url = '#';
                        $inputs = [];
                        $redirect = false;

                        // TODO: add other gateway cases and create inputs according to them
                        // ...

                        switch ((int)$gatewayMethod['method_type']) {
                            case METHOD_TYPE_GATEWAY_SADAD:
                            case METHOD_TYPE_GATEWAY_TAP:
                                /**
                                 * @var SadadRequestResultProvider|TapRequestResultProvider $gatewayInfo
                                 */
                                $url = $gatewayInfo->getUrl();
                                $redirect = true;
                                break;
                            default:
                                $resourceHandler
                                    ->type(RESPONSE_TYPE_WARNING)
                                    ->data('روش پرداخت انتخاب شده نامعتبر است!');
                                $canContinue = false;
                                break;
                        }

                        if ($canContinue) {
                            // remove charge  array that stored before to prevent any error due to traces
                            session()->remove(SESSION_WALLET_CHARGE_ARR_INFO);

                            $resourceHandler
                                ->type(RESPONSE_TYPE_SUCCESS)
                                ->data([
                                    'redirect' => $redirect,
                                    'url' => $url,
                                    'inputs' => $inputs,
                                ]);
                        } else {
                            $this->removeWalletChargeFlow();
                        }
                    } else {
                        $this->removeWalletChargeFlow();
                        $resourceHandler
                            ->type(RESPONSE_TYPE_ERROR)
                            ->errorMessage('خطا در ارتباط با درگاه بانک، لطفا دوباره تلاش کنید.');
                    }
                }
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function removeWalletChargeFlow()
    {
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        $chargeArr = session()->get(SESSION_WALLET_CHARGE_ARR_INFO);
        // reverse all db action
        $walletFlowModel->delete('order_code=:code', ['code' => $chargeArr['order_code']]);
        session()->remove(SESSION_WALLET_CHARGE_ARR_INFO);
    }
}
