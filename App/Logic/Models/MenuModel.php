<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception;

class MenuModel extends BaseModel
{
    /**
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getMenuItemsWithColumns(
        array $columns,
        string $where = null,
        array $bind_values = []
    ): array
    {
        $select = $this->connector->select();
        try {
            $select
                ->from(self::TBL_CATEGORIES . ' AS c')
                ->cols($columns)
                ->where('c.publish=:pub')
                ->where('c.show_in_menu=:sim')
                ->bindValues([
                    'pub' => DB_YES,
                    'sim' => DB_YES,
                ])
                ->leftJoin(self::TBL_CATEGORIES . ' AS pc', 'c.parent_id=pc.id')
                ->orderBy(['c.priority DESC']);
        } catch (Exception $e) {
            return [];
        }

        if (!empty($where)) {
            $select->where($where)->bindValues($bind_values);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getMenuItems(string $where = null, array $bind_values = []): array
    {
        return $this->getMenuItemsWithColumns(['c.name', 'c.parent_id', 'pc.name AS parent_name'], $where, $bind_values);
    }
}