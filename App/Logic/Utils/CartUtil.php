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
        //
        $total = 0.0;
        foreach ($items as $item) {
            $key = !empty($item['stepped_discounted_price'] ?? null) ? 'stepped_discounted_price' : 'discounted_price';

            if (!\is_scalar($item[$key])) return 0.0;

            $amount = (float)($item[$key] ?? 0.0);

            if (!$this->isDiscountValid($item['discount_until'])) {
                $amount = (float)($item['stepped_price'] ?? $item['price'] ?? 0.0);
            }

            // if we have tax for item
            $amount += ((float)($item['tax_rate'] ?? 0.0) * $amount) / 100.0;

            // we have n times of amount for price
            $amount = ($item['qnt'] ?? 0) * $amount;

            $total += $amount;
        }
        //
        return \loader()->setData([
            'items' => $items,
            'total_amount' => $total,
            'count' => $count,
        ])->getContent(partial_path('main/ajax/cart', false));
    }

    /**
     * @param $discount_until
     * @return bool
     */
    private function isDiscountValid($discount_until): bool
    {
        return empty($discount_until) || $discount_until >= \time();
    }
}
