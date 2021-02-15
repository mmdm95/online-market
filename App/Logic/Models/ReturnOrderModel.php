<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class ReturnOrderModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_RETURN_ORDERS;

    /**
     * Use [ro for return_orders], [o for orders], [u for users], [uc for users - for status changer user]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $columns
     * @return array
     */
    public function getReturnOrders(
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['ro.id DESC'],
        int $limit = null,
        int $offset = 0,
        array $columns = [
            'ro.*',
            'u.username',
            'u.first_name AS user_first_name',
            'u.last_name AS user_last_name'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS ro')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->innerJoin(
                    self::TBL_USERS . ' AS u',
                    'u.id=ro.user_id'
                )
                ->innerJoin(
                    self::TBL_ORDERS . ' AS o',
                    'o.code=ro.order_code'
                )
                ->leftJoin(
                    self::TBL_USERS . ' AS uc',
                    'uc.id=ro.status_changed_by'
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
     * Use [ro for return_orders], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getReturnOrdersCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getReturnOrders($where, $bind_values, [], null, 0, ['COUNT(DISTINCT(ro.id)) AS count']);
        if (count($res)) return (int)$res[0]['count'];
        return 0;
    }

    /**
     * Use [roi for return_order_items], [ro for return_orders], [oi for order_items], [p for products]
     *
     * @param $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getReturnOrderItems($columns, ?string $where = null, array $bind_values = []): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_RETURN_ORDER_ITEMS . ' AS roi')
            ->cols($columns);

        try {
            $select
                ->innerJoin(
                    self::TBL_ORDER_ITEMS . ' AS oi',
                    'oi.id=roi.order_item_id'
                )
                ->innerJoin(
                    self::TBL_RETURN_ORDERS . ' AS ro',
                    'ro.code=roi.return_code'
                )
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
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getReturnOrderItemsCount(?string $where = null, array $bind_values = []): int
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_RETURN_ORDER_ITEMS)
            ->cols(['COUNT(*) AS count']);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        if (count($res)) return (int)$res[0]['count'];
        return 0;
    }
}