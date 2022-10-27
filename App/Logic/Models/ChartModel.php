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
                'COUNT(*) AS count',
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
        $subSelect = $this->connector->select();
        $subSelect
            ->from($this->table . ' AS oa')
            ->cols([
                '(CASE WHEN (oa.title IS NOT NULL) THEN (oa.title) ELSE (oa.order_item_product_title) END) AS product_title',
                '(CASE WHEN (oa.category_name IS NOT NULL) THEN (oa.category_name) ELSE ("") END) AS category_name',
                'oa.order_item_product_count AS oi_product_count',
                'oa.product_id',
            ])
            ->where('oa.ordered_at BETWEEN :d1 AND :d2')
            ->where('oa.payment_status=:ps')
            ->bindValues([
                'd1' => $fromDate,
                'd2' => $toDate,
                'ps' => PAYMENT_STATUS_SUCCESS,
            ])
            ->limit($topN)
            ->groupBy(['oa.product_id']);
        //
        $select = $this->connector->select();
        $select
            ->fromSubSelect($subSelect, 'oa')
            ->cols([
                'SUM(oa.oi_product_count) AS count',
                'oa.product_title',
                'oa.category_name',
                'oa.product_id',
            ])
            ->orderBy(['1 DESC'])
            ->groupBy(['oa.product_id']);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}
