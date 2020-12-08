<?php

namespace App\Logic\Models;

class RoleModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_ROLES;

    /**
     * @param string|null $role_name
     * @return int|null
     */
    public function getIDFromRoleName(?string $role_name): ?int
    {
        if(empty($role_name)) return null;
        $select = $this->connector->select();
        $select
            ->from($this->table)
            ->cols(['id'])
            ->where('name=:role_name')
            ->bindValues(['role_name' => $role_name]);

        $id = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (!count($id)) return null;
        return (int)$id[0]['id'];
    }
}