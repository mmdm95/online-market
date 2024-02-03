<?php

namespace App\Logic\Utils;

use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderPaymentModel;
use App\Logic\Models\WalletFlowModel;
use Sim\Utils\StringUtil;

class OrderUtil
{
    /**
     * @param string $orderCode
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getOrderPaymentWithFlows(string $orderCode): array
    {
        /**
         * @var OrderPaymentModel $orderPayModel
         */
        $orderPayModel = container()->get(OrderPaymentModel::class);
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);

        // get all payments with result of it and payment code
        $orderPayments = $orderPayModel->getAllPaymentsWithGatewayFlow(
            'op.order_code=:code',
            ['code' => $orderCode]
        );
        $walletPayments = $walletFlowModel->get(
            ['deposit_type_title', 'deposit_price', 'deposit_at'],
            'order_code=:code',
            ['code' => $orderCode],
            ['deposit_at DESC']
        );

        foreach ($walletPayments as $payment) {
            $orderPayments[] = [
                'order_code' => $orderCode,
                'payment_code' => null,
                'price' => abs((float)$payment['deposit_price']),
                'method_type' => METHOD_TYPE_WALLET,
                'method_title' => METHOD_TYPES_ALL[METHOD_TYPE_WALLET],
                'payment_status' => PAYMENT_STATUS_SUCCESS,
                'is_success' => DB_YES,
                'msg' => $payment['deposit_type_title'] ?: 'پرداخت موفق',
                'created_at' => $payment['deposit_at'],
            ];
        }

        // sort order payments by created_at column
        $columnToSortBy = array_column($orderPayments, 'created_at');
        array_multisort($columnToSortBy, SORT_DESC, $orderPayments);

        return $orderPayments;
    }

    /**
     * @param int $length
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getUniqueOrderCode(int $length = 15): string
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        do {
            $uniqueStr = StringUtil::randomString($length, StringUtil::RS_NUMBER, ['0']);

        } while ($orderModel->count('code=:code', ['code' => $uniqueStr]));
        return $uniqueStr;
    }

    /**
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getOrderBadgesWithCount(): array
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderBadgeModel $orderBadgeModel
         */
        $orderBadgeModel = container()->get(OrderBadgeModel::class);

        // get badges
        $badgeIds = $orderBadgeModel->get(['code', 'title', 'color'], 'is_deleted=:del', ['del' => DB_NO], ['id ASC']);
        $orderBadges = [];
        foreach ($badgeIds as $k => $badge) {
            $orderBadges[$k] = $badge;
            $orderBadges[$k]['count'] = $orderModel->count(
                'send_status_code=:ssc',
                [
                    'ssc' => $badge['code'],
                ]
            );
        }

        return $orderBadges;
    }
}
