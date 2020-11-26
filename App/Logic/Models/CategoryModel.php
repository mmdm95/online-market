<?php

namespace App\Logic\Models;

class CategoryModel extends BaseModel
{
    /**
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getCategories(array $columns = ['*'], string $where = null, array $bind_values = []): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_CATEGORIES)
            ->cols($columns)
            ->orderBy(['name ASC']);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param array $values
     * @param bool $get_last_inserted_id
     * @return bool|int
     */
    public function insert(array $values, bool $get_last_inserted_id = false)
    {
        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_CATEGORIES)
            ->cols($values);

        $stmt = $this->db->prepare($insert->getStatement());
        $res = $stmt->execute($insert->getBindValues());

        if ($get_last_inserted_id) {
            // get the last insert ID
            $name = $insert->getLastInsertIdName('id');
            $res = (int)$this->db->lastInsertId($name);
        }

        return $res;
    }

    /**
     * @param array $data
     * @param string $where
     * @param array $bind_values
     * @return bool
     */
    public function update(array $data, string $where, array $bind_values = []): bool
    {
        $update = $this->connector->update();
        $update
            ->table(self::TBL_CATEGORIES)
            ->cols($data);

        if (!empty($where)) {
            $update
                ->where($where)
                ->bindValues($bind_values);
        }

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }

    /**
     * @param string $where
     * @param array $bind_values
     * @return bool
     */
    public function delete(string $where, array $bind_values = []): bool
    {
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_CATEGORIES)
            ->where('deletable=:is_deletable')
            ->bindValue('is_deletable', DB_YES);

        if (!empty($where)) {
            $delete
                ->where($where)
                ->bindValues($bind_values);
        }

        $stmt = $this->db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }
}