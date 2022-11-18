<?php

use Sim\Utils\StringUtil;

?>

<div class="modal fade subscribe_popup" id="__compare_products" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
                </button>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="popup_content">
                            <div class="popup-text">
                                <div class="heading_s3 text-center">
                                    <h4>انتخاب محصول برای مقایسه</h4>
                                </div>
                                <p>
                                    محصولات جهت مقایسه در دسته‌بندی
                                    <a href="<?= url('home.search', [
                                        'category' => $category_id,
                                        'category_slug' => StringUtil::slugify($category_name),
                                    ])->getRelativeUrlTrimmed(); ?>">
                                        <span class="categories_name"><?= $category_name; ?></span>
                                    </a>
                                </p>
                            </div>

                            <form method="get" class="rounded_input" id="__frm_compare_products">
                                <div class="d-flex">
                                    <div class="form-group btn-group">
                                        <button class="btn btn-danger btn-block text-uppercase btn-radius px-3 d-none"
                                                title="حذف فیلتر جستجو"
                                                type="button"
                                                id="filter_remover_btn">
                                            <i class="linearicons-cross" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="form-group col">
                                        <input name="compare-q-inp" required type="text" class="form-control"
                                               placeholder="جستجوی محصول برای مقایسه...">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-fill-line btn-block text-uppercase btn-radius"
                                                title="جستجو"
                                                type="submit">
                                            جستجو
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="mt-3 compare-box-items d-flex justify-content-center flex-wrap"
                                 id="__compare_products_container">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>