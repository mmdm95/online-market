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
                'COUNT(oa.product_code) AS count',
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

    /**
     * @param int $topN
     * @param $fromDate
     * @param $toDate
     * @return array
     */
    public function getTopNBoughtProductsCount(int $topN, $fromDate, $toDate): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS oa')
            ->cols([
                '(CASE WHEN (oa.title IS NOT NULL) THEN (oa.title) ELSE (oa.order_item_product_title) END) AS product_title',
                '(CASE WHEN (oa.category_name IS NOT NULL) THEN (oa.category_name) ELSE ("") END) AS category_name',
                'COUNT(DISTINCT(oa.id)) AS count',
            ])
            ->where('oa.ordered_at BETWEEN :d1 AND :d2')
            ->bindValues([
                'd1' => $fromDate,
                'd2' => $toDate,
            ])
            ->orderBy(['3 DESC'])
            ->limit($topN)
            ->groupBy(['oa.product_id']);

        try {
            $select
                ->innerJoin(
                    self::TBL_PRODUCT_PROPERTY . ' AS pp',
                    'oa.product_id=pp.product_id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}
