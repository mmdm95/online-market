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
     * @param int|null $limit
     * @param int $offset
     * @param array $order_by
     * @param array $columns
     * @return array
     */
    public function getComments(
        ?string $where = null,
        array $bind_values = [],
        ?int $limit = null,
        int $offset = 0,
        array $order_by = ['c.id DESC'],
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
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

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

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getCommentsCount(
        ?string $where = null,
        array $bind_values = []
    ): int
    {
        $res = $this->getComments($where, $bind_values, null, 0, [], ['COUNT(*) AS count']);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}