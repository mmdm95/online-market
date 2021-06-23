<?php

namespace App\Logic\Utils;

class ReserveOrderUtil
{
    public static function checkReserveItemsNTakeAction()
    {
        // in case of not paying order in a specific time:
        //   - return order items to stock
        //   - also return used coupon to not use status
        //   - and make order a failed order
        // ...
    }
}
