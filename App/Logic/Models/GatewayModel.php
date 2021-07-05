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
     * Return will be in following format:
     * [
     *   'order' => [...],
     *   'user' => [...],
     * ]
     *
     * @param $code
     * @return array
     */
    public function getUserAndOrderInfoFromGatewayFlowCode($code)
    {
        $ret = [
            'user' => [],
            'order' => [],
        ];

        // get order info
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS gf')
            ->cols(['o.*'])
            ->where('code=:code')
            ->bindValue('code', $code)
            ->limit(1);
        try {
            $select
                ->innerJoin(
                    self::TBL_ORDERS . ' AS o',
                    'gf.order_code=o.code'
                );
        } catch (AuraException $e) {
            return $ret;
        }
        $ret['order'] = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        // get user info
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS gf')
            ->cols(['u.*'])
            ->where('code=:code')
            ->bindValue('code', $code)
            ->limit(1);
        try {
            $select
                ->innerJoin(
                    self::TBL_ORDERS . ' AS o',
                    'gf.order_code=o.code'
                )
                ->innerJoin(
                    self::TBL_ORDERS . ' AS u',
                    'u.id=o.user_id'
                );
        } catch (AuraException $e) {
            return $ret;
        }
        $ret['user'] = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        return $ret;
    }
}