<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\Order\CheckoutForm;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Middlewares\Logic\AllowCheckoutMiddleware;
use App\Logic\Models\AddressModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\PaymentUtil;
use App\Logic\Utils\PostPriceUtil;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestResultProvider;

class CheckoutController extends AbstractHomeController
{
    public function __construct()
    {
        parent::__construct();

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');

        // get current user info
        $user = $userModel->getFirst(['*'], 'id=:id', ['id' => $auth->getCurrentUser()['id'] ?? 0]);
        unset($user['password']);
        $user['roles'] = $userModel->getUserRoles($user['id'], null, [], ['r.*']);

        $this->setDefaultArguments(array_merge($this->getDefaultArguments(), [
            'user' => $user,
        ]));
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function checkout()
    {
        $this->setMiddleWare(AllowCheckoutMiddleware::class);
        $this->middlewareResult();

        /**
         * @var PaymentMethodModel $methodModel
         */
        $methodModel = container()->get(PaymentMethodModel::class);
        /**
         * @var AddressModel $addressModel
         */
        $addressModel = container()->get(AddressModel::class);

        $user = $this->getDefaultArguments()['user'];

        $paymentMethods = $methodModel->get(['code', 'title', 'image'], 'publish=:pub', ['pub' => DB_YES]);
        $addresses = $addressModel->getUserAddresses(
            ['u_addr.*', 'c.name AS city_name', 'p.name AS province_name']
            , 'u_addr.user_id=:uId', ['uId' => $user['id']]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/order/checkout');
        return $this->render([
            'payment_methods' => $paymentMethods,
            'addresses' => $addresses,
        ]);
    }

    /**
     * Issue a factor, reserve products and make connection to gateway
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function issuingFactorNConnectToGateway()
    {
        $resourceHandler = new ResourceHandler();

        $this->setMiddleWare(AllowCheckoutMiddleware::class, [false]);
        $this->middlewareResult();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                // check gateway code and get information about it
                $gatewayCode = input()->post('payment_method_option')->getValue();
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
                session()->setFlash(SESSION_GATEWAY_RECORD, $gatewayMethod);

                // issue a factor for order
                $formHandler = new GeneralAjaxFormHandler();
                $resourceHandler = $formHandler
                    ->handle(CheckoutForm::class);

                // return needed response for gateway redirection if everything is ok
                if ($resourceHandler->getReturnData()['type'] == RESPONSE_TYPE_SUCCESS) {
                    // connect to gateway and get needed info
                    [$gatewayInfo, $infoRes] = PaymentUtil::getGatewayInfo(
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
                            // remove all cart items
                            cart()->destroy();
                            // remove order array that stored before to prevent any error due to traces
                            session()->remove(SESSION_ORDER_ARR_INFO);
                            // remove other traces
                            session()->remove(SESSION_APPLIED_COUPON_CODE);
                            session()->remove(SESSION_APPLIED_POST_PRICE);

                            $resourceHandler
                                ->type(RESPONSE_TYPE_SUCCESS)
                                ->data([
                                    'redirect' => $redirect,
                                    'url' => $url,
                                    'inputs' => $inputs,
                                ]);
                        } else {
                            $this->removeIssuedFactor();
                        }
                    } else {
                        $this->removeIssuedFactor();
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
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * Calculate post price
     *
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function calculateSendPrice()
    {
        $resourceHandler = new ResourceHandler();

        $this->setMiddleWare(AllowCheckoutMiddleware::class, [false]);
        $this->middlewareResult();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                /**
                 * @var ProvinceModel $provinceModel
                 */
                $provinceModel = container()->get(ProvinceModel::class);

                $cityId = input()->post('city')->getValue();
                $provinceId = input()->post('province')->getValue();
                $province = $provinceModel->getFirst(['post_price_order'], 'id=:id', ['id' => $provinceId]);
                $ownProvince = $provinceModel->getFirst(['post_price_order'], 'id=:id', ['id' => config()->get('settings.store_province.value')]);

                if (0 != count($province) && 0 != count($ownProvince)) {
                    // calculate weights
                    $items = cart()->getItems();
                    $totalPrice = 0.0;
                    $weight = 0.0;
                    foreach ($items as $item) {
                        $weight += (float)$item['weight'];
                        $totalPrice += $item['qnt'] * (float)get_discount_price($item)[0];
                    }

                    if ($totalPrice > (config()->get('settings.min_free_price.value') ?: PHP_INT_MAX)) {
                        $price = 0;
                    } elseif (
                        (config()->get('settings.store_city.value') ?: -1) == $cityId &&
                        (config()->get('settings.store_province.value') ?: -1) == $provinceId
                    ) {
                        $price = config()->get('settings.current_city_post_price.value');
                    } else {
                        $postPriceCalcMode = config()->get('settings.post_price_calculation_mode.value');
                        if ($postPriceCalcMode == SEND_PRICE_CALCULATION_STATIC) {
                            $price = config()->get('settings.static_post_price.value') ?: 0;
                            //
                            if (0 == $price) {
                                $postUtil = new PostPriceUtil($ownProvince['post_price_order'], $province['post_price_order'], $weight);
                                $price = $postUtil->post();
                            }
                        } else {
                            $postUtil = new PostPriceUtil($ownProvince['post_price_order'], $province['post_price_order'], $weight);
                            $price = $postUtil->post();
                        }
                    }
                    session()->set(SESSION_APPLIED_POST_PRICE, $price);
                    $resourceHandler
                        ->type(RESPONSE_TYPE_SUCCESS)
                        ->data('هزینه ارسال اعمال شد.');
                } else {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('استان وارد شده نامعتبر است.');
                }
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function removeIssuedFactor()
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        $orderArr = session()->get(SESSION_ORDER_ARR_INFO);
        // reverse all db action
        $orderModel->removeIssuedFactor($orderArr['code']);
        session()->remove(SESSION_ORDER_ARR_INFO);
    }
}
