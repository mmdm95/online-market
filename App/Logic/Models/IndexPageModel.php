<?php

namespace App\Logic\Models;

class IndexPageModel extends BaseModel
{
    /**
     * @return array
     */
    public function getMainSlider(): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_MAIN_SLIDER)
            ->cols(['title', 'note', 'image', 'link'])
            ->orderBy(['priority DESC']);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}