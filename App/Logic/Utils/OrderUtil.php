<?php

namespace App\Logic\Utils;

use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use Sim\Utils\StringUtil;

class OrderUtil
{
    /**
     * @param int $length
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getUniqueOrderCode(int $length = 15): string
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        do {
            $uniqueStr = StringUtil::randomString($length, StringUtil::RS_NUMBER, ['0']);

        } while ($orderModel->count('code=:code', ['code' => $uniqueStr]));
        return $uniqueStr;
    }

    /**
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getOrderBadgesWithCount(): array
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderBadgeModel $orderBadgeModel
         */
        $orderBadgeModel = container()->get(OrderBadgeModel::class);

        // get badges
        $badgeIds = $orderBadgeModel->get(['code', 'title', 'color'], 'is_deleted=:del', ['del' => DB_NO], ['id ASC']);
        $orderBadges = [];
        foreach ($badgeIds as $k => $badge) {
            $orderBadges[$k] = $badge;
            $orderBadges[$k]['count'] = $orderModel->count(
                'send_status_code=:ssc',
                [
                    'ssc' => $badge['code'],
                ]
            );
        }

        return $orderBadges;
    }
}
