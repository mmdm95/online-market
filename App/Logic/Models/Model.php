<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\UpdateInterface;

class Model extends BaseModel
{
    /**
     * @param SelectInterface $select
     * @return array
     */
    public function get(SelectInterface $select): array
    {
        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param InsertInterface|UpdateInterface|DeleteInterface $interface
     * @return bool
     */
    public function execute($interface): bool
    {
        $stmt = $this->db->prepare($interface->getStatement());
        return $stmt->execute($interface->getBindValues());
    }

    /**
     * @return SelectInterface
     */
    public function select(): SelectInterface
    {
        return $this->connector->select();
    }

    /**
     * @return InsertInterface
     */
    public function insert(): InsertInterface
    {
        return $this->connector->insert();
    }

    /**
     * @return UpdateInterface
     */
    public function update(): UpdateInterface
    {
        return $this->connector->update();
    }

    /**
     * @return DeleteInterface
     */
    public function delete(): DeleteInterface
    {
        return $this->connector->delete();
    }
}