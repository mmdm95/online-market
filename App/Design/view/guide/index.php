<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card col-lg-9">
        <?php load_partial('admin/card-header', ['header_title' => 'راهنمای لینک']); ?>

        <?php
        $base = rtrim(get_base_url(), '/\\');
        ?>

        <div class="card-body">
            <div class="list-feed">
                <div class="list-feed-item border-warning-400">
                    لینک درباره ما:
                    <a href="<?= url('home.about')->getRelativeUrlTrimmed(); ?>" dir="ltr" target="_blank">
                        about
                    </a>

                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="aboutLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.about')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#aboutLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-info-400">
                    لینک تماس با ما:
                    <a href="<?= url('home.contact')->getRelativeUrlTrimmed(); ?>" dir="ltr" target="_blank">
                        contact
                    </a>

                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="contactLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.contact')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#contactLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-purple-400">
                    لینک صفحه ثبت نام:
                    <a href="<?= url('home.login')->getRelativeUrlTrimmed(); ?>" dir="ltr" target="_blank">
                        login
                    </a>

                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="loginLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.login')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#loginLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-pink-400">
                    لینک صفحه ثبت نام:
                    <a href="<?= url('home.signup')->getRelativeUrlTrimmed(); ?>" dir="ltr" target="_blank">
                        signup
                    </a>

                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="signupLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.signup')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#signupLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-slate-600">
                    لینک صفحه فراموشی کلمه عبور:
                    <a href="<?= url('home.forget-password')->getRelativeUrlTrimmed(); ?>" dir="ltr" target="_blank">
                        forget password
                    </a>

                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="fpLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.forget-password')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#fpLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-teal-400">
                    لینک صفحه سؤالات متداول:
                    <a href="<?= url('home.faq')->getRelativeUrlTrimmed(); ?>" dir="ltr" target="_blank">
                        faq
                    </a>

                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="faqLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.faq')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#faqLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-danger-400">
                    لینک صفحه ثبت شکایات:
                    <a href="<?= url('home.complaint')->getRelativeUrlTrimmed(); ?>" dir="ltr" target="_blank">
                        complaint
                    </a>

                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="complaintLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.complaint')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#complaintLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-success-400">
                    لینک صفحات ثابت:
                    <div class="alert alert-info">
                        بعد از لینک زیر، نام صفحه مورد نظر خود را که در هنگام ایجاد صفحه ثابت وارد کرده‌اید را قرار
                        دهید.
                    </div>
                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="staticLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.pages')->getRelativeUrl(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#staticLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-brown-400">
                    لینک تمامی محصولات:
                    <div class="alert alert-info">
                        بعد از لینک زیر، شناسه دسته‌بندی مورد نظر خود را که در هنگام ایجاد صفحه ثابت وارد کرده‌اید را
                        قرار دهید.
                    </div>
                    <div class="alert alert-primary">
                        <div class="alert-heading">
                            آپشن‌های اضافی که می‌توانید در انتهای آدرس و پس از علامت
                            <code>?</code>
                            قرار دهید عبارتند از:
                        </div>
                        <ul class="list-unstyled mt-2 mb-0">
                            <li>
                                <strong dir="ltr">q</strong>
                                -
                                رشته مورد نظر برای جستجو
                            </li>
                            <li>
                                <strong dir="ltr">tag</strong>
                                -
                                رشته مورد نظر برای جستجو برچسب
                            </li>
                            <li>
                                <strong dir="ltr">price[min]</strong>
                                -
                                نمایش محصولات از قیمت
                            </li>
                            <li>
                                <strong dir="ltr">price[max]</strong>
                                -
                                نمایش محصولات تا قیمت
                            </li>
                            <li>
                                <strong dir="ltr">color[]</strong>
                                -
                                رنگ‌های محصول، به صورت کد هگز. برای مثال
                                <code>#ffffff</code>
                            </li>
                            <li>
                                <strong dir="ltr">brands[]</strong>
                                -
                                برندهای مورد نظر
                            </li>
                            <li>
                                <strong dir="ltr">is_available</strong>
                                -
                                مقدار
                                <code>1</code>
                                به معنای موجود بودن محصول می‌باشد
                            </li>
                            <li>
                                <strong dir="ltr">is_special</strong>
                                -
                                مقدار
                                <code>1</code>
                                به معنای موجود ویژه محصول می‌باشد
                            </li>
                            <li>
                                <strong dir="ltr">sort</strong>
                                -
                                مقادیر به شرح زیر می‌باشد:
                                <ul>
                                    <li>
                                        1 --> جدیدترین
                                    </li>
                                    <li>
                                        12 --> پربازدیدترین
                                    </li>
                                    <li>
                                        16 --> پرتخفیف ترین
                                    </li>
                                    <li>
                                        4 --> ارزان ترین
                                    </li>
                                    <li>
                                        7 --> گران ترین
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="row my-3">
                        <div class="col-xl-9 col-lg-12 col-md-12">
                            <div class="form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control ltr px-3 rounded-full"
                                       id="searchLinkPlaceholder" disabled
                                       style="border: 1px dashed !important;"
                                       value="<?= $base . url('home.search')->getRelativeUrlTrimmed(); ?>">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    <button type="button" class="btn btn-dark btn-icon rounded-full copy-to-clipboard"
                                            data-clipboard-target="#searchLinkPlaceholder"
                                            data-popup="tooltip" data-original-title="کپی" data-placement="right">
                                        <i class="icon-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-feed-item border-dark-alpha">
                </div>
            </div>
        </div>
    </div>
</div>
