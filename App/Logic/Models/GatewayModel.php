<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class GatewayModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_GATEWAY_FLOW;

    /**
     * Use [gf for gateway_flow], [o for orders], [u for users]
     *
     *
     * @param $code
     * @param array $columns
     * @return array
     */
    public function getFirstUserAndOrderInfoFromGatewayFlowCode(
        $code,
        $columns = [
            'o.*',
            'u.username',
            'u.first_name AS user_first_name',
            'u.last_name AS user_last_name',
        ]
    )
    {
        $ret = [
            'user' => [],
            'order' => [],
        ];

        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS gf')
            ->cols($columns)
            ->where('gf.code=:code')
            ->bindValue('code', $code)
            ->limit(1);
        try {
            $select
                ->innerJoin(
                    self::TBL_ORDERS . ' AS o',
                    'gf.order_code=o.code'
                )
                ->leftJoin(
                    self::TBL_ORDERS . ' AS u',
                    'u.id=o.user_id'
                );
        } catch (AuraException $e) {
            return $ret;
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) {
            $res = $res[0];
        }

        return $res;
    }
}