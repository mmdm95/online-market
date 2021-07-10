<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Common\Insert;
use Aura\SqlQuery\Common\Update;
use Aura\SqlQuery\Exception as AuraException;

class OrderModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_ORDERS;

    /**
     * Use [o for orders], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $columns
     * @return array
     */
    public function getOrders(
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['o.id DESC'],
        ?int $limit = null,
        int $offset = 0,
        array $columns = [
            'o.*',
            'u.username',
            'u.first_name AS user_first_name',
            'u.last_name AS user_last_name'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS o')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'u.id=o.user_id'
                );
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [o for orders], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getOrdersCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getOrders($where, $bind_values, [], null, 0, ['COUNT(DISTINCT(o.id)) AS count']);
        if (count($res)) return (int)$res[0]['count'];
        return 0;
    }

    /**
     * Use [oi for order_items], [p for products]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getOrderItems(array $columns, ?string $where = null, array $bind_values = []): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_ORDER_ITEMS . ' AS oi')
            ->cols($columns);

        try {
            $select
                ->leftJoin(
                    self::TBL_PRODUCTS . ' AS p',
                    'p.id=oi.product_id'
                );
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [oi for order_items], [roi for return_order_items],
     * [ro for return_orders], [p for products], [pp for product_property]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getOrderItemsWithReturnOrderItems(
        array $columns,
        ?string $where = null,
        array $bind_values = []
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_ORDER_ITEMS . ' AS oi')
            ->cols($columns);

        try {
            $select
                ->leftJoin(
                    self::TBL_PRODUCTS . ' AS p',
                    'p.id=oi.product_id'
                )
                ->leftJoin(
                    self::TBL_PRODUCT_PROPERTY . ' AS pp',
                    'pp.code=oi.product_code'
                )
                ->leftJoin(
                    self::TBL_RETURN_ORDERS . ' AS ro',
                    'ro.order_code=oi.order_code'
                )
                ->leftJoin(
                    self::TBL_RETURN_ORDER_ITEMS . ' AS roi',
                    'roi.return_code=ro.code'
                );
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [u for users], [o for orders], [oi for order_items]
     *
     * @param $product_id
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function getUsersFromProductId(
        $product_id,
        array $columns,
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['u.id DESC'],
        ?int $limit = null,
        int $offset = 0
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_ORDERS . ' AS o')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by)
            ->where('oi.product_id=:pId')
            ->bindValue('pId', $product_id);

        try {
            $select
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'u.id=o.user_id'
                )
                ->innerJoin(
                    self::TBL_ORDER_ITEMS . ' AS oi',
                    'oi.order_code=o.code'
                );
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [u for users], [o for orders], [oi for order_items]
     *
     * @param $product_id
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getUsersFromProductIdCount(
        $product_id,
        ?string $where = null,
        array $bind_values = []
    ): int
    {
        $res = $this->getUsersFromProductId($product_id, ['COUNT(DISTINCT(u.id)) AS count'], $where, $bind_values);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param array $order
     * @param array $items
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function issueFullFactor(array $order, array $items): bool
    {
        // it must have at least one item to issue a factor
        if (!count($items)) {
            return false;
        }

        $this->db->beginTransaction();

        /**
         * @var Model $model
         */
        $model = container()->get(Model::class);

        // issue a factor by inserting to orders table
        $res = $this->insert($order);
        //-----
        $res2 = true;
        $res4 = true;
        foreach ($items as $item) {
            // insert all items to order items table
            /**
             * @var Insert $insert
             */
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_ORDER_ITEMS)
                ->cols([
                    'order_code' => $order['code'],
                    'product_id' => $item['product_id'],
                    'product_code' => $item['code'],
                    'product_title' => $item['title'],
                    'price' => $item['price'],
                    'discounted_price' => $item['discounted_price'],
                    'unit_price' => $item['unit_sign'],
                    'product_count' => $item['qnt'],
                    'unit_title' => $item['unit_title'],
                    'color' => $item['color_hex'],
                    'color_name' => $item['color_name'],
                    'size' => $item['size'],
                    'guarantee' => $item['guarantee'],
                    'is_returnable' => is_value_checked($item['is_returnable']) ? DB_YES : DB_NO,
                ]);

            $stmt = $this->db->prepare($insert->getStatement());
            $res2 = $stmt->execute($insert->getBindValues());
            if (!$res2) break;

            /**
             * @var Update $update
             */
            $update = $this->connector->update();
            $update
                ->table(self::TBL_PRODUCT_PROPERTY)
                ->set('stock_count', 'stock_count-' . $item['qnt'])
                ->where('code=:code')
                ->bindValue('code', $item['code']);
            $res4 = $model->execute($update);
            if (!$res4) break;
        }
        //-----

        if (!$res || !$res2 || !$res4) {
            $this->db->rollBack();
            return false;
        }

        /**
         * @var OrderReserveModel $reserveModel
         */
        $reserveModel = container()->get(OrderReserveModel::class);

        $res3 = $reserveModel->insert([
            'order_code' => $order['code'],
            'created_at' => time(),
            'expire_at' => time() + RESERVE_MAX_TIME,
        ]);

        if (!$res3) {
            $this->db->rollBack();
            return false;
        }

        $this->db->commit();

        return $res && $res2 && $res3 && $res4;
    }

    /**
     * @param $orderCode
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function removeIssuedFactor($orderCode)
    {
        /**
         * @var OrderReserveModel $reserveModel
         */
        $reserveModel = container()->get(OrderReserveModel::class);
        /**
         * @var CouponModel $couponModel
         */
        $couponModel = container()->get(CouponModel::class);
        /**
         * @var Model $model
         */
        $model = container()->get(Model::class);

        $this->db->beginTransaction();

        $order = $this->getFirst(['coupon_id', 'coupon_code'], 'code=:code', ['code' => $orderCode]);

        $res = $this->delete('code=:code', ['code' => $orderCode]);
        $res2 = $reserveModel->delete('order_code=:code', ['code' => $orderCode]);

        $couponRes = true;
        if (count($order) && !empty($order['coupon_id']) && !empty($order['coupon_code'])) {
            // make this coupon a used one
            $couponUpdate = $model->update();
            $couponUpdate
                ->table(BaseModel::TBL_COUPONS)
                ->set('use_count', 'use_count+1')
                ->where('id=:id AND code=:code')
                ->bindValues([
                    'id' => $order['coupon_id'],
                    'code' => $order['coupon_code'],
                ]);
            $couponRes = $model->execute($couponUpdate);
        }

        if (!$res || !$res2 || !$couponRes) {
            $this->db->rollBack();
        }
        $this->db->commit();
    }
}