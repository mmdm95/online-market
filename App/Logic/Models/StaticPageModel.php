<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class StaticPageModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_STATIC_PAGES;

    /**
     * Use [sp for static_pages], [u for users - created_by]
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
    public function getPages(
        array $columns = [
            'sp.*',
            'CONCAT(u.first_name, " ", u.last_name) AS creator',
        ],
        ?string $where = null,
        array $bind_values = [],
        ?int $limit = null,
        int $offset = 0,
        array $order_by = ['sp.id DESC'],
        array $group_by = ['sp.id']
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS sp')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by)
            ->groupBy($group_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'sp.created_by=u.id'
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
     * Use [sp for static_pages], [u for users - created_by]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getPagesCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getPages(['COUNT(DISTINCT(sp.id)) AS count'], $where, $bind_values, null, 0, [], []);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}