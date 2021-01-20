<!-- Content area -->
<div class="content">

    <div class="row">
        <div class="col-xl-6">
            <!-- Category form for festival -->
            <div class="card">
                <?php load_partial('admin/card-header', ['header_title' => 'افزودن محصول به جشنواره']); ?>

                <input type="hidden" data-festival-id="<?= $festivalId; ?>">
                <div class="card-body">
                    <form action="#" id="__form_add_product_festival">
                        <div class="row">
                            <div class="form-group col-lg-8">
                                <select data-placeholder="محصول را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-add-product-festival-product"
                                        data-fouc>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id']; ?>">
                                            <?= $product['title']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <input type="number" class="form-control" min="0" max="100"
                                       placeholder="درصد تخفیف"
                                       name="inp-add-product-festival-percent">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                    class="btn btn-outline-success d-block d-sm-inline-block w-100 w-sm-auto">
                                افزودن به جشنواره
                                <i class="icon-add-to-list ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /category form for festival -->
        </div>

        <div class="col-xl-6">
            <!-- Product form for festival -->
            <div class="card">
                <?php load_partial('admin/card-header', ['header_title' => 'ویرایش محصولات دسته‌بندی در جشنواره']); ?>

                <div class="card-body">
                    <form action="#" id="__form_modify_product_festival">
                        <div class="row">
                            <div class="form-group col-lg-8">
                                <select data-placeholder="دسته‌بندی را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-modify-product-festival-category"
                                        data-fouc>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id']; ?>">
                                            <?= $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <input type="number" class="form-control" min="0" max="100"
                                       placeholder="درصد تخفیف"
                                       name="inp-modify-product-festival-percent">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                    class="btn btn-outline-info mr-2 mt-2 mt-md-0 d-block d-sm-inline-block w-100 w-sm-auto">
                                افزودن به جشنواره
                                <i class="icon-add-to-list ml-2"></i>
                            </button>
                            <button type="button" id="__btn_remove_product_festival"
                                    class="btn btn-outline-danger mt-2 mt-md-0 d-block d-sm-inline-block w-100 w-sm-auto">
                                حذف از جشنواره
                                <i class="icon-trash ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /product form for festival -->
        </div>
    </div>

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست محصولات جشنواره']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف محصولات از جشنواره کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"product_image"},{"data":"product_name"},{"data":"category_name"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.product.festival.dt.view')->getRelativeUrl() . $festivalId; ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر محصول</th>
                <th>نام محصول</th>
                <th>نام دسته‌بندی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>تصویر محصول</th>
                <th>نام محصول</th>
                <th>نام دسته‌بندی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
