<?php

namespace App\Logic\Utils;

use App\Logic\Models\BaseModel;
use App\Logic\Models\Model;
use App\Logic\Models\OrderModel;
use App\Logic\Models\OrderPaymentModel;
use App\Logic\Models\OrderReserveModel;

class ReserveOrderUtil
{
    /**
     * Check reserved orders to restore any operation of an order or not
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function checkReserveItemsNTakeAction()
    {
        /**
         * @var OrderReserveModel $reserveModel
         */
        $reserveModel = container()->get(OrderReserveModel::class);

        $orders = $reserveModel->get(['order_code'], 'expire_at<:time', ['time' => time()]);

        $ok = true;
        foreach ($orders as $order) {
            $ok = $ok && self::restoreReserveOrder($order['order_code']);
        }
        if ($ok) {
            // remove reserved items after work done
            $reserveModel->delete('expire_at<:time', ['time' => time()]);
        }
    }

    /**
     * In case of not paying order in a specific time:
     *   - return order items to product stock
     *   - make order a failed one
     *   - return used coupon to not use status
     *   - remove reserved record
     *   - if there is a record in gateway flow, it must have canceled message
     *
     * @param $orderCode
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function restoreReserveOrder($orderCode)
    {
        /**
         * @var OrderReserveModel $reserveModel
         */
        $reserveModel = container()->get(OrderReserveModel::class);
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderPaymentModel $orderPayModel
         */
        $orderPayModel = container()->get(OrderPaymentModel::class);
        /**
         * @var Model $model
         */
        $model = container()->get(Model::class);

        $theOrder = $orderModel->getFirst([
            'coupon_id', 'coupon_code', 'payment_status'
        ], 'code=:code', [
            'code' => $orderCode,
        ]);
        $payments = $orderPayModel->get(
            ['id'],
            'order_code=:code AND payment_status=:ps',
            ['code' => $orderCode, 'ps' => PAYMENT_STATUS_WAIT]
        );

        $ok = true;
        if (count($theOrder)) {
            if (PAYMENT_STATUS_WAIT == $theOrder['payment_status']) {
                connector()->getDb()->beginTransaction();
                if ($theOrder['is_product_returned_to_stock'] != DB_YES) {
                    $items = $orderModel->getOrderItems(['product_code', 'product_count'], 'order_code=:oc', ['oc' => $orderCode]);
                    // return order items to product stock
                    foreach ($items as $item) {
                        $update = $model->update();
                        $update
                            ->table(BaseModel::TBL_PRODUCT_PROPERTY)
                            ->set('stock_count', 'stock_count+' . $item['product_count'])
                            ->where('code=:code')
                            ->bindValues([
                                'code' => $item['product_code'],
                            ]);
                        $res = $model->execute($update);
                        if (!$res) {
                            $ok = false;
                            break;
                        }
                    }
                }

                // make order a failed one
                $ok = $ok && $orderModel->update([
                        'payment_status' => PAYMENT_STATUS_NOT_PAYED,
                        'is_product_returned_to_stock' => DB_YES,
                    ], 'code=:oc', ['oc' => $orderCode]);
                // return used coupon to not use status
                $couponUpdate = $model->update();
                $couponUpdate
                    ->table(BaseModel::TBL_COUPONS)
                    ->set('use_count', 'use_count+1')
                    ->where('id=:id AND code=:code')
                    ->bindValues([
                        'id' => $theOrder['coupon_id'],
                        'code' => $theOrder['coupon_code'],
                    ]);
                $ok = $ok && $model->execute($couponUpdate);
                //-----
                if ($ok) {
                    connector()->getDb()->commit();
                } else {
                    self::log('Could not complete reserve task for order with code: ' . $orderCode);
                    connector()->getDb()->rollBack();
                }
            }

            // make all order payments status to fail if they are in wait status
            $okStatus = true;
            foreach ($payments as $payment) {
                $okStatus = $okStatus && $orderPayModel->update([
                        'payment_status' => PAYMENT_STATUS_FAILED
                    ], 'id=:id', ['id' => $payment['id']]);
            }
            //-----
            if (!$okStatus) {
                self::log("NOTICE: Some of order payments have WAIT status still that couldn't change");
            }
        }
        //-----
        if ($ok) {
            $reserveModel->delete('order_code=:code', ['code' => $orderCode]);
        }

        return $ok;
    }

    /**
     * @param mixed $msg
     */
    private static function log($msg = null)
    {
        logger()->warning([
            'from' => 'Cron job reserve action',
            'msg' => $msg ?: 'Could not complete reserve task',
        ]);
    }
}
