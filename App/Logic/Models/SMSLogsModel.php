<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;

class SMSLogsModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_SMS_LOGS;

    /**
     * Use [sl for sms_logs], [u for users]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param array|null $order_by
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function getLogsDetailed(
        array   $columns = ['sl.*'],
        ?string $where = null,
        array   $bind_values = [],
        ?array  $order_by = ['id DESC'],
        ?int    $limit = null,
        int     $offset = 0
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS sl')
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
                ->leftJoin(
                    self::TBL_USERS . ' AS u',
                    'sl.sent_by=u.id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * Use [sl for sms_logs], [u for users]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getLogsDetailedCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getLogsDetailed(['sl.id', 'COUNT(sl.id) AS count'], $where, $bind_values);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }
}
