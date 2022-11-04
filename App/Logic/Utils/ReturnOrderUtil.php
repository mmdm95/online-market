<?php

namespace App\Logic\Utils;

use App\Logic\Models\ReturnOrderModel;
use Sim\Utils\StringUtil;

class ReturnOrderUtil
{
    /**
     * @param int $length
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getUniqueReturnOrderCode(int $length = 15): string
    {
        /**
         * @var ReturnOrderModel $returnOrderModel
         */
        $returnOrderModel = container()->get(ReturnOrderModel::class);
        do {
            $uniqueStr = StringUtil::randomString($length, StringUtil::RS_NUMBER, ['0']);

        } while ($returnOrderModel->count('code=:code', ['code' => $uniqueStr]));
        return $uniqueStr;
    }
}
