<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Forms\User\Wallet\WalletCharge;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\PaymentUtil;
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
                $gatewayMethod = PaymentUtil::validateAndGetPaymentMethod($gatewayCode);
                if (is_null($gatewayMethod)) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('روش پرداخت انتخاب شده نامعتبر است.');
                    response()->json($resourceHandler->getReturnData());
                }
                // store to flash session to retrieve in form store
                session()->setFlash(SESSION_GATEWAY_CHARGE_RECORD, $gatewayMethod);

                // issue a factor for wallet interaction
                $formHandler = new GeneralAjaxFormHandler();
                $resourceHandler = $formHandler
                    ->handle(WalletCharge::class);

                // return needed response for gateway redirection if everything is ok
                if ($resourceHandler->getReturnData()['type'] == RESPONSE_TYPE_SUCCESS) {
                    $connOptions = WalletChargeUtil::getGatewayConnectionOptions($gatewayMethod);

                    if (is_array($connOptions)) {
                        $url = $connOptions['url'];
                        $inputs = $connOptions['inputs'];
                        $redirect = $connOptions['redirect'];
                        $isMultipart = $connOptions['isMultipart'];

                        // remove charge  array that stored before to prevent any error due to traces
                        session()->remove(SESSION_WALLET_CHARGE_ARR_INFO);

                        $resourceHandler
                            ->type(RESPONSE_TYPE_SUCCESS)
                            ->data([
                                'redirect' => $redirect,
                                'url' => $url,
                                'inputs' => $inputs,
                                'multipart_form' => $isMultipart,
                            ]);
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
