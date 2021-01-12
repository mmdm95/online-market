<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class CategoryImageModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_CATEGORY_IMAGES;

    /**
     * Use [ci for category images], [c for categories]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param int|null $limit
     * @param int $offset
     * @param array $order_by
     * @return array
     */
    public function getCategoryImages(
        array $columns = [],
        ?string $where = null,
        array $bind_values = [],
        int $limit = null,
        int $offset = 0,
        array $order_by = ['ci.id DESC']
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS ci')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->innerJoin(
                    self::TBL_CATEGORIES . ' AS c',
                    'ci.category_id=c.id'
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
     * Use [bc for categories], [cc for categories - parent info]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getCategoryImagesCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getCategoryImages(['COUNT(DISTINCT(ci.id)) AS count'], $where, $bind_values);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}