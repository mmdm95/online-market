<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class OrderModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_ORDERS;

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
}