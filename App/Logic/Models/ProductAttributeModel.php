<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;
use DI\DependencyException;
use DI\NotFoundException;

class ProductAttributeModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_PRODUCT_ATTRS;

    /**
     * Use [pac for product_attr_category], [pa for product_attrs], [c for categories]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function getAttrCategories(
        array   $columns = ['pac.*'],
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = ['pac.id DESC'],
        ?int    $limit = null,
        int     $offset = 0
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ATTR_CATEGORY . ' AS pac')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

        try {
            $select
                ->innerJoin(
                    $this->table . ' AS pa',
                    'pac.p_attr_id=pa.id'
                )
                ->innerJoin(
                    self::TBL_CATEGORIES . ' AS c',
                    'pac.c_id=c.id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [pac for product_attr_category], [pa for product_attrs], [c for categories]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getAttrCategoriesCount(
        ?string $where = null,
        array $bind_values = []
    ): int
    {
        $res = $this->getAttrCategories(['COUNT(pac.id) AS count'], $where, $bind_values);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * Use [pac for product_attr_category], [pa for product_attrs], [c for categories]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @return array
     */
    public function getFirstAttrCategory(
        array   $columns = ['pac.*'],
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = []
    ): array
    {
        $res = $this->getAttrCategories($columns, $where, $bind_values, $order_by, 1);
        if (count($res)) return $res[0];
        return [];
    }

    /**
     * @param int $attr_id
     * @param int $category_id
     * @return bool
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function assignAttrToCategory(int $attr_id, int $category_id): bool
    {
        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_PRODUCT_ATTR_CATEGORY)
            ->cols([
                'p_attr_id' => $attr_id,
                'c_id' => $category_id,
                'created_at' => time(),
                'created_by' => auth_admin()->getCurrentUser()['id'] ?? null
            ]);
        $stmt = $this->db->prepare($insert->getStatement());
        return $stmt->execute($insert->getBindValues());
    }

    /**
     * @param int $id
     * @param int $attr_id
     * @param int $category_id
     * @return bool
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function updateAssignedAttrCategory(int $id, int $attr_id, int $category_id): bool
    {
        $update = $this->connector->update();
        $update
            ->table(self::TBL_PRODUCT_ATTR_CATEGORY)
            ->cols([
                'p_attr_id' => $attr_id,
                'c_id' => $category_id,
                'updated_at' => time(),
                'updated_by' => auth_admin()->getCurrentUser()['id'] ?? null
            ])
            ->where('id=:id')
            ->bindValue('id', $id);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }

    /**
     * @param int $attr_id
     * @param string $attr_val
     * @return bool
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function assignValueToAttr(int $attr_id, string $attr_val): bool
    {
        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_PRODUCT_ATTR_VALUES)
            ->cols([
                'p_attr_id' => $attr_id,
                'attr_val' => $attr_val,
                'created_at' => time(),
                'created_by' => auth_admin()->getCurrentUser()['id'] ?? null
            ]);

        $stmt = $this->db->prepare($insert->getStatement());
        return $stmt->execute($insert->getBindValues());
    }

    /**
     * @param int $id
     * @param string $attr_val
     * @return bool
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function updateAssignedValueToAttr(int $id, string $attr_val): bool
    {
        $update = $this->connector->update();
        $update
            ->table(self::TBL_PRODUCT_ATTR_VALUES)
            ->cols([
                'attr_val' => $attr_val,
                'updated_at' => time(),
                'updated_by' => auth_admin()->getCurrentUser()['id'] ?? null
            ])
            ->where('id=:id')
            ->bindValue('id', $id);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }

    /**
     * Use [pav for product_attr_values], [pa for product_attrs]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function getAttrValues(
        array   $columns = ['pav.*'],
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = ['pav.id DESC'],
        ?int    $limit = null,
        int     $offset = 0
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ATTR_VALUES . ' AS pav')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

        try {
            $select
                ->innerJoin(
                    $this->table . ' AS pa',
                    'pav.p_attr_id=pa.id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [pav for product_attr_values], [pa for product_attrs]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getAttrValuesCount(
        ?string $where = null,
        array $bind_values = []
    ): int
    {
        $res = $this->getAttrValues(['COUNT(pav.id) AS count'], $where, $bind_values);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * Use [pav for product_attr_values], [pa for product_attrs]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @return array
     */
    public function getFirstAttrValues(
        array   $columns = ['pav.*'],
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = []
    ): array
    {
        $res = $this->getAttrValues($columns, $where, $bind_values, $order_by, 1);
        if (count($res)) return $res[0];
        return [];
    }

    /**
     * @param int $pId
     * @return bool
     */
    public function removeAllProductAttrValues(int $pId): bool
    {
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_PRODUCT_ATTR_PRODUCT)
            ->where('p_id=:id')
            ->bindValue('id', $pId);

        $stmt = $this->db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }

    /**
     * Use [pav for product_attr_values], [pa for product_attrs], [pac for product_attr_category]
     *
     * @param int $categoryId
     * @param array $columns
     * @param array $order_by
     * @param array $group_by
     * @return array
     */
    public function getProductAttrValuesOfCategory(
        int   $categoryId,
        array $columns = ['pav.*'],
        array $order_by = ['pav.id ASC'],
        array $group_by = []
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ATTR_VALUES . ' AS pav')
            ->cols($columns)
            ->where('pac.c_id=:cId')
            ->bindValue('cId', $categoryId)
            ->orderBy($order_by)
            ->groupBy($group_by);

        try {
            $select
                ->innerJoin(
                    $this->table . ' AS pa',
                    'pav.p_attr_id=pa.id'
                )
                ->innerJoin(
                    self::TBL_PRODUCT_ATTR_CATEGORY . ' AS pac',
                    'pac.p_attr_id=pa.id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [pap for product_attr_product], [pav for product_attr_values]
     *
     * @param int $productId
     * @param array $columns
     * @param array $order_by
     * @return array
     */
    public function getProductAttrValues(
        int   $productId,
        array $columns = ['pap.*'],
        array $order_by = ['pap.id ASC']
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_PRODUCT_ATTR_PRODUCT . ' AS pap')
            ->cols($columns)
            ->where('pap.p_id=:pId')
            ->bindValue('pId', $productId)
            ->orderBy($order_by);

        try {
            $select
                ->innerJoin(
                    self::TBL_PRODUCT_ATTR_VALUES . ' AS pav',
                    'pap.p_attr_val_id=pav.id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param int $p_id
     * @param array $value_ids
     * @return bool
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function assignProductAttrValues(int $p_id, array $value_ids): bool
    {
        $this->db->beginTransaction();

        // delete all product attribute product
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_PRODUCT_ATTR_PRODUCT)
            ->where('p_id=:pId')
            ->bindValues(['pId' => $p_id]);
        $stmt = $this->db->prepare($delete->getStatement());
        $res = $stmt->execute($delete->getBindValues());

        $res2 = true;
        foreach ($value_ids as $val) {
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_PRODUCT_ATTR_PRODUCT)
                ->cols([
                    'p_id' => $p_id,
                    'p_attr_val_id' => $val,
                    'created_at' => time(),
                    'created_by' => auth_admin()->getCurrentUser()['id'] ?? null
                ]);

            $stmt = $this->db->prepare($insert->getStatement());
            $res2 = $stmt->execute($insert->getBindValues());

            if (!$res2) break;
        }

        if ($res && $res2) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }
}
