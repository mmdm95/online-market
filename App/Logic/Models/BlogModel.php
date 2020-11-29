<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class BlogModel extends BaseModel
{
    /**
     * Use [b for blog], [bc for blog_categories], [u for users - created_by], [uu for users - updated_by]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param int|null $limit
     * @return array
     */
    public function getBlog(
        array $columns = [
            'b.*',
            'bc.name AS category_name',
            'bc.slug AS category_slug',
            'CONCAT(u.first_name, " ", u.last_name) AS creator',
            'CONCAT(uu.first_name, " ", uu.last_name) AS updater',
            'u.image AS creator_image',
            'u.id AS creator_id'
        ],
        ?string $where = null,
        array $bind_values = [],
        int $limit = null
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_BLOG . ' AS b')
            ->cols($columns)
            ->orderBy(['b.id DESC'])
            ->groupBy(['b.id']);

        try {
            $select
                ->innerJoin(
                    self::TBL_BLOG_CATEGORIES . ' AS bc',
                    'b.category_id=bc.id'
                )
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'b.created_by=u.id'
                )->leftJoin(
                    self::TBL_USERS . ' AS uu',
                    'b.updated_by=uu.id'
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
     * Use [b for blog], [bc for blog_categories], [u for users - created_by], [uu for users - updated_by]
     *
     * @param string|null $where
     * @param array $bindParams
     * @return int
     */
    public function getBlogCount(?string $where = null, array $bindParams = []): int
    {
        $select = $this->connector->select();
        $select
            ->cols(['COUNT(DISTINCT(b.id)) AS count'])
            ->from(self::TBL_BLOG . ' AS b');

        try {
            $select
                ->innerJoin(
                    self::TBL_BLOG_CATEGORIES . ' AS bc',
                    'b.category_id=bc.id'
                )
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'b.created_by=u.id'
                )->leftJoin(
                    self::TBL_USERS . ' AS uu',
                    'b.updated_by=uu.id'
                );
        } catch (AuraException $e) {
            return 0;
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bindParams);
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}