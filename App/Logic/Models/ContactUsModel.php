<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class ContactUsModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_CONTACT_US;

    /**
     * Use [cu for contact_us], [u for users]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function getContacts(
        array $columns = [
            'cu.*',
            'CONCAT(u.first_name, " ", u.last_name) AS creator',
            'u.id AS creator_id'
        ],
        ?string $where = null,
        array $bind_values = [],
        array $order_by = ['cu.id DESC'],
        ?int $limit = null,
        int $offset = 0
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS cu')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'cu.user_id=u.id'
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
     * Use [cu for contact_us], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $columns
     * @return array
     */
    public function getSingleContact(
        ?string $where = null,
        array $bind_values = [],
        array $columns = [
            'cu.*',
            'u.first_name AS creator_name',
            'u.last_name AS creator_family',
            'u.id AS creator_id'
        ]
    ): array
    {
        $res = $this->getContacts($columns, $where, $bind_values, [], 1);
        if (count($res)) return $res[0];
        return [];
    }

    /**
     * Use [cu for contact_us], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getContactsCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getContacts(['COUNT(cu.id) AS count'], $where, $bind_values);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}