<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\Order\CheckoutForm;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Middlewares\Logic\AllowCheckoutMiddleware;
use App\Logic\Models\AddressCompanyModel;
use App\Logic\Models\AddressModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\SendMethodModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\PaymentUtil;
use App\Logic\Utils\PostPriceUtil;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

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
        if (count($user)) {
            unset($user['password']);
            $user['roles'] = $userModel->getUserRoles($user['id'], null, [], ['r.*']);

            $this->setDefaultArguments(array_merge($this->getDefaultArguments(), [
                'user' => $user,
            ]));
        }
    }

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
    public function checkout()
    {
        $this->setMiddleWare(AllowCheckoutMiddleware::class);
        $this->middlewareResult();

        /**
         * @var PaymentMethodModel $payMethodModel
         */
        $payMethodModel = container()->get(PaymentMethodModel::class);
        /**
         * @var SendMethodModel $sendMethodModel
         */
        $sendMethodModel = container()->get(SendMethodModel::class);
        /**
         * @var AddressModel $addressModel
         */
        $addressModel = container()->get(AddressModel::class);
        /**
         * @var AddressCompanyModel $addressCompanyModel
         */
        $addressCompanyModel = container()->get(AddressCompanyModel::class);

        $user = $this->getDefaultArguments()['user'];

        $paymentMethods = $payMethodModel->get(['code', 'title', 'image', 'method_type'], 'publish=:pub', ['pub' => DB_YES]);
        $sendMethods = $sendMethodModel->get(
            ['code', 'title', '`desc`', 'image'],
            'publish=:pub',
            ['pub' => DB_YES],
            ['priority ASC', 'id ASC']
        );
        $addresses = $addressModel->getUserAddresses(
            ['u_addr.*', 'c.name AS city_name', 'p.name AS province_name']
            , 'u_addr.user_id=:uId', ['uId' => $user['id']]
        );
        $addressesCompany = $addressCompanyModel->getUserAddresses(
            ['uc_addr.*', 'c.name AS city_name', 'p.name AS province_name']
            , 'uc_addr.user_id=:uId', ['uId' => $user['id']]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/order/checkout');
        return $this->render([
            'payment_methods' => $paymentMethods,
            'send_methods' => $sendMethods,
            'addresses' => $addresses,
            'addresses_company' => $addressesCompany,
        ]);
    }

    /**
     * Issue a factor, reserve products and make connection to gateway
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function issuingFactorNConnectToGateway()
    {
        $resourceHandler = new ResourceHandler();

        $this->setMiddleWare(AllowCheckoutMiddleware::class, [false]);
        // it'll send a json response back to browser inside the middleware
        $this->middlewareResult();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                //------------------------------------------------------------------
                // check gateway code and get information about it
                //------------------------------------------------------------------
                $gatewayCode = input()->post('payment_method_option')->getValue();
                $gatewayMethod = PaymentUtil::validateAndGetPaymentMethod($gatewayCode);
                if (is_null($gatewayMethod)) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('روش پرداخت انتخاب شده نامعتبر است.');
                    response()->json($resourceHandler->getReturnData());
                }
                // store to flash session to retrieve in form store
                session()->setFlash(SESSION_GATEWAY_RECORD, $gatewayMethod);

                //------------------------------------------------------------------
                // check send method and get information about it
                //------------------------------------------------------------------
                $sendMethodCode = input()->post('send_method_option')->getValue();
                $sendMethod = PaymentUtil::validateAndGetSendMethod($sendMethodCode);
                if (is_null($sendMethodCode)) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('روش ارسال انتخاب شده نامعتبر است.');
                    response()->json($resourceHandler->getReturnData());
                }
                // store to flash session to retrieve in form store
                session()->setFlash(SESSION_SEND_METHOD_RECORD, $sendMethod);

                //------------------------------------------------------------------

                // issue a factor for order
                $formHandler = new GeneralAjaxFormHandler();
                $resourceHandler = $formHandler
                    ->handle(CheckoutForm::class);

                // return needed response for gateway redirection if everything is ok
                if ($resourceHandler->getReturnData()['type'] == RESPONSE_TYPE_SUCCESS) {
                    $connOptions = PaymentUtil::getGatewayConnectionOptions($gatewayMethod);

                    if (is_array($connOptions)) {
                        $url = $connOptions['url'];
                        $inputs = $connOptions['inputs'];
                        $redirect = $connOptions['redirect'];
                        $isMultipart = $connOptions['isMultipart'];

                        // remove all cart items
                        cart()->destroy();
                        // remove generated unique code to prevent error on repay
                        session()->remove(SESSION_REPAY_GATEWAY_UNIQUE_CODE);
                        // remove order array that stored before to prevent any error due to traces
                        session()->remove(SESSION_ORDER_ARR_INFO);
                        // remove other traces
                        session()->remove(SESSION_APPLIED_COUPON_CODE);
                        session()->remove(SESSION_ZERO_POST_PRICE);
                        session()->remove(SESSION_APPLIED_POST_PRICE);

                        $resourceHandler
                            ->type(RESPONSE_TYPE_SUCCESS)
                            ->data([
                                'redirect' => $redirect,
                                'url' => $url,
                                'inputs' => $inputs,
                                'multipart_form' => $isMultipart,
                            ]);
                    } else {
                        $msg = 'خطا در ارتباط با درگاه بانک، لطفا دوباره تلاش کنید.';
                        if (is_string($connOptions) && !empty($connOptions)) {
                            $msg = $connOptions;
                        }

                        $this->removeIssuedFactor();
                        $resourceHandler
                            ->type(RESPONSE_TYPE_ERROR)
                            ->errorMessage($msg);
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
     * Calculate post price
     *
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws DependencyException
     * @throws NotFoundException
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

                $sendMethodCode = input()->post('send_method_option')->getValue();
                $sendMethod = PaymentUtil::validateAndGetSendMethod($sendMethodCode);
                if (is_null($sendMethodCode)) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('روش ارسال انتخاب شده نامعتبر است.');
                    response()->json($resourceHandler->getReturnData());
                }

                $cityId = input()->post('city')->getValue();
                $provinceId = input()->post('province')->getValue();
                $province = $provinceModel->getFirst(['post_price_order'], 'id=:id', ['id' => $provinceId]);
                $ownProvince = $provinceModel->getFirst(['post_price_order'], 'id=:id', ['id' => config()->get('settings.store_province.value')]);

                $canContinue = false;

                // calculate weights
                $items = cart()->getItems();
                $totalPrice = 0.0;
                $weight = 0.0;
                foreach ($items as $item) {
                    $weight += (float)$item['weight'];
                    $totalPrice += $item['qnt'] * (float)get_discount_price($item)[0];
                }

                // Reset this session every time price is calculating
                session()->set(SESSION_ZERO_POST_PRICE, false);

                if ($totalPrice > (config()->get('settings.min_free_price.value') ?: PHP_INT_MAX)) {
                    $canContinue = true;
                    $price = 0;
                } else {
                    if (!empty($sendMethod) && $sendMethod['determine_price_by_location'] == DB_NO) {
                        $canContinue = true;
                        $price = (float)$sendMethod['price'];
                        session()->set(SESSION_ZERO_POST_PRICE, $price === 0.0);
                    } else if (0 != count($province) && 0 != count($ownProvince)) {
                        $canContinue = true;

                        if (
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
                    }
                }

                if ($canContinue) {
                    // consider separate consignment of each item for shipping price
                    $shippingTimes = 0;
                    $countQntItems = 0;
                    $cartItems = cart()->getItems();
                    foreach ($cartItems as $item) {
                        $countQntItems += $item['qnt'];
                        if (is_value_checked($item['separate_consignment'])) {
                            $shippingTimes += $item['qnt'];
                        }
                    }

                    if ($countQntItems > $shippingTimes) {
                        $shippingTimes += 1;
                    }
                    if (
                        count($cartItems) <= 1 ||
                        (
                            !empty($sendMethod) &&
                            $sendMethod['only_for_shop_location'] == DB_YES
                        )
                    ) {
                        $shippingTimes = 1;
                    }
                    //

                    session()->set(SESSION_APPLIED_POST_PRICE, (float)$price * (float)$shippingTimes);

                    $resourceHandler
                        ->type(RESPONSE_TYPE_SUCCESS)
                        ->data('هزینه ارسال اعمال شد.');
                } else {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('استان وارد شده/روش ارسال انتخاب شده نامعتبر است.');
                }
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch
        (Exception $e) {
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
