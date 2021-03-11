<?php

$cart = cart();
$items = $cart->getItems();

?>

<table class="table">
    <thead>
    <tr>
        <th class="product-thumbnail">&nbsp;</th>
        <th class="product-name">محصول</th>
        <th class="product-price">قیمت</th>
        <th class="product-quantity">تعداد</th>
        <th class="product-subtotal">جمع</th>
        <th class="product-remove">حذف</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="product-thumbnail"><a href="#"><img src="assets/images/product_img1.jpg"
                                                       alt="product1"></a></td>
        <td class="product-name" data-title="محصول"><a href="#">لباس آبی زنانه</a></td>
        <td class="product-price" data-title="قیمت">45000 تومان</td>
        <td class="product-quantity" data-title="تعداد">
            <div class="quantity">
                <input type="button" value="-" class="minus">
                <input type="text" name="quantity" value="2" title="Qty" class="qty"
                       size="4">
                <input type="button" value="+" class="plus">
            </div>
        </td>
        <td class="product-subtotal" data-title="جمع">90000 تومان</td>
        <td class="product-remove" data-title="حذف"><a href="#"><i class="ti-close"></i></a>
        </td>
    </tr>
    <tr>
        <td class="product-thumbnail"><a href="#"><img src="assets/images/product_img2.jpg"
                                                       alt="product2"></a></td>
        <td class="product-name" data-title="محصول"><a href="#">چرم خاکستری</a></td>
        <td class="product-price" data-title="قیمت">55000 تومان</td>
        <td class="product-quantity" data-title="تعداد">
            <div class="quantity">
                <input type="button" value="-" class="minus">
                <input type="text" name="quantity" value="1" title="Qty" class="qty"
                       size="4">
                <input type="button" value="+" class="plus">
            </div>
        </td>
        <td class="product-subtotal" data-title="جمع">55000 تومان</td>
        <td class="product-remove" data-title="حذف"><a href="#"><i class="ti-close"></i></a>
        </td>
    </tr>
    <tr>
        <td class="product-thumbnail"><a href="#"><img src="assets/images/product_img3.jpg"
                                                       alt="product3"></a></td>
        <td class="product-name" data-title="محصول"><a href="#">لباس کامل زنانه</a></td>
        <td class="product-price" data-title="قیمت">68000 تومان</td>
        <td class="product-quantity" data-title="تعداد">
            <div class="quantity">
                <input type="button" value="-" class="minus">
                <input type="text" name="quantity" value="3" title="Qty" class="qty"
                       size="4">
                <input type="button" value="+" class="plus">
            </div>
        </td>
        <td class="product-subtotal" data-title="جمع">204000 تومان</td>
        <td class="product-remove" data-title="حذف"><a href="#"><i class="ti-close"></i></a>
        </td>
    </tr>
    </tbody>
</table>