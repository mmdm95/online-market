<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class CategoryModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_CATEGORIES;

    /**
     * Use [c for categories], [cc for categories - parent info]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param int|null $limit
     * @param int $offset
     * @param array $order_by
     * @param array $group_by
     * @return array
     */
    public function getCategories(
        array $columns = ['c.*', 'cc.name'],
        ?string $where = null,
        array $bind_values = [],
        ?int $limit = null,
        int $offset = 0,
        array $order_by = ['c.id DESC'],
        array $group_by = ['c.id']
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS c')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by)
            ->groupBy($group_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_CATEGORIES . ' AS cc',
                    'c.parent_id=cc.id'
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
    public function getCategoriesCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getCategories(['COUNT(DISTINCT(c.id)) AS count'], $where, $bind_values, null, 0, [], []);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}
