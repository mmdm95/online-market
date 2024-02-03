<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Forms\Order\RepayForm;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderPaymentModel;
use App\Logic\Models\OrderReserveModel;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\PaymentUtil;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class RePayController extends AbstractUserController
{
    /**
     * @param $id
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function index($id): string
    {
        $user = $this->getDefaultArguments()['user'];

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderReserveModel $reserveModel
         */
        $reserveModel = container()->get(OrderReserveModel::class);
        /**
         * @var PaymentMethodModel $methodModel
         */
        $methodModel = container()->get(PaymentMethodModel::class);

        if (0 === $orderModel->count('id=:id AND user_id=:uId', ['id' => $id, 'uId' => $user['id']])) {
            return $this->show404();
        }

        $order = $orderModel->getOrders('o.id=:id', ['id' => $id], ['o.id DESC'], 1)[0];

        $orderItems = $orderModel->getOrderItemsWithReturnOrderItems([
            'oi.*', 'roi.order_item_id', 'pa.slug AS product_slug', 'pa.allow_commenting',
            'pa.image AS product_image', 'pa.code AS main_product_code',
        ], 'oi.order_code=:code', ['code' => $order['code']]);

        $reservedItem = $reserveModel->getFirst(
            ['expire_at'],
            'order_code=:oc',
            ['oc' => $order['code']],
            ['created_at DESC']
        );

        $paymentMethods = $methodModel->get(
            ['code', 'title', 'image'],
            'publish=:pub',
            ['pub' => DB_YES]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/order/re-pay');
        return $this->render([
            'order' => $order,
            'order_items' => $orderItems,
            'reserved_item' => $reservedItem,
            'payment_methods' => $paymentMethods,
            'sub_title' => 'پرداخت سفارش' . '-' . $order['code'],
        ]);
    }

    /**
     * @param $id
     * @return void
     */
    public function makeRepayConnection($id)
    {
        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                //------------------------------------------------------------------
                // check order and get information about it
                //------------------------------------------------------------------
                /**
                 * @var OrderModel $orderModel
                 */
                $orderModel = container()->get(OrderModel::class);
                /**
                 * @var OrderReserveModel $reserveModel
                 */
                $reserveModel = container()->get(OrderReserveModel::class);

                $order = $orderModel->getFirst(['*'], 'id=:id', ['id' => $id]);
                $reservedItem = $reserveModel->getFirst(
                    ['expire_at'],
                    'order_code=:oc',
                    ['oc' => $order['code']],
                    ['created_at DESC']
                );


                if (
                    !count($order) ||
                    (
                        !in_array($order['payment_status'], [PAYMENT_STATUS_WAIT, PAYMENT_STATUS_NOT_PAYED]) &&
                        (
                            !isset($reservedItem['expire_at']) ||
                            $reservedItem['expire_at'] < time()
                        )
                    )
                ) {
                    $msg = 'سفارش نامعتبر می‌باشد.';
                    if (isset($reservedItem['expire_at']) && $reservedItem['expire_at'] < time()) {
                        $msg = 'زمان پرداخت سفارش به پایان رسیده است، لطفا مجددا سفارش خود را ثبت کنید.';
                    }

                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage($msg);
                    response()->json($resourceHandler->getReturnData());
                }
                // store to flash session to retrieve in form store
                session()->setFlash(SESSION_REPAY_ORDER_RECORD, $order);

                //------------------------------------------------------------------
                // check gateway code and get information about it
                //------------------------------------------------------------------
                $gatewayCode = input()->post('inp-re-payment-method-option')->getValue();
                $gatewayMethod = PaymentUtil::validateAndGetPaymentMethod($gatewayCode);
                if (is_null($gatewayMethod)) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('روش پرداخت انتخاب شده نامعتبر است.');
                    response()->json($resourceHandler->getReturnData());
                }
                // store to flash session to retrieve in form store
                session()->setFlash(SESSION_REPAY_GATEWAY_RECORD, $gatewayMethod);

                //------------------------------------------------------------------

                // issue a factor for order
                $formHandler = new GeneralAjaxFormHandler();
                $resourceHandler = $formHandler
                    ->handle(RepayForm::class);

                // return needed response for gateway redirection if everything is ok
                if ($resourceHandler->getReturnData()['type'] == RESPONSE_TYPE_SUCCESS) {
                    $connOptions = PaymentUtil::getGatewayConnectionOptions($gatewayMethod);

                    if (is_array($connOptions)) {
                        $url = $connOptions['url'];
                        $inputs = $connOptions['inputs'];
                        $redirect = $connOptions['redirect'];
                        $isMultipart = $connOptions['isMultipart'];

                        $resourceHandler
                            ->type(RESPONSE_TYPE_SUCCESS)
                            ->data([
                                'redirect' => $redirect,
                                'url' => $url,
                                'inputs' => $inputs,
                                'multipart_form' => $isMultipart,
                            ]);

                        session()->remove(SESSION_REPAY_GATEWAY_UNIQUE_CODE);
                    } else {
                        $msg = 'خطا در ارتباط با درگاه بانک، لطفا دوباره تلاش کنید.';
                        if (is_string($connOptions) && !empty($connOptions)) {
                            $msg = $connOptions;
                        }

                        $this->removeOrderPayment();
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
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function removeOrderPayment()
    {
        /**
         * @var OrderPaymentModel $orderPayModel
         */
        $orderPayModel = container()->get(OrderPaymentModel::class);

        $uniqueCode = session()->get(SESSION_REPAY_GATEWAY_UNIQUE_CODE);
        $orderPayModel->delete('code=:code', ['code' => $uniqueCode]);

        session()->remove(SESSION_REPAY_GATEWAY_UNIQUE_CODE);
    }
}
