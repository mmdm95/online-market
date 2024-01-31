<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class AddressCompanyModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_USER_COMPANY_ADDRESS;

    /**
     * Use [uc_addr for user_company_address], [p for provinces], [c for cities]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param int|null $limit
     * @param int $offset
     * @param array $order_by
     * @return array
     */
    public function getUserAddresses(
        array $columns = ['uc_addr.*', 'c.name AS city_name', 'p.name AS province_name'],
        ?string $where = null,
        array $bind_values = [],
        ?int $limit = null,
        int $offset = 0,
        array $order_by = ['uc_addr.id DESC']): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_USER_COMPANY_ADDRESS . ' AS uc_addr')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->innerJoin(
                    self::TBL_PROVINCES . ' AS p',
                    'p.id=uc_addr.province_id'
                )->innerJoin(
                    self::TBL_CITIES . ' AS c',
                    'c.id=uc_addr.city_id'
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
     * Use [uc_addr for user_company_address], [p for provinces], [c for cities]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $columns
     * @return array
     */
    public function getSingleUserAddress(
        ?string $where = null,
        array $bind_values = [],
        array $columns = ['uc_addr.*', 'c.name AS city_name', 'p.name AS province_name']
    ): array
    {
        $res = $this->getUserAddresses($columns, $where, $bind_values, 1);
        if (count($res)) return $res[0];
        return [];
    }

    /**
     * Use [uc_addr for user_company_address], [p for provinces], [c for cities]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getUserAddressesCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getUserAddresses(['COUNT(DISTINCT(uc_addr.id)) AS count'], $where, $bind_values);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param int $addr_id
     * @return bool
     */
    public function deleteAddress(int $addr_id): bool
    {
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_USER_COMPANY_ADDRESS)
            ->where('id=:id')
            ->bindValues([
                'id' => $addr_id,
            ]);

        $stmt = $this->db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }

    /**
     * @param array $data
     * @param int $addr_id
     * @return bool
     */
    public function updateAddress(array $data, int $addr_id): bool
    {
        $update = $this->connector->update();
        $update
            ->table(self::TBL_USER_COMPANY_ADDRESS)
            ->cols($data)
            ->where('id=:id')
            ->bindValues([
                'id' => $addr_id,
            ]);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }
}