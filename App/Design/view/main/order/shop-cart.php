<?php
$cart = cart();
$items = $cart->getItems();
?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <?php if (count($items) || 1): ?>
                <!-- START CART ITEMS -->
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive shop_cart_table">
                        </div>

                        <div class="row no-gutters align-items-center mt-3">
                            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                <div class="coupon field_form input-group">
                                    <input type="text" value="" class="form-control form-control-sm"
                                           placeholder="شماره کوپن را وارد کنید...">
                                    <div class="input-group-append">
                                        <button class="btn btn-fill-out btn-sm" type="submit">
                                            کوپن
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 text-left text-md-right">
                                <button class="btn btn-line-fill btn-sm" type="button">
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
                    <div class="col-md-6">
                        <div class="heading_s1 mb-3">
                            <h6>محاسبه هزینه ارسال</h6>
                        </div>
                        <form class="field_form shipping_calculator">
                            <div class="form-row">
                                <div class="form-group col-lg-12">
                                    <div class="custom_select">
                                        <select class="form-control">
                                            <option value="">یک گزینه را انتخاب کنید ...</option>
                                            <option value="AF"> افغانستان</option>
                                            <option value="AL"> آلبانی</option>
                                            <option value="DZ"> الجزایر</option>
                                            <option value="AO"> آنگولا</option>
                                            <option value="AI"> آنگولا</option>
                                            <option value="AQ"> قطب جنوب</option>
                                            <option value="AG"> آنتیگوا و باربودا</option>
                                            <option value="AR"> آرژانتین</option>
                                            <option value="AM"> ارمنستان</option>
                                            <option value="AW"> آروبا</option>
                                            <option value="AU"> استرالیا</option>
                                            <option value="AT"> اتریش</option>
                                            <option value="AZ"> آذربایجان</option>
                                            <option value="BH"> بحرین</option>
                                            <option value="BD"> بنگلادش</option>
                                            <option value="BB"> باربادوس</option>
                                            <option value="BY"> بلاروس</option>
                                            <option value="BE"> بلژیک</option>
                                            <option value="BZ"> بلیز</option>
                                            <option value="BJ"> بنین</option>
                                            <option value="BM"> برمودا</option>
                                            <option value="BT"> بوتان</option>
                                            <option value="BO"> بولیوی</option>
                                            <option value="BA"> بوسنی و هرزگوین</option>
                                            <option value="BW"> بوتسوانا</option>
                                            <option value="BR"> برزیل</option>
                                            <option value="IO"> قلمرو اقیانوس هند بریتانیا</option>
                                            <option value="VG"> جزایر ویرجین بریتانیا</option>
                                            <option value="BN"> برونئی</option>
                                            <option value="BG"> بلغارستان</option>
                                            <option value="BI"> بوروندی</option>
                                            <option value="KH"> کامبوج</option>
                                            <option value="CM"> کامرون</option>
                                            <option value="CA"> کانادا</option>
                                            <option value="CV"> کیپ ورد</option>
                                            <option value="KY"> جزایر کیمن</option>
                                            <option value="CF"> جمهوری آفریقای مرکزی</option>
                                            <option value="TD"> چاد</option>
                                            <option value="CL"> شیلی</option>
                                            <option value="CN"> چین</option>
                                            <option value="CX"> جزیره کریسمس</option>
                                            <option value="CC"> جزایر کوکوس</option>
                                            <option value="CO"> کلمبیا</option>
                                            <option value="KM"> کومور</option>
                                            <option value="CG"> کنگو</option>
                                            <option value="CD"> کنگو</option>
                                            <option value="CK"> جزایر کوک</option>
                                            <option value="CR"> کاستاریکا</option>
                                            <option value="HR"> کرواسی</option>
                                            <option value="CU"> کوبا</option>
                                            <option value="CW"> کورااائو</option>
                                            <option value="CY"> قبرس</option>
                                            <option value="CZ"> جمهوری چک</option>
                                            <option value="DK"> دانمارک</option>
                                            <option value="DJ"> جیبوتی</option>
                                            <option value="DM"> دومینیکا</option>
                                            <option value="DO"> جمهوری دومنیکن</option>
                                            <option value="EC"> اکوادور</option>
                                            <option value="EG"> مصر</option>
                                            <option value="SV"> السالوادور</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <input required="required" placeholder="کشور" class="form-control" name="name"
                                           type="text">
                                </div>
                                <div class="form-group col-lg-6">
                                    <input required="required" placeholder="کد پستی" class="form-control" name="name"
                                           type="text">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-12">
                                    <button class="btn btn-fill-line" type="submit">به روزرسانی کل</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <div class="border p-3 p-md-4">
                            <div class="heading_s1 mb-3">
                                <h6>جمع سبد خرید</h6>
                            </div>
                            <div class="shop-cart-info-table">
                            </div>
                            <a href="#" class="btn btn-fill-out">ادامه</a>
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