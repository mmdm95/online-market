<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Interfaces\Report\IReportPdf;
use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderPaymentModel;
use App\Logic\Models\OrderReserveModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\ReportUtil;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\FileNotExistsException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class OrderController extends AbstractUserController implements IReportPdf
{
    /**
     * @var string
     */
    protected $report_layout = 'admin-report';

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
    public function index()
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        $user = $this->getDefaultArguments()['user'];

        $orders = $orderModel->getOrders(
            'o.user_id=:id',
            ['id' => $user['id']],
            ['o.id DESC'],
            null,
            0,
            [
                'o.id',
                'o.code',
                'o.payment_status',
                'o.send_status_title',
                'o.send_status_color',
                'o.final_price',
                'o.ordered_at',
            ]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/order/index');
        return $this->render([
            'orders' => $orders,
        ]);
    }

    /**
     * @param $id
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
    public function detail($id)
    {
        $user = $this->getDefaultArguments()['user'];

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);

        /**
         * @var OrderReserveModel $reserveModel
         */
        $reserveModel = container()->get(OrderReserveModel::class);
        /**
         * @var OrderPaymentModel $orderPayModel
         */
        $orderPayModel = container()->get(OrderPaymentModel::class);

        if (0 === $orderModel->count('id=:id AND user_id=:uId', ['id' => $id, 'uId' => $user['id']])) {
            return $this->show404();
        }

        $order = $orderModel->getOrders('o.id=:id', ['id' => $id], ['o.id DESC'], 1)[0];
        $orderItems = $orderModel->getOrderItemsWithReturnOrderItems([
            'oi.*', 'roi.order_item_id', 'pa.slug AS product_slug', 'pa.allow_commenting',
            'pa.image AS product_image', 'pa.code AS main_product_code',
        ], 'oi.order_code=:code', ['code' => $order['code']]);
        $payment = $orderPayModel->getFirst(['*'], 'order_code=:code', ['code' => $order['code']], ['created_at DESC']);

        $paymentSuccess = $gatewayModel->getFirst([
            'id', 'price', 'msg', 'payment_code', 'is_success', 'method_type', 'payment_date'
        ], 'order_code=:oc AND user_id=:uId AND (method_type IS NULL OR method_type=:mt)', [
            'oc' => $order['code'],
            'uId' => $user['id'],
            'mt' => $payment['method_type'] ?? '',
        ], ['payment_date DESC', 'id DESC']) ?: null;
        $order['payment_code'] = $paymentSuccess['payment_code'] ?? null;

        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        $paymentInfo = [];
        $paymentInfo['gateway_flow'] = $gatewayModel->get([
            'id', 'price', 'msg', 'payment_code', 'is_success', 'method_type', 'payment_date'
        ], 'order_code=:oc AND user_id=:uId AND id!=:id', [
            'id' => $paymentSuccess['id'] ?? -1,
            'oc' => $order['code'],
            'uId' => $user['id'],
        ], ['payment_date DESC', 'id DESC']);
        //
        $paymentInfo['wallet_flow'] = $walletFlowModel->get([
            'id', 'deposit_price', 'deposit_type_title', 'deposit_at'
        ], 'order_code=:oc AND username=:username AND deposit_type_code=:dtc', [
            'oc' => $order['code'], 'username' => $user['username'], 'dtc' => DEPOSIT_TYPE_PAYED
        ]);

        $reservedItem = $reserveModel->getFirst(
            ['expire_at'],
            'order_code=:oc',
            ['oc' => $order['code']],
            ['created_at DESC']
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/order/detail');
        return $this->render([
            'order' => $order,
            'order_items' => $orderItems,
            'payment_success' => $paymentSuccess,
            'payment_info' => $paymentInfo,
            'order_id' => $id,
            'reserved_item' => $reservedItem,
            'sub_title' => 'جزئیات سفارش' . '-' . $order['code'],
        ]);
    }

    /**
     * @param $code
     * @return void
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Mpdf\MpdfException
     * @throws \ReflectionException
     * @throws FileNotExistsException
     */
    public function exportPdfOne($code)
    {
        $user = $this->getDefaultArguments()['user'];

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);

        $order = $orderModel->getFirst(['*'], 'code=:code AND user_id=:uId', ['code' => $code, 'uId' => $user['id']]);

        if (count($order)) {
            $gatewayInfo = $gatewayModel->get(['*'], 'order_code=:code', ['code' => $order['code']], ['issue_date DESC']);
            $orderItems = $orderModel->getOrderItems(['oi.*'], 'order_code=:code', ['code' => $order['code']]);

            $order['info']['successful'] = [];
            $order['info']['failed'] = [];

            // filter successful order info first then if there is nothing, find last failed info
            foreach ($gatewayInfo as $info) {
                if (DB_YES == $info['is_success']) {
                    $order['info']['successful'] = $info;
                } else {
                    $order['info']['failed'] = $info;
                }

                if (!empty($order['info']['successful']) && !empty($order['info']['failed'])) break;
            }

            $order['method_title'] = METHOD_TYPES_ALL[$order['info']['successful']['method_type'] ?? -999]
                ?? METHOD_TYPES_ALL[$order['info']['failed']['method_type'] ?? -999]
                ?? 'نامشخص';

            if (count($orderItems)) {
                $html = $this
                    ->setLayout($this->report_layout)
                    ->setTemplate('partial/admin/report-templates/order-pdf')
                    ->render([
                        'title' => 'گزارش آیتم‌های سفارش به شماره ' . $order['code'],
                        'order' => $order,
                        'items' => $orderItems,
                    ]);

                $filename = 'آیتم‌های سفارش شماره ';
                $filename .= $order['code'] . ' ';
                $filename .= Jdf::jdate(REPORT_TIME_FORMAT);

                ReportUtil::exportPdf($filename, $html, config()->get('settings.title.value'));
            } else {
                show_500('هیچ سفارشی پیدا نشد!');
            }
        } else {
            show_500('هیچ سفارشی پیدا نشد!');
        }
    }
}
