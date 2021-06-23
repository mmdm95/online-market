<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Common\Insert;
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

    public function issueFullFactor(array $order, array $items): bool
    {
        $this->db->beginTransaction();

        /**
         * @var Model $model
         */
        $model = container()->get(Model::class);

        // issue a factor by inserting to orders table
        $res = $this->insert($order);
        //-----

        // insert all items to order items table
        /**
         * @var Insert $insert
         */
        $insert = $model->insert();
        $counter = 0;
        foreach ($items as $item) {
            if ($counter++ != 0) {
                $insert->addRow();
            }
            $insert->cols($item);
        }
        $res2 = $model->execute($insert);
        //-----

        if (!$res || !$res2) {
            $this->db->rollBack();
        }

        $this->db->commit();

        return false;
    }
}