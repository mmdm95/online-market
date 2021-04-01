<?php

namespace App\Logic\Models;

class CouponModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_COUPONS;

    /**
     * @param $code
     * @return float
     */
    public function getCouponPriceFromCode($code): float
    {
        $select = $this->connector->select();
        $select
            ->from($this->table)
            ->cols(['price'])
            ->where('code=:code')
            ->bindValue('code', $code);

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) {
            return (float)$res[0]['price'];
        }
        return 0.0;
    }
}