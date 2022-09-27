<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class ChartModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_ORDER_ADVANCED;

    /**
     * @param $fromDate
     * @param $toDate
     * @return array
     */
    public function getBoughtProductsByStatusCount($fromDate, $toDate): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS oa')
            ->cols([
                'oa.send_status_code AS status_code',
                'COUNT(oa.id) AS badge_count',
            ])
            ->where('oa.ordered_at BETWEEN :d1 AND :d2')
            ->bindValues([
                'd1' => $fromDate,
                'd2' => $toDate,
            ])
            ->groupBy(['ob.code']);

        try {
            $select
                ->innerJoin(
                    self::TBL_ORDER_BADGES . ' AS ob',
                    'oa.send_status_code=ob.code'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}