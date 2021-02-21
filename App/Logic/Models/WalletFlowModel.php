<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class WalletFlowModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_WALLET_FLOW;

    /**
     * Use [wf for wallet_flow], [u for users - for payer id]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $columns
     * @return array
     */
    public function getWalletFlowInfo(
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['wf.id DESC'],
        ?int $limit = null,
        int $offset = 0,
        array $columns = [
            'wf.*',
            'u.username',
            'u.first_name AS user_first_name',
            'u.last_name AS user_last_name'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_WALLET_FLOW . ' AS wf')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'u.id=wf.payer_id'
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
     * Use [wf for wallet_flow], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getWalletFlowInfoCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getWalletFlowInfo($where, $bind_values, [], null, 0, ['COUNT(DISTINCT(wf.id)) AS count']);
        if (count($res)) return (int)$res[0]['count'];
        return 0;
    }
}