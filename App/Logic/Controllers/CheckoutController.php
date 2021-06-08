<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\AddressModel;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\Payment\SharedPaymentUtil;
use App\Logic\Utils\PostPriceUtil;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Payment\Factories\Sadad;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\Sadad\SadadRequestProvider;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;

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
        $user['roles'] = $userModel->getUserRoles($user['id'], null, [], ['r.*']);

        $this->setDefaultArguments(array_merge($this->getDefaultArguments(), [
            'user' => $user,
        ]));
    }

    public function demo()
    {
        /**
         * @var Sadad $gateway
         */
        // $key, $merchantId, $terminalId
        $gateway = PaymentFactory::instance(PaymentFactory::GATEWAY_SADAD,);
        // provider
        $provider = new SadadRequestProvider();
        $provider
            ->setReturnUrl(url('pay.test')->getRelativeUrlTrimmed())
            ->setAmount(10000)
            ->setOrderId(1);
        // events
        $gateway->createRequestOkClosure(function (SadadRequestResultProvider $result) {
            return '
                    <form action="' . $result->getUrl() . '">
                        <button type="submit">Go to payment</button>
                    </form>
                ';
        });
        //
        $gateway->createRequest($provider);

        return 'Demo Payment';
    }

    public function demoResult()
    {

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
        if (is_post()) {

        }

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
     * Calculate post price
     *
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function calculateSendPrice()
    {
        $resourceHandler = new ResourceHandler();

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

                $provinceId = input()->post('province')->getValue();
                $province = $provinceModel->getFirst(['post_price_order'], 'id=:id', ['id' => $provinceId]);
                $ownProvince = $provinceModel->getFirst(['post_price_order'], 'id=:id', ['id' => config()->get('settings.store_province.value')]);
                if (0 != count($province) && 0 != count($ownProvince)) {
                    // calculate weights
                    $items = cart()->getItems();
                    $weight = 0.0;
                    foreach ($items as $item) {
                        $weight += (float)$item['weight'];
                    }

                    $postUtil = new PostPriceUtil($ownProvince, $province, $weight);
                    $price = $postUtil->post();
                    session()->set(SESSION_APPLIED_POST_PRICE, $price);
                    $resourceHandler
                        ->type(RESPONSE_TYPE_SUCCESS)
                        ->data('');
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
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }
}