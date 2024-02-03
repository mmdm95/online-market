<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class ProductFestivalModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_PRODUCT_FESTIVAL;

    /**
     * Use [pf for product_festival], [p for products], [c for categories]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function getFestivalProducts(
        array   $columns = [
            'pf.*',
            'p.image AS product_image',
            'p.title AS product_name',
            'c.name AS category_name',
        ],
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = [],
        ?int    $limit = null,
        int     $offset = 0
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS pf')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_PRODUCTS . ' AS p',
                    'pf.product_id=p.id'
                )
                ->leftJoin(
                    self::TBL_CATEGORIES . ' AS c',
                    'p.category_id=c.id'
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
     * Use [pf for product_festival], [p for products], [c for categories]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getFestivalProductsCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getFestivalProducts(['COUNT(DISTINCT(pf.id)) AS count'], $where, $bind_values, [], null, 0);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param int $festival_id
     * @param int $category_id
     * @param int $percentage
     * @return bool
     */
    public function addCategoryToFestival(int $festival_id, int $category_id, int $percentage): bool
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ADVANCED . ' AS pa')
            ->cols(['pa.product_id AS id'])
            ->where('pa.category_id=:cId')
            ->orWhere('pa.category_all_parents_id REGEXP :capi')
            ->bindValue('cId', $category_id)
            ->bindValue('capi', getDBCommaRegexString($category_id));
        $products = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        // if there is no products, there is no need for further operations
        if (!count($products)) return true;

        $res = false;
        foreach ($products as $k => $product) {
            if (0 !== $this->count('product_id=:pId AND festival_id=:fId', ['pId' => $product['id'], 'fId' => $festival_id])) {
                $res = true;
                continue;
            }

            $insert = $this->connector->insert();
            $insert
                ->into($this->table)
                ->cols([
                    'product_id' => $product['id'],
                    'festival_id' => $festival_id,
                    'discount' => $percentage,
                ]);

            $stmt = $this->db->prepare($insert->getStatement());
            $res = $stmt->execute($insert->getBindValues());

            if (!$res) break;
        }

        return $res;
    }

    /**
     * @param int $festival_id
     * @param int $category_id
     * @return bool
     */
    public function removeCategoryFromFestival(int $festival_id, int $category_id): bool
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCTS)
            ->cols(['id'])
            ->where('category_id=:cId')
            ->bindValue('cId', $category_id);
        $products = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        // if there is no products, there is no need for further operations
        if (!count($products)) return false;

        // create in clause
        $inClause = '';
        $bindValues = [];
        foreach ($products as $k => $product) {
            $inClause .= ":pbv{$k},";
            $bindValues["pbv{$k}"] = $product['id'];
        }
        $inClause = rtrim($inClause, ',');

        $delete = $this->connector->delete();
        $delete
            ->from($this->table)
            ->where('product_id IN(' . $inClause . ')')
            ->where('festival_id=:fId')
            ->bindValues($bindValues)
            ->bindValue('fId', $festival_id);

        $stmt = $this->db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }
}