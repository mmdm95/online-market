<?php

namespace App\Logic\Models;

class ProductModel extends BaseModel
{
    /**
     * Use [pa] instead of [product_advanced]
     *
     * @var string
     */
    protected $table = self::TBL_PRODUCTS;

    /**
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $group_by
     * @param array $columns
     * @return array
     */
    public function getLimitedProduct(
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['pa.product_id DESC'],
        ?int $limit = null,
        int $offset = 0,
        array $group_by = ['pa.product_id'],
        array $columns = [
            'pa.product_id',
            'pa.slug',
            'pa.title',
            'pa.image',
            'pa.price',
            'pa.discounted_price'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ADVANCED . ' AS pa')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by)
            ->groupBy($group_by);

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
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getLimitedProductCount(
        ?string $where = null,
        array $bind_values = []
    ): int
    {
        $res = $this->getLimitedProduct($where, $bind_values, [], null, 0, [], ['COUNT(*) AS count']);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}