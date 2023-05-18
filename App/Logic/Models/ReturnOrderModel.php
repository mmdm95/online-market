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
        array   $bind_values = [],
        array   $order_by = ['ro.id DESC'],
        ?int    $limit = null,
        int     $offset = 0,
        array   $columns = [
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
     * Use [roi for return_order_items], [ro for return_orders], [oi for order_items], [pa for product_advanced]
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
            ->cols($columns)
            ->groupBy(['pa.product_id']);

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
                    self::TBL_PRODUCT_ADVANCED . ' AS pa',
                    'pa.product_id=oi.product_id'
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

    /**
     * @param array $returnOrderInfo
     * @param array $returnOrderItems
     * @return bool
     */
    public function modifyReturnOrderItems(array $returnOrderInfo, array $returnOrderItems): bool
    {
        $this->db->beginTransaction();

        $code = $returnOrderInfo['code'];

        if ($this->count('order_code=:code', ['code' => $returnOrderInfo['order_code']])) {
            $res = $this->update(
                $returnOrderInfo,
                'code=:c AND order_code=:oc',
                ['c' => $code, 'co' => $returnOrderInfo['order_code']]
            );
        } else {
            $res = $this->insert($returnOrderInfo);
        }

        if (!$res) {
            $this->db->rollBack();
            return false;
        }

        $res2 = false;
        foreach ($returnOrderItems as $item) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_RETURN_ORDER_ITEMS)
                ->cols([
                    'return_code' => $code,
                    'order_item_id' => $item['order_item_id'],
                    'product_count' => $item['product_count'],
                    'is_accepted' => DB_NO,
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res2 = $stmt->execute($insert->getBindValues());

            if (!$res2) break;
        }

        if ($res2) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }

    /**
     * @param array $returnOrderInfo
     * @param string|null $where
     * @param array $bind_values
     * @return bool
     */
    public function modifyReturnOrderStatus(array $returnOrderInfo, string $where, array $bind_values): bool
    {
        $this->db->beginTransaction();

        $code = $returnOrderInfo['code'];
        $orderCode = $returnOrderInfo['order_code'];
        unset($returnOrderInfo['code']);
        unset($returnOrderInfo['order_code']);

        $res = $this->update($returnOrderInfo, $where, $bind_values);

        if (!$res) {
            $this->db->rollBack();
            return false;
        }

        if ($returnOrderInfo['status'] == RETURN_ORDER_STATUS_ACCEPT) {
            $update = $this->connector->update();
            $update
                ->table(self::TBL_RETURN_ORDER_ITEMS)
                ->cols([
                    'is_accepted' => DB_YES,
                    'accepted_at' => time(),
                ])
                ->where('return_code=:code')
                ->bindValues([
                    'code' => $code,
                ]);
            $stmt = $this->db->prepare($update->getStatement());
            $res2 = $stmt->execute($update->getBindValues());

            if (!$res2) {
                $this->db->rollBack();
                return false;
            }
            //
            $update = $this->connector->update();
            $update
                ->table(self::TBL_ORDER_ITEMS)
                ->cols([
                    'is_returned' => DB_YES,
                ])
                ->where('order_code=:code')
                ->bindValues([
                    'code' => $orderCode,
                ]);
            $stmt = $this->db->prepare($update->getStatement());
            $res3 = $stmt->execute($update->getBindValues());

            if (!$res3) {
                $this->db->rollBack();
                return false;
            }
        }

        $this->db->commit();
        return true;
    }
}
