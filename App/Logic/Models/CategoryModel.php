<?php

namespace App\Logic\Models;

class CategoryModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_CATEGORIES;

    /**
     * @param string $where
     * @param array $bind_values
     * @return bool
     */
    public function delete(string $where, array $bind_values = []): bool
    {
        return parent::delete('deletable=:is_deletable', [
            'is_deletable' => DB_YES
        ]);
    }
}