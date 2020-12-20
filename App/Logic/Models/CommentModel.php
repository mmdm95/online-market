<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class CommentModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_COMMENTS;

    /**
     * @param string|null $where
     * @param array $bind_values
     * @param array $columns
     * @return array
     */
    public function getComments(
        ?string $where = null,
        array $bind_values = [],
        array $columns = [
            'c.product_id',
            'c.user_id',
            'c.body',
            'c.sent_at',
            'u.first_name',
            'u.image AS user_image',
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS c')
            ->cols($columns);

        try {
            $select->innerJoin(self::TBL_USERS . ' AS u', 'c.user_id=u.id');
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}