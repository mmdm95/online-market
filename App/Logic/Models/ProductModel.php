<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;
use Pecee\Http\Input\InputItem;
use Sim\Utils\StringUtil;

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
        array   $bind_values = [],
        array   $columns = [
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
            'p.stock_count',
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
        array   $bind_values = [],
        array   $order_by = ['pa.product_availability DESC', 'pa.is_available DESC', 'pa.stock_count DESC', 'pa.product_id DESC'],
        ?int    $limit = null,
        int     $offset = 0,
        array   $group_by = ['pa.product_id'],
        array   $columns = [
            'pa.product_id',
            'pa.slug',
            'pa.title',
            'pa.image',
            'pa.price',
            'pa.discounted_price',
            'pa.stock_count',
            'pa.max_cart_count',
        ]
    ): array
    {
        $order_by = array_unique(
            $order_by +
            [
                'pa.product_availability DESC',
                'pa.is_available DESC',
                'pa.stock_count DESC',
                'pa.product_id DESC'
            ]
        );

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
        array   $bind_values = []
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

        $ids = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        return array_map(function ($id) {
            return $id['related_id'];
        }, $ids);
    }

    /**
     * @param $product_id
     * @param array $columns
     * @param array $group_by
     * @return array
     */
    public function getRelatedProductsWithInfo($product_id, array $columns = ['product_id'], array $group_by = ['product_id']): array
    {
        $ids = $this->getRelatedProductsIds($product_id);

        if (!count($ids)) return [];

        $inClause = '';
        $bind_values = [];
        foreach ($ids as $k => $id) {
            $inClause .= ":id{$k},";
            $bind_values["id{$k}"] = $id;
        }
        $inClause = trim($inClause, ',');
        //-----
        if (empty($inClause)) return [];

        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ADVANCED)
            ->cols($columns)
            ->where('product_id IN (' . $inClause . ')')
            ->bindValues($bind_values)
            ->groupBy($group_by);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param $product_id
     * @return array
     */
    public function getImageGallery($product_id): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_GALLERY)
            ->cols(['image'])
            ->where('product_id=:pId')
            ->bindValues(['pId' => $product_id]);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [fp for favorite_user_product], [pa for product_advanced]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $columns
     * @return array
     */
    public function getUserFavoriteProducts(
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = ['fp.product_id DESC'],
        ?int    $limit = null,
        int     $offset = 0,
        array   $columns = [
            'fp.id AS favorite_id',
            'fp.product_id',
            'pa.slug',
            'pa.title',
            'pa.image',
            'pa.code',
            'pa.price',
            'pa.discount_from',
            'pa.discount_until',
            'pa.discounted_price',
            'pa.festival_expire',
            'pa.max_cart_count',
            'pa.stock_count',
            'pa.color_name',
            'pa.color_hex',
            'pa.show_color',
            'pa.is_patterned_color',
            'pa.size',
            'pa.guarantee',
            'pa.product_availability',
            'pa.is_available',
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_FAVORITE_USER_PRODUCT . ' AS fp')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);
        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        try {
            $select
                ->innerJoin(self::TBL_PRODUCT_ADVANCED . ' AS pa', 'fp.product_id=pa.product_id');
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

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

    /**
     * @param $user_id
     * @return int
     */
    public function userFavoriteProductCount($user_id): int
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_FAVORITE_USER_PRODUCT)
            ->cols(['COUNT(*) AS count'])
            ->where('user_id=:u_id')
            ->bindValue('u_id', $user_id);

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) return (int)$res[0]['count'];
        return 0;
    }

    /**
     * @param array $info
     * @param array $image_galley
     * @param array $products
     * @param array $related_products
     * @return bool
     * @throws \Exception
     */
    public function insertProduct(array $info, array $image_galley, array $products, array $related_products): bool
    {
        $this->db->beginTransaction();

        $insert = $this->connector->insert();
        $insert
            ->into($this->table)
            ->cols($info);

        $stmt = $this->db->prepare($insert->getStatement());
        $res = $stmt->execute($insert->getBindValues());

        if (!$res) {
            $this->db->rollBack();
            return false;
        }

        $productId = (int)$this->db->lastInsertId();

        foreach ($products as $product) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_PRODUCT_PROPERTY)
                ->cols([
                    'code' => StringUtil::uniqidReal(12),
                    'product_id' => $productId,
                    'stock_count' => $product['stock_count'],
                    'max_cart_count' => $product['max_cart'],
                    'price' => $product['price'],
                    'discounted_price' => $product['discount_price'],
                    'discount_from' => $product['discount_from'] ?: null,
                    'discount_until' => $product['discount_until'] ?: null,
                    'is_available' => $product['available'],
                    'color_hex' => $product['color_hex'],
                    'color_name' => $product['color_name'],
                    'show_color' => $product['show_color'],
                    'is_patterned_color' => $product['is_patterned_color'],
                    'size' => $product['size'] ?: null,
                    'guarantee' => $product['guarantee'] ?: null,
                    'weight' => $product['weight'],
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res = $stmt->execute($insert->getBindValues());

            if (!$res) break;
        }

        if (!$res) {
            $this->db->rollBack();
            return false;
        }

        $res2 = false;
        foreach ($image_galley as $image) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_PRODUCT_GALLERY)
                ->cols([
                    'product_id' => $productId,
                    'image' => get_image_name($image),
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res2 = $stmt->execute($insert->getBindValues());

            if (!$res2) break;
        }

        $res3 = true;
        $related_products = array_unique($related_products);
        /**
         * @var InputItem $related
         */
        foreach ($related_products as $related) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_PRODUCT_RELATED)
                ->cols([
                    'product_id' => $productId,
                    'related_id' => $related->getValue(),
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res3 = $stmt->execute($insert->getBindValues());

            if (!$res3) break;
        }

        if ($res && $res2 && $res3) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }

    /**
     * @param $product_id
     * @param array $info
     * @param array $image_galley
     * @param array $products
     * @param array $related_products
     * @return bool
     * @throws \Exception
     */
    public function updateProduct($product_id, array $info, array $image_galley, array $products, array $related_products): bool
    {
        $this->db->beginTransaction();

        $update = $this->connector->update();
        $update
            ->table($this->table)
            ->cols($info)
            ->where('id=:id')
            ->bindValues(['id' => $product_id]);

        $stmt = $this->db->prepare($update->getStatement());
        $res = $stmt->execute($update->getBindValues());

        if (!$res) {
            $this->db->rollBack();
            return false;
        }

        // delete all gallery images
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_PRODUCT_GALLERY)
            ->where('product_id=:pId')
            ->bindValues(['pId' => $product_id]);
        $stmt = $this->db->prepare($delete->getStatement());
        $res4 = $stmt->execute($delete->getBindValues());

        // delete all related products
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_PRODUCT_RELATED)
            ->where('product_id=:pId')
            ->bindValues(['pId' => $product_id]);
        $stmt = $this->db->prepare($delete->getStatement());
        $res5 = $stmt->execute($delete->getBindValues());

        if (!$res4 || !$res5) {
            $this->db->rollBack();
            return false;
        }

        // insert/update product properties
        $res6 = $this->updateProductProperty($product_id, $products, false);

        if (!$res6) {
            $this->db->rollBack();
            return false;
        }
        //

        $res2 = false;
        foreach ($image_galley as $image) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_PRODUCT_GALLERY)
                ->cols([
                    'product_id' => $product_id,
                    'image' => get_image_name($image),
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res2 = $stmt->execute($insert->getBindValues());

            if (!$res2) break;
        }

        $res3 = true;
        $related_products = array_unique($related_products);
        /**
         * @var InputItem $related
         */
        foreach ($related_products as $related) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_PRODUCT_RELATED)
                ->cols([
                    'product_id' => $product_id,
                    'related_id' => $related->getValue(),
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res3 = $stmt->execute($insert->getBindValues());

            if (!$res3) break;
        }

        if ($res && $res2 && $res3) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }

    /**
     * @param $product_id
     * @param array $products
     * @param bool $useInternalTransaction
     * @return bool
     * @throws \Exception
     */
    public function updateProductProperty($product_id, array $products, bool $useInternalTransaction = true): bool
    {
        if ($useInternalTransaction) {
            $this->db->beginTransaction();
        }

        // get previous products
        $productSelect = $this->connector->select();
        $productSelect
            ->cols(['id', 'color_hex', 'color_name', 'show_color', 'is_patterned_color'])
            ->from(self::TBL_PRODUCT_PROPERTY)
            ->where('product_id=:pId')
            ->bindValues(['pId' => $product_id]);
        $prevProducts = $this->db->fetchAll($productSelect->getStatement(), $productSelect->getBindValues());

        // insert new or update previous product properties
        $visitedProductsIds = [];
        $res = false;

        foreach ($products as $product) {
            $prevProduct = array_filter($prevProducts, function ($item) use ($product) {
                return $item['id'] == $product['id'];
            });
            $prevProduct = array_pop($prevProduct);

            if (empty($prevProduct)) {
                if (!($product['color_hex'] ?? false)) continue;

                $insertOrUpdate = $this->connector->insert();
                $insertOrUpdate
                    ->into(self::TBL_PRODUCT_PROPERTY)
                    ->cols([
                        'code' => StringUtil::uniqidReal(12),
                        'product_id' => $product_id,
                        'stock_count' => $product['stock_count'],
                        'max_cart_count' => $product['max_cart'],
                        'price' => $product['price'],
                        'discounted_price' => $product['discount_price'],
                        'discount_from' => $product['discount_from'] ?: null,
                        'discount_until' => $product['discount_until'] ?: null,
                        'is_available' => $product['available'],
                        'separate_consignment' => $product['separate_consignment'],
                        'color_hex' => $product['color_hex'],
                        'color_name' => $product['color_name'],
                        'show_color' => $product['show_color'],
                        'is_patterned_color' => $product['is_patterned_color'],
                        'size' => $product['size'] ?: null,
                        'guarantee' => $product['guarantee'] ?: null,
                        'weight' => $product['weight'],
                    ]);
            } else {
                $visitedProductsIds[] = $prevProduct['id'];

                $insertOrUpdate = $this->connector->update();
                $insertOrUpdate
                    ->table(self::TBL_PRODUCT_PROPERTY)
                    ->cols([
                        'stock_count' => $product['stock_count'],
                        'max_cart_count' => $product['max_cart'],
                        'price' => $product['price'],
                        'discounted_price' => $product['discount_price'],
                        'discount_from' => $product['discount_from'] ?: null,
                        'discount_until' => $product['discount_until'] ?: null,
                        'is_available' => $product['available'],
                        'separate_consignment' => $product['separate_consignment'],
                        'color_hex' => $product['color_hex'] ?: $prevProduct['color_hex'],
                        'color_name' => $product['color_name'] ?: $prevProduct['color_name'],
                        'show_color' => !is_null($product['show_color'])
                            ? $product['show_color']
                            : $prevProduct['show_color'],
                        'is_patterned_color' => !is_null($product['is_patterned_color'])
                            ? $product['is_patterned_color']
                            : $prevProduct['is_patterned_color'],
                        'size' => $product['size'] ?: null,
                        'guarantee' => $product['guarantee'] ?: null,
                        'weight' => $product['weight'],
                    ])
                    ->where('id=:id AND product_id=:pId')
                    ->bindValues(['id' => $prevProduct['id'], 'pId' => $product_id]);
            }

            $stmt = $this->db->prepare($insertOrUpdate->getStatement());
            $res = $stmt->execute($insertOrUpdate->getBindValues());

            if (!$res) break;
        }

        // remove not existence previous product properties
        $notExistenceProducts = array_filter($prevProducts, function ($item) use ($visitedProductsIds) {
            return !in_array($item['id'], $visitedProductsIds);
        });
        $notExistenceProductsIds = array_map(function ($item) {
            return $item['id'];
        }, $notExistenceProducts);

        if (count($notExistenceProductsIds)) {
            $idxRange = range(1, count($notExistenceProductsIds));
            $placeholders = [];
            $bindings = [];

            foreach ($idxRange as $id) {
                $key = 'id' . $id;
                $placeholders[] = ':' . $key;
                $bindings[$key] = array_shift($notExistenceProductsIds);
            }

            $delete = $this->connector->delete();
            $delete
                ->from(self::TBL_PRODUCT_PROPERTY)
                ->where('product_id=:pId')
                ->bindValues(['pId' => $product_id])
                ->where('id IN (' . implode(',', $placeholders) . ')')
                ->bindValues($bindings);
            $stmt = $this->db->prepare($delete->getStatement());
            $res2 = $stmt->execute($delete->getBindValues());
        } else {
            $res2 = true;
        }

        if (!$res || !$res2) {
            if ($useInternalTransaction) {
                $this->db->rollBack();
            }
            return false;
        }

        if ($useInternalTransaction) {
            $this->db->commit();
        }
        return true;
    }

    /**
     * @param array $ids
     * @param array $info
     * @return bool
     */
    public function updateBatchProducts(array $ids, array $info): bool
    {
        $this->db->beginTransaction();
        //
        $inClause = '';
        $bindParams = [];
        foreach ($ids as $k => $id) {
            $inClause .= ":id{$k},";
            $bindParams["id{$k}"] = $id;
        }
        $inClause = rtrim($inClause, ',');
        //
        $update = $this->connector->update();
        $update
            ->table($this->table)
            ->cols($info)
            ->where('id IN (' . $inClause . ')')
            ->bindValues($bindParams);

        $stmt = $this->db->prepare($update->getStatement());
        $res = $stmt->execute($update->getBindValues());

        if ($res) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }

    /**
     * @param array $ids
     * @param array $rawInfo
     * @param array $productInfo
     * @return bool
     */
    public function updateBatchProductsPrice(array $ids, array $rawInfo = [], array $productInfo = []): bool
    {
        $this->db->beginTransaction();

        $update = $this->connector->update();
        $update
            ->table(BaseModel::TBL_PRODUCTS)
            ->cols($productInfo);

        $stmt = $this->db->prepare($update->getStatement());
        $res = $stmt->execute($update->getBindValues());

        if (!$res) {
            $this->db->rollBack();
            return false;
        }
        //
        $inClause = '';
        $bindParams = [];
        foreach ($ids as $k => $id) {
            $inClause .= ":id{$k},";
            $bindParams["id{$k}"] = $id;
        }
        $inClause = rtrim($inClause, ',');
        //
        $update = $this->connector->update();
        $update
            ->table(BaseModel::TBL_PRODUCT_PROPERTY)
            ->where('product_id IN (' . $inClause . ')')
            ->bindValues($bindParams);

        foreach ($rawInfo as $k => $v) {
            $update->set($k, $v);
        }

        $stmt = $this->db->prepare($update->getStatement());
        $res = $stmt->execute($update->getBindValues());

        if ($res) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }

    /**
     * @param $product_id
     * @param array $columns
     * @param array $orderBy
     * @return array
     */
    public function getProductProperty($product_id, array $columns = ['*'], array $orderBy = ['id ASC']): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_PROPERTY)
            ->cols($columns)
            ->where('product_id=:id')
            ->bindValue('id', $product_id)
            ->orderBy($orderBy);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param $product_id
     * @return int
     */
    public function getProductPropertyCount($product_id): int
    {
        return $this->getProductPropertyWithInfoCount('product_id=:id', ['id' => $product_id]);
    }

    /**
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getProductPropertyWithInfo(array $columns = ['*'], ?string $where = null, array $bind_values = []): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_PROPERTY)
            ->cols($columns);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getProductPropertyWithInfoCount(?string $where = null, array $bind_values = []): int
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_PROPERTY)
            ->cols(['COUNT(*) AS count']);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param $product_code
     * @param array $columns
     * @return array
     */
    public function getSteppedPrices($product_code, array $columns = ['*']): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_STEPPED_PRICES)
            ->cols($columns)
            ->where('product_code=:code')
            ->bindValue('code', $product_code);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getSteppedPricesWithInfo(array $columns = ['*'], ?string $where = null, array $bind_values = []): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_STEPPED_PRICES)
            ->cols($columns);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getSteppedPricesCount(?string $where, array $bind_values = []): int
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_STEPPED_PRICES)
            ->cols(['COUNT(*) AS count']);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param array $values
     * @return bool
     */
    public function insertSteppedPrice(array $values): bool
    {
        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_STEPPED_PRICES)
            ->cols($values);

        $stmt = $this->db->prepare($insert->getStatement());
        $res = $stmt->execute($insert->getBindValues());

        return $res;
    }

    /**
     * @param array $data
     * @param string $where
     * @param array $bind_values
     * @return bool
     */
    public function updateSteppedPrice(array $data, string $where, array $bind_values = []): bool
    {
        $update = $this->connector->update();
        $update
            ->table(self::TBL_STEPPED_PRICES)
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
     * Use [pa] instead of [product_advanced], [pac] instead of [product_attr_category],
     * [pat] instead of [product_attrs], [pav] instead of [product_attr_values],
     * [pap] instead of [product_attr_product]
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
    public function getProductsWithAttrs(
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = ['pa.product_id DESC'],
        ?int    $limit = null,
        int     $offset = 0,
        array   $group_by = ['pa.product_id'],
        array   $columns = [
            'pa.product_id',
            'pa.slug',
            'pa.title',
            'pa.image',
            'pa.price',
            'pa.discounted_price',
            'pa.stock_count',
            'pa.max_cart_count',
        ]
    ): array
    {
        $order_by = array_unique(
            $order_by +
            [
                'pa.product_availability DESC',
                'pa.is_available DESC',
                'pa.stock_count DESC'
            ]
        );
        $select = $this->connector->select();


        $select
            ->fromRaw(
                '(' .
                'WITH pa AS ( ' .
                'SELECT *, ROW_NUMBER() OVER (PARTITION BY product_id ORDER BY product_availability DESC, is_available DESC, stock_count DESC) AS rn ' .
                'FROM ' . self::TBL_PRODUCT_ADVANCED . ')' . "\n" .
                'SELECT * ' .
                'FROM pa ' .
                'WHERE rn=1' .
                ') AS pa'
            )
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by)
            ->groupBy($group_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_PRODUCT_ATTR_CATEGORY . ' AS pac',
                    'pa.category_id=pac.c_id'
                )
                ->leftJoin(
                    self::TBL_PRODUCT_ATTRS . ' AS pat',
                    'pat.id=pac.p_attr_id'
                )
                ->leftJoin(
                    self::TBL_PRODUCT_ATTR_VALUES . ' AS pav',
                    'pat.id=pav.p_attr_id'
                )
                ->leftJoin(
                    self::TBL_PRODUCT_ATTR_PRODUCT . ' AS pap',
                    'pap.p_id=pa.product_id'
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
     * Use [pa] instead of [product_advanced], [pac] instead of [product_attr_category],
     * [pat] instead of [product_attrs], [pav] instead of [product_attr_values],
     * [pap] instead of [product_attr_product]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getProductsWithAttrsCount(
        ?string $where = null,
        array   $bind_values = []
    ): int
    {
        $res = $this->getProductsWithAttrs($where, $bind_values, [], null, 0, [], ['COUNT(DISTINCT(pa.product_id)) AS count']);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param $productId
     * @param int $viewCount
     * @return bool
     */
    public function increaseViewCount($productId, int $viewCount = 1): bool
    {
        $update = $this->connector->update();
        $update
            ->table($this->table)
            ->where('id=:id')
            ->bindValue('id', $productId)
            ->set('view_count', 'view_count+' . $viewCount);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }
}
