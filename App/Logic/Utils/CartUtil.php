<?php

namespace App\Logic\Utils;

use Sim\Interfaces\IFileNotExistsException;

class CartUtil
{
    /**
     * @return string
     * @throws IFileNotExistsException
     */
    public function getCartSection(): string
    {
        $items = \cart()->getItems();
        $count = count($items);
        return \loader()->setData([
            'items' => $items,
            'total_amount' => \cart()->totalDiscountedPercentageWithTax(),
            'count' => $count,
        ])->getContent(partial_path('main/ajax/cart', false));
    }
}