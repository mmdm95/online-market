<?php

namespace App\Logic\Models;

use Aura\Sql\ExtendedPdoInterface;
use Sim\DBConnector;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class BaseModel
{
    /**
     * Tables
     */
    const TBL_BLOG = 'blog';
    const TBL_BLOG_CATEGORIES = 'blog_categories';
    const TBL_BRANDS = 'brands';
    const TBL_CATEGORIES = 'categories';
    const TBL_CATEGORY_IMAGES = 'category_images';
    const TBL_CITIES = 'cities';
    const TBL_COLORS = 'colors';
    const TBL_COMMENTS = 'comments';
    const TBL_COMPLAINTS = 'complaints';
    const TBL_CONTACT_US = 'contact_us';
    const TBL_COUPONS = 'coupons';
    const TBL_DEPOSIT_TYPES = 'deposit_types';
    const TBL_FAQ = 'faq';
    const TBL_FAVORITE_USER_PRODUCT = 'favorite_user_product';
    const TBL_FESTIVALS = 'festivals';
    const TBL_INSTAGRAM_IMAGES = 'instagram_images';
    const TBL_MAIN_SLIDER = 'main_slider';
    const TBL_NEWSLETTERS = 'newsletters';
    const TBL_ORDERS = 'orders';
    const TBL_ORDER_BADGES = 'order_badges';
    const TBL_ORDER_ITEMS = 'order_items';
    const TBL_ORDER_RESERVED = 'order_reserved';
    const TBL_OUR_TEAM = 'our_team';
    const TBL_PRODUCTS = 'products';
    const TBL_PRODUCT_PROPERTY = 'product_property';
    const TBL_PRODUCT_ADVANCED = 'product_advanced';
    const TBL_PRODUCT_FESTIVAL = 'product_festival';
    const TBL_PRODUCT_GALLERY = 'product_gallery';
    const TBL_PRODUCT_RELATED = 'product_related';
    const TBL_PROVINCES = 'provinces';
    const TBL_ROLES = 'roles';
    const TBL_STATIC_PAGES = 'static_pages';
    const TBL_STEPPED_PRICES = 'stepped_prices';
    const TBL_UNITS = 'units';
    const TBL_USERS = 'users';
    const TBL_USER_ADDRESS = 'user_address';
    const TBL_USER_ROLE = 'user_role';
    const TBL_WALLET = 'wallet';
    const TBL_WALLET_FLOW = 'wallet_flow';

    /**
     * @var DBConnector
     */
    protected $connector;

    /**
     * @var ExtendedPdoInterface
     */
    protected $db;

    /**
     * @var string
     */
    protected $table;

    /**
     * BaseModel constructor.
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function __construct()
    {
        $this->connector = \connector();
        $this->db = $this->connector->getDb();
    }

    /**
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @return array
     */
    public function get(
        array $columns = ['*'],
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['id DESC'],
        int $limit = null
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table)
            ->cols($columns)
            ->orderBy($order_by);

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
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @return array
     */
    public function getFirst(
        array $columns = ['*'],
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['id DESC'],
        int $limit = null
    ): array
    {
        $res = $this->get($columns, $where, $bind_values, $order_by, $limit);
        if (count($res)) return $res[0];
        return [];
    }

    /**
     * @param array $values
     * @param bool $get_last_inserted_id
     * @return bool|int
     */
    public function insert(array $values, bool $get_last_inserted_id = false)
    {
        $insert = $this->connector->insert();
        $insert
            ->into($this->table)
            ->cols($values);

        $stmt = $this->db->prepare($insert->getStatement());
        $res = $stmt->execute($insert->getBindValues());

        if ($get_last_inserted_id) {
            // get the last insert ID
            $res = (int)$this->db->lastInsertId();
        }

        return $res;
    }

    /**
     * @param $unique_column
     * @param array $values
     * @param bool $get_id
     * @return bool|int
     */
    public function insertIfNotExists($unique_column, array $values, bool $get_id = false)
    {
        // create where clause to check existence
        $where = "{$unique_column}=:{$unique_column}0";
        $bindValues = [];
        $bindValues["{$unique_column}0"] = $values[$unique_column];

        if ($this->count($where, $bindValues)) {
            $insert = $this->connector->insert();
            $insert
                ->into($this->table)
                ->cols($values);

            $stmt = $this->db->prepare($insert->getStatement());
            $res = $stmt->execute($insert->getBindValues());

            if ($get_id) {
                // get the last insert ID
                $res = (int)$this->db->lastInsertId();
            }
        } else {
            $res = $this->update($values, $where, $bindValues);

            if ($get_id) {
                $res = $this->getFirst(['id'], $where, $bindValues)['id'] ?? 0;
            }
        }

        return $res;
    }

    /**
     * @param array $data
     * @param string $where
     * @param array $bind_values
     * @return bool
     */
    public function update(array $data, string $where, array $bind_values = []): bool
    {
        $update = $this->connector->update();
        $update
            ->table($this->table)
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
     * @param string $where
     * @param array $bind_values
     * @return bool
     */
    public function delete(string $where, array $bind_values = []): bool
    {
        $delete = $this->connector->delete();
        $delete
            ->from($this->table);

        if (!empty($where)) {
            $delete
                ->where($where)
                ->bindValues($bind_values);
        }

        $stmt = $this->db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }


    /**
     * @param string|null $where
     * @param array $bindParams
     * @return int
     */
    public function count(?string $where = null, array $bindParams = []): int
    {
        $select = $this->connector->select();
        $select
            ->cols(['COUNT(DISTINCT(id)) AS count'])
            ->from($this->table);

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