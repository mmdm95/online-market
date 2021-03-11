<?php

$cart = cart();
$items = $cart->getItems();

?>

<div class="table-responsive">
    <table class="table">
        <tbody>
        <tr>
            <td class="cart_total_label">جمع سبد خرید</td>
            <td class="cart_total_amount">349000 تومان</td>
        </tr>
        <tr>
            <td class="cart_total_label">هزینه ارسال</td>
            <td class="cart_total_amount">ارسال رایگان</td>
        </tr>
        <tr>
            <td class="cart_total_label">جمع</td>
            <td class="cart_total_amount"><strong>349000 تومان</strong></td>
        </tr>
        </tbody>
    </table>
</div>