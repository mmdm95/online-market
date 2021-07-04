<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START 404 SECTION -->
    <div class="section">
        <div class="error_wrap">
            <div class="custom-container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 col-md-10 order-lg-first">
                        <div class="text-center">
                            <div class="error_txt"><?= local_number('404'); ?></div>
                            <h5 class="mb-2 mb-sm-3">صفحه مورد نظر شما یافت نشد!</h5>
                            <p>
                                صفحه مورد نظر شما منتقل شد ، حذف شد ، تغییر نام داد یا ممکن است هرگز وجود نداشته باشد.
                            </p>
                            <div class="search_form pb-3 pb-md-4">
                                <form action="<?= url('home.search')->getRelativeUrlTrimmed(); ?>" method="get">
                                    <input name="text" id="text" type="text" placeholder="جستجو" class="form-control">
                                    <button type="submit" class="btn icon_search">
                                        <i class="ion-ios-search-strong"></i>
                                    </button>
                                </form>
                            </div>
                            <a href="<?= url('home.index'); ?>" class="btn btn-fill-out">بازگشت به خانه</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END 404 SECTION -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->