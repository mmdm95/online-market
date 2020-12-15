<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class BlogModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_BLOG;

    /**
     * Use [b for blog], [bc for blog_categories], [u for users - created_by], [uu for users - updated_by]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
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
        array $order_by = ['b.id DESC'],
        int $limit = null
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS b')
            ->cols($columns)
            ->orderBy($order_by)
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
     * @param string|null $where
     * @param array $bind_values
     * @param array $columns
     * @return array
     */
    public function getFirstBlog(
        ?string $where = null,
        array $bind_values = [],
        array $columns = [
            'b.*',
            'bc.id AS category_id',
            'bc.name AS category_name',
            'bc.slug AS category_slug',
            'CONCAT(u.first_name, " ", u.last_name) AS creator',
            'CONCAT(uu.first_name, " ", uu.last_name) AS updater',
            'u.image AS creator_image',
            'u.id AS creator_id'
        ]
    ): array
    {
        $res = $this->getBlog($columns, $where, $bind_values, [], 1);
        if (!count($res)) return [];
        return $res[0];
    }

    /**
     * Use [b for blog], [bc for blog_categories], [u for users - created_by]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param int|null $limit
     * @param int $offset
     * @param bool $with_category
     * @param bool $with_users
     * @param array $columns
     * @return array
     */
    public function getLimitedBlog(
        ?string $where = null,
        array $bind_values = [],
        ?int $limit = null,
        int $offset = 0,
        bool $with_category = false,
        bool $with_users = false,
        array $columns = [
            'b.id',
            'b.title',
            'b.slug',
            'b.image',
            'b.abstract',
            'b.created_at'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS b')
            ->cols($columns);
        if (!empty($where)) {
            $select->where($where)->bindValues($bind_values);
        }
        if (!empty($limit) && 0 !== $limit) {
            $select->limit($limit);
        }
        $select->offset($offset);

        if ($with_category) {
            try {
                $select
                    ->cols(['category_name' => 'bc.name'])
                    ->innerJoin(self::TBL_BLOG_CATEGORIES . ' AS bc', 'b.category_id=bc.id');
            } catch (AuraException $e) {
                return [];
            }
        }
        if ($with_users) {
            try {
                $select
                    ->leftJoin(
                        self::TBL_USERS . ' AS u',
                        'b.created_by=u.id'
                    );
            } catch (AuraException $e) {
                return [];
            }
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param string|null $where
     * @param array $bind_values
     * @param bool $with_category
     * @param bool $with_users
     * @return int
     */
    public function getLimitedBlogCount(
        ?string $where = null,
        array $bind_values = [],
        bool $with_category = false,
        bool $with_users = false): int
    {
        $res = $this->getLimitedBlog($where, $bind_values, null, 0, $with_category, $with_users, ['COUNT(*) AS count']);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param array $columns
     * @return array
     */
    public function getSiblings(
        ?string $where = null,
        array $bind_values = [],
        int $limit = null,
        array $order_by = ['id DESC'],
        array $columns = ['id', 'slug', 'title']
    ): array
    {
        return $this->get($columns, $where, $bind_values, $order_by, $limit);
    }

    /**
     * @param array $info
     * @param int|null $limit
     * @return array
     */
    public function getRelatedBlog(array $info = [], int $limit = null): array
    {
        // create related blog where
        $where = '';
        $bindValues = [];
        $keywords = $info['keywords'] ?? [];
        foreach ($keywords as $k => $keyword) {
            $where .= " OR b.title LIKE :title{$k}";
            $where .= " OR b.fa_title LIKE :fa_title{$k}";
            $where .= " OR b.keywords LIKE :keywords{$k}";
            $where .= " OR b.abstract LIKE :abs{$k}";
            $bindValues["title{$k}"] = '%' . $keyword . '%';
            $bindValues["fa_title{$k}"] = '%' . $keyword . '%';
            $bindValues["keywords{$k}"] = '%' . $keyword . '%';
            $bindValues["abs{$k}"] = '%' . $keyword . '%';
        }
        $where = trim($where, ' OR');

        return $this->getBlog([
            'b.id', 'b.slug', 'b.title', 'b.image', 'b.created_at'
        ], $where, $bindValues, ['b.id DESC'], $limit);
    }

    /**
     * @return array
     */
    public function getArchive(): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table)
            ->cols(['COUNT(*) AS count', 'archive_tag'])
            ->groupBy(['archive_tag']);

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
        $res = $this->getBlog(['COUNT(DISTINCT(b.id)) AS count'], $where, $bindParams);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}