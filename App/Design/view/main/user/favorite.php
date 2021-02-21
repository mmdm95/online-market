<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <div class="row" id="__theia_sticky_sidebar_container">
                <!-- START DASHBOARD MENU -->
                <?php load_partial('main/user/dashboard-menu', ['user' => $user]); ?>
                <!-- END DASHBOARD MENU -->

                <div class="col-lg-9 col-md-8">
                    <div class="dashboard_content">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive wishlist_table">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="product-thumbnail">&nbsp;</th>
                                            <th class="product-name">محصول</th>
                                            <th class="product-price">قیمت</th>
                                            <th class="product-stock-status">وضعیت</th>
                                            <th class="product-add-to-cart"></th>
                                            <th class="product-remove">حذف</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="product-thumbnail"><a href="#"><img src="assets/images/product_img1.jpg" alt="product1"></a></td>
                                            <td class="product-name" data-title="محصول"><a href="#">لباس آبی زنانه</a></td>
                                            <td class="product-price" data-title="قیمت">45000 تومان</td>
                                            <td class="product-stock-status" data-title="وضعیت محصول"><span class="badge badge-pill badge-success">موجود</span></td>
                                            <td class="product-add-to-cart"><a href="#" class="btn btn-fill-out"><i class="icon-basket-loaded"></i> افزودن به سبد خرید</a></td>
                                            <td class="product-remove" data-title="حذف"><a href="#"><i class="ti-close"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="product-thumbnail"><a href="#"><img src="assets/images/product_img2.jpg" alt="product2"></a></td>
                                            <td class="product-name" data-title="محصول"><a href="#">چرم خاکستری</a></td>
                                            <td class="product-price" data-title="قیمت">55000 تومان</td>
                                            <td class="product-stock-status" data-title="وضعیت محصول"><span class="badge badge-pill badge-success">موجود</span></td>
                                            <td class="product-add-to-cart"><a href="#" class="btn btn-fill-out"><i class="icon-basket-loaded"></i> افزودن به سبد خرید</a></td>
                                            <td class="product-remove" data-title="حذف"><a href="#"><i class="ti-close"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="product-thumbnail"><a href="#"><img src="assets/images/product_img3.jpg" alt="product3"></a></td>
                                            <td class="product-name" data-title="محصول"><a href="#">لباس کامل زنانه</a></td>
                                            <td class="product-price" data-title="قیمت">68000 تومان</td>
                                            <td class="product-stock-status" data-title="وضعیت محصول"><span class="badge badge-pill badge-success">موجود</span></td>
                                            <td class="product-add-to-cart"><a href="#" class="btn btn-fill-out"><i class="icon-basket-loaded"></i> افزودن به سبد خرید</a></td>
                                            <td class="product-remove" data-title="حذف"><a href="#"><i class="ti-close"></i></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP -->
</div>