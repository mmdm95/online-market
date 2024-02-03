<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class WalletFlowModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_WALLET_FLOW;

    /**
     * Use [wf for wallet_flow], [mu for users], [u for users - for payer id]
     *
     * @param string|null $where
     * @param array $bind_values
     * @param array $order_by
     * @param int|null $limit
     * @param int $offset
     * @param array $columns
     * @return array
     */
    public function getWalletFlowInfo(
        ?string $where = null,
        array   $bind_values = [],
        array   $order_by = ['wf.id DESC'],
        ?int    $limit = null,
        int     $offset = 0,
        array   $columns = [
            'wf.*',
            'u.username',
            'u.first_name AS user_first_name',
            'u.last_name AS user_last_name'
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_WALLET_FLOW . ' AS wf')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by);

        try {
            $select
                ->leftJoin(
                    self::TBL_USERS . ' AS mu',
                    'mu.username=wf.username'
                )
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'u.id=wf.payer_id'
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
     * Use [wf for wallet_flow], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getWalletFlowInfoCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getWalletFlowInfo($where, $bind_values, [], null, 0, ['COUNT(DISTINCT(wf.id)) AS count']);
        if (count($res)) return (int)$res[0]['count'];
        return 0;
    }

    /**
     * Use [wf for wallet_flow], [w for wallet], [u for users]
     *
     * @param $code - order_code from orders table
     * @param array $columns
     * @return array
     */
    public function getFirstUserAndWalletInfoByCode(
        $code,
        array $columns = [
            'wf.*',
            'w.balance',
            'u.username',
            'u.first_name AS user_first_name',
            'u.last_name AS user_last_name',
        ]
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS wf')
            ->cols($columns)
            ->where('wf.order_code=:code')
            ->bindValue('code', $code)
            ->limit(1);
        try {
            $select
                ->innerJoin(
                    self::TBL_WALLET . ' AS w',
                    'wf.username=w.username'
                )
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'u.username=w.username'
                );
        } catch (AuraException $e) {
            return [];
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) {
            $res = $res[0];
        }

        return $res;
    }

    /**
     * @return void
     */
    public function removeUnwantedWalletCharges()
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_WALLET_FLOW . ' AS wf')
            ->cols([

            ])
            ->where('gf.is_success=:suc')
            ->bindValue('suc', DB_NO)
            ->where('gf.issue_date<:d')
            ->bindValue('d', time() - RESERVE_MAX_TIME);

        try {
            $select
                ->innerJoin(
                    self::TBL_GATEWAY_FLOW . ' AS gf',
                    'gf.order_code=wf.order_code'
                );
        } catch (AuraException $e) {
            return;
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        $inClause = [];
        $bindValues = [];
        foreach ($res as $k => $flow) {
            $inClause[] = ':id' . $k;
            $bindValues['id' . $k] = $flow['order_code'];
        }
        $this->delete('order_code IN (' . implode(',', $inClause) . ')', $bindValues);
    }

    /**
     * @param array $flowArr
     * @return bool
     */
    public function payOrderWithWallet(array $flowArr): bool
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderReserveModel $orderReserveModel
         */
        $orderReserveModel = container()->get(OrderReserveModel::class);

        $this->db->beginTransaction();

        $res = $this->insert($flowArr);

        $update = $this->connector->update();
        $update
            ->table(self::TBL_WALLET)
            ->set('balance', 'balance+(' . $flowArr['deposit_price'] . ')')
            ->where('username=:username')
            ->bindValues(['username' => $flowArr['username']]);
        $stmt = $this->db->prepare($update->getStatement());
        $res2 = $stmt->execute($update->getBindValues());

        if ($res && $res2) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }

    /**
     * @param $username
     * @param $orderCode
     * @param $orderPrice
     * @return bool
     */
    public function removeWalletPayment($username, $orderCode, $orderPrice): bool
    {
        $this->db->beginTransaction();

        $update = $this->connector->update();
        $update
            ->table(self::TBL_WALLET)
            ->set('balance', 'balance+(' . $orderPrice . ')')
            ->where('username=:username')
            ->bindValues(['username' => $username]);
        $stmt = $this->db->prepare($update->getStatement());
        $res = $stmt->execute($update->getBindValues());

        $res2 = $this->delete('order_code=:code', ['code' => $orderCode]);

        if ($res && $res2) {
            $this->db->commit();
            return true;
        }

        $this->db->rollBack();
        return false;
    }
}
