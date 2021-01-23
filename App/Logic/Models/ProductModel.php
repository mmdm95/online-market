<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class ProductModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_PRODUCTS;

    /**
     * Use [p] instead of [products], [b] instead of [brands], [c] instead of [categories]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $columns
     * @return array
     */
    public function getSingleProduct(
        ?string $where = null,
        array $bind_values = [],
        array $columns = [
            'p.id',
            'p.slug',
            'p.title',
            'p.image',
            'p.category_id',
            'p.keywords',
            'p.is_available',
            'p.is_special',
            'p.unit_title',
            'p.unit_sign',
            'p.body',
            'p.properties',
            'p.baby_property',
            'p.brand_id',
            'p.allow_commenting',
            'b.name AS brand_name',
            'b.slug AS brand_slug',
            'b.keywords AS brand_keywords',
            'c.name AS category_name',
            'c.keywords AS category_keywords',
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS p')
            ->cols($columns)
            ->limit(1);

        try {
            $select
                ->innerJoin(self::TBL_BRANDS . ' AS b', 'p.brand_id=b.id')
                ->innerJoin(self::TBL_CATEGORIES . ' AS c', 'p.category_id=c.id');
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) return $res[0];
        return [];
    }

    /**
     * Use [pa] instead of [product_advanced]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $group_by
     * @param array $columns
     * @return array
     */
    public function getLimitedProduct(
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['pa.product_id DESC'],
        ?int $limit = null,
        int $offset = 0,
        array $group_by = ['pa.product_id'],
        array $columns = [
            'pa.product_id',
            'pa.slug',
            'pa.title',
            'pa.image',
            'pa.price',
            'pa.discounted_price'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ADVANCED . ' AS pa')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by)
            ->groupBy($group_by);

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
     * Use [pa] instead of [product_advanced]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getLimitedProductCount(
        ?string $where = null,
        array $bind_values = []
    ): int
    {
        $res = $this->getLimitedProduct($where, $bind_values, [], null, 0, [], ['COUNT(DISTINCT(pa.product_id)) AS count']);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param $product_id
     * @return array
     */
    public function getRelatedProductsIds($product_id): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_RELATED)
            ->cols(['related_id'])
            ->where('product_id=:pId')
            ->bindValue('pId', $product_id);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return array - [result, type, message] accordingly
     */
    public function toggleUserFavoriteProduct($user_id, $product_id): array
    {
        $isFavorite = $this->isUserFavoriteProduct($user_id, $product_id);
        if (!$isFavorite) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_FAVORITE_USER_PRODUCT)
                ->cols([
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'created_at' => time(),
                ]);

            $stmt = $this->db->prepare($insert->getStatement());
            $res = $stmt->execute($insert->getBindValues());
            $type = RESPONSE_TYPE_SUCCESS;
            $message = 'محصول به لیست علاقه‌مندی‌ها اضافه شد.';
        } else {
            $res = $this->removeUserFavoriteProduct($user_id, $product_id);
            $type = RESPONSE_TYPE_INFO;
            $message = 'محصول از لیست علاقه‌مندی‌ها حذف شد.';
        }
        if (!$res) {
            $type = RESPONSE_TYPE_WARNING;
            $message = 'خطا در انجام عملیات!';
        }
        return [$res, $type, $message];
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return bool
     */
    public function removeUserFavoriteProduct($user_id, $product_id): bool
    {
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_FAVORITE_USER_PRODUCT)
            ->where('user_id=:u_id')
            ->bindValue('u_id', $user_id)
            ->where('product_id=:p_id')
            ->bindValue('p_id', $product_id);

        $stmt = $this->db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return bool
     */
    public function isUserFavoriteProduct($user_id, $product_id): bool
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_FAVORITE_USER_PRODUCT)
            ->cols(['COUNT(*) AS count'])
            ->where('user_id=:u_id')
            ->bindValue('u_id', $user_id)
            ->where('product_id=:p_id')
            ->bindValue('p_id', $product_id);

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) return (int)$res[0]['count'] > 0;
        return false;
    }
}