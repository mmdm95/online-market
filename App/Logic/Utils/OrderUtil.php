<?php

namespace App\Logic\Utils;

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
    public static function getUniqueOrderCode($length = 20): string
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        do {
            $uniqueStr = StringUtil::randomString($length, StringUtil::RS_NUMBER);
        } while ($orderModel->count('code=:code', ['code' => $uniqueStr]));
        return $uniqueStr;
    }
}
