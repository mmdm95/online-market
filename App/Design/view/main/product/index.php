<!-- Home Popup Section -->
<?php load_partial('main/message/popup-newsletter'); ?>
<!-- End Screen Load Popup Section -->

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row align-items-center mb-4 pb-1">
                        <div class="col-12">
                            <div class="product_header">
                                <div class="product_header_left">
                                    <div class="custom_select">
                                        <select class="form-control form-control-sm">
                                            <?php foreach (PRODUCT_ORDERINGS as $k => $ordering): ?>
                                                <option value="<?= $k; ?>"><?= $ordering ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row shop_container list" id="__main_product_container">
                    </div>
                </div>
                <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                    <div class="sidebar">
                        <div class="widget">
                            <h5 class="widget_title">دسته بندی ها</h5>
                            <ul class="widget_categories">
                                <li>
                                    <a href="#">
                                        <span class="categories_name">زنانه</span>
                                        <span class="categories_num">(9)</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="widget">
                            <h5 class="widget_title">فیلتر قیمت</h5>
                            <div class="filter_price">
                                <div id="price_filter" data-min="0" data-max="500" data-min-value="50"
                                     data-max-value="300" data-price-sign="تومان"></div>
                                <div class="price_range">
                                    <span>قیمت: <span id="flt_price"></span></span>
                                    <input type="hidden" id="price_second">
                                    <input type="hidden" id="price_first">
                                </div>
                                <button type="button" class="mt-3 btn btn-info btn-block" id="__search_product_price_filter">
                                    اعمال فیلتر
                                </button>
                            </div>
                        </div>
                        <div class="widget">
                            <h5 class="widget_title">برندها</h5>
                            <ul class="list_brand">
                                <li>
                                    <div class="custome-checkbox">
                                        <input class="form-check-input" type="checkbox" name="checkbox"
                                               id="Arrivals" value="">
                                        <label class="form-check-label" for="Arrivals">
                                            <span>نایکی</span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="widget">
                            <h5 class="widget_title">سایزها</h5>
                            <div class="product_size_switch">
                                <span>xs</span>
                            </div>
                        </div>
                        <div class="widget">
                            <h5 class="widget_title">رنگ ها</h5>
                            <div class="product_color_switch">
                                <span data-color="#87554B"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->