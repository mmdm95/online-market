<?php
$cart = cart();
$items = $cart->getItems();
?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <?php if (count($items)): ?>
                <!-- START CART ITEMS -->
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive shop_cart_table">
                        </div>

                        <div class="row no-gutters align-items-center mt-3">
                            <div class="col-lg-12 text-left text-md-right">
                                <button class="btn btn-line-fill btn-sm" type="button"
                                        id="__update_main_cart">
                                    بروزرسانی سبد خرید
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END CART ITEMS -->

                <!-- START DIVIDER -->
                <div class="row">
                    <div class="col-12">
                        <div class="medium_divider"></div>
                        <div class="divider center_icon"><i class="ti-shopping-cart-full"></i></div>
                        <div class="medium_divider"></div>
                    </div>
                </div>
                <!-- END DIVIDER -->

                <!-- START CART PRICE -->
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <div class="border p-3 p-md-4">
                            <div class="heading_s1 mb-3">
                                <h6>جمع سبد خرید</h6>
                            </div>
                            <div class="shop-cart-items-info-table">
                            </div>
                            <a href="<?= url('home.checkout'); ?>" class="btn btn-fill-out">ادامه</a>
                        </div>
                    </div>
                </div>
                <!-- END CART PRICE -->
            <?php else: ?>
                <!-- START EMPTY CART -->
                <div class="row">
                    <div class="col-12">
                        <div class="empty-cart-container">
                            <div class="empty-cart">
                                <div class="d-flex flex-column flex-lg-row align-items-center col">
                                    <i class="linearicons-cart-empty"></i>
                                    <span class="mt-3 ml-0 mt-lg-0 ml-lg-3">سبد خرید شما خالی است</span>
                                </div>
                                <a href="<?= url('home.search'); ?>" class="btn btn-info mt-3 ml-0 mt-lg-0 ml-lg-3">
                                    ادامه خرید
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END EMPTY CART -->
            <?php endif; ?>
        </div>
    </div>
    <!-- END SECTION SHOP -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->