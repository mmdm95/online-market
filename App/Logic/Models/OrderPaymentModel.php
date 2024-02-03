<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class OrderPaymentModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_ORDER_PAYMENTS;

    /**
     * Use [op for order_payments], [gf for gateway_flow]
     *
     * @param string|null $where
     * @param array $bindings
     * @param array $orderBy
     * @param int|null $limit
     * @param int $offset
     * @param array $columns
     * @return array
     */
    public function getAllPaymentsWithGatewayFlow(
        ?string $where = null,
        array   $bindings = [],
        array   $orderBy = ['op.created_at DESC'],
        ?int    $limit = null,
        int     $offset = 0,
        array   $columns = [
            'op.*', 'gf.payment_code', 'gf.is_success',
            'gf.price', 'gf.payment_date', 'gf.msg',
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS op')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($orderBy);

        try {
            $select
                ->leftJoin(
                    self::TBL_GATEWAY_FLOW . ' AS gf',
                    'op.code=gf.code AND op.order_code=gf.order_code'
                );
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bindings);
        }

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}
