<?php if (count($special_slider ?? [])): ?>
    <div class="section small_pt small_pb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading_tab_header">
                        <div class="heading_s2">
                            <h2>تخفیف ویژه های امروز</h2>
                        </div>
                        <div class="deal_timer">
                            <div class="countdown_time countdown_style1" data-time="<?= date('Y/m/d H:i:s', ); ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="product_slider carousel_slider owl-carousel owl-theme nav_style1" data-loop="true"
                         data-dots="false" data-nav="true" data-margin="20"
                         data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>
                        <div class="item">
                            <div class="product">
                                <div class="product_img">
                                    <a href="shop-product-detail.html">
                                        <img src="<?= asset_path('image', false); ?>/product_img1.jpg"
                                             alt="product_img1">
                                    </a>
                                    <div class="product_action_box">
                                        <ul class="list_none pr_action_btn">
                                            <li class="add-to-cart"><a href="#"><i class="icon-basket-loaded"></i>
                                                    افزودن به سبد خرید</a></li>
                                            <li><a href="shop-compare.html" class="popup-ajax"><i
                                                            class="icon-shuffle"></i></a></li>
                                            <li><a href="shop-quick-view.html" class="popup-ajax"><i
                                                            class="icon-magnifier-add"></i></a></li>
                                            <li><a href="#"><i class="icon-heart"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product_info">
                                    <h6 class="product_title"><a href="shop-product-detail.html">لباس آبی زنانه</a></h6>
                                    <div class="product_price">
                                        <span class="price">45000 تومان</span>
                                        <del>55000 تومان</del>
                                        <div class="on_sale">
                                            <span>٪35 تخفیف</span>
                                        </div>
                                    </div>
                                    <div class="rating_wrap">
                                        <div class="rating">
                                            <div class="product_rate" style="width:80%"></div>
                                        </div>
                                        <span class="rating_num">(21)</span>
                                    </div>
                                    <div class="pr_desc">
                                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                            طراحان گرافیک است، چاپگرها و متون بلکه روزنامه.</p>
                                    </div>
                                    <div class="pr_switch_wrap">
                                        <div class="product_color_switch">
                                            <span class="active" data-color="#87554B"></span>
                                            <span data-color="#333333"></span>
                                            <span data-color="#DA323F"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>