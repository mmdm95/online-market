<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class WalletModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_WALLET;

    /**
     * Use [w for wallet], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $columns
     * @return array
     */
    public function getWalletInfo(
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = ['w.id DESC'],
        ?int    $limit = null,
        int     $offset = 0,
        array   $columns = [
            'w.*',
            'u.username',
            'u.first_name AS user_first_name',
            'u.last_name AS user_last_name'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS w')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'u.username=w.username'
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
     * Use [w for wallet], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getWalletInfoCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getWalletInfo($where, $bind_values, [], null, 0, ['COUNT(DISTINCT(w.id)) AS count']);
        if (count($res)) return (int)$res[0]['count'];
        return 0;
    }

    /**
     * @param $wallet_id
     * @param array $wallet_info
     * @param array $wallet_flow_info
     * @return bool
     */
    public function chargeWalletWithWalletId(
        $wallet_id,
        array $wallet_info,
        array $wallet_flow_info,
        array $wallet_info_set
    ): bool
    {
        $this->db->beginTransaction();

        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_WALLET_FLOW)
            ->cols($wallet_flow_info);

        $stmt = $this->db->prepare($insert->getStatement());
        $res = $stmt->execute($insert->getBindValues());

        if (!$res) {
            $this->db->rollBack();
            return false;
        }

        $update = $this->connector->update();
        $update
            ->table(self::TBL_WALLET)
            ->cols($wallet_info)
            ->where('id=:id')
            ->bindValue('id', $wallet_id);

        foreach ($wallet_info_set as $col => $info) {
            $update->set($col, $info);
        }

        $stmt = $this->db->prepare($update->getStatement());
        $res = $stmt->execute($update->getBindValues());

        if (!$res) {
            $this->db->rollBack();
            return false;
        }
        $this->db->commit();
        return true;
    }

    /**
     * @param $username
     * @param $balance
     * @return bool
     */
    public function increaseBalance($username, $balance): bool
    {
        $update = $this->connector->update();
        $update
            ->table($this->table)
            ->where('username=:username')
            ->bindValue('username', $username)
            ->set('balance', 'balance+' . (int)$balance);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }
}
