<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#changeInfo">
            تغییر اطلاعات کاربری
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#changePassword">
            تغییر کلمه عبور
        </a>
    </li>

    <?php if (count($security_questions ?? [])): ?>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#changeOther">
                تنظیمات
            </a>
        </li>
    <?php endif; ?>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane container active p-0" id="changeInfo">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-header">
                    <h3>جزئیات حساب</h3>
                </div>
                <div class="card-body">
                    <form action="<?= url('user.info')->getRelativeUrlTrimmed(); ?>#changeInfo"
                          method="post" id="__form_change_info">
                        <?php load_partial('main/message/message-form', [
                            'errors' => $info_change_errors ?? [],
                            'success' => $info_change_success ?? '',
                            'warning' => $info_change_warning ?? '',
                        ]); ?>

                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"
                               data-ignored>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>
                                    نام کاربری:
                                </label>
                                <input class="form-control" type="text" disabled
                                       value="<?= $user['username']; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    نام:
                                </label>
                                <input required="required"
                                       class="form-control"
                                       placeholder="حروف فارسی"
                                       name="inp-info-first-name"
                                       type="text"
                                       value="<?= $user['first_name']; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    نام خانوادگی
                                    <small class="text-muted">(اختیاری)</small>
                                    :
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="حروف فارسی"
                                       name="inp-info-last-name"
                                       value="<?= $user['last_name']; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    شماره شناسنامه:
                                </label>
                                <input class="form-control ltr" type="text"
                                       placeholder="از نوع عددی"
                                       name="inp-info-national-num"
                                       value="<?= $user['national_number']; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    شماره شبا
                                    <small class="text-muted">(اختیاری)</small>
                                    :
                                </label>
                                <div class="input-group">
                                    <input class="form-control ltr" type="text"
                                           placeholder="از نوع عددی"
                                           name="inp-info-shaba-num"
                                           value="<?= $user['shaba_number']; ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">IR</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>
                                    آدرس ایمیل
                                    <small class="text-muted">(اختیاری)</small>
                                    :
                                </label>
                                <input class="form-control ltr"
                                       type="text"
                                       placeholder="مثال: example@gmail.com"
                                       name="inp-info-email"
                                       value="<?= $user['email']; ?>">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-fill-out"
                                        name="infoSubmit" value="Submit">
                                    ذخیره اطلاعات
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane container fade p-0" id="changePassword">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-header">
                    <h3>تغییر کلمه عبور</h3>
                </div>
                <div class="card-body">
                    <?php load_partial('main/message/message-info', [
                        'info' => 'پس از تغییر کلمه عبور، عملیات ورود باید دوباره انجام شود',
                        'dismissible' => false,
                    ]); ?>

                    <form action="<?= url('user.info')->getRelativeUrlTrimmed(); ?>#changePassword"
                          method="post" id="__form_change_password">
                        <?php load_partial('main/message/message-form', [
                            'errors' => $password_change_errors ?? [],
                            'success' => $password_change_success ?? '',
                            'warning' => $password_change_warning ?? '',
                        ]); ?>

                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"
                               data-ignored>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>
                                    <span class="required">*</span>
                                    کلمه عبور فعلی
                                </label>
                                <input required="" class="form-control"
                                       name="inp-pass-prev-password"
                                       type="password">
                            </div>
                            <div class="form-group col-md-12">
                                <label>
                                    <span class="required">*</span>
                                    کلمه عبور جدید
                                </label>
                                <input required="" class="form-control"
                                       name="inp-pass-password"
                                       type="password">
                            </div>
                            <div class="form-group col-md-12">
                                <label>
                                    <span class="required">*</span>
                                    تأیید کلمه عبور
                                </label>
                                <input required="" class="form-control"
                                       name="inp-pass-re-password"
                                       type="password">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-fill-out"
                                        name="passwordSubmit" value="Submit">
                                    تغییر کلمه عبور
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($security_questions ?? [])): ?>
        <div class="tab-pane container fade p-0" id="changeOther">
            <div class="dashboard_content">
                <div class="card">
                    <div class="card-header">
                        <h3>نوع بازیابی کلمه عبور</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= url('user.info')->getRelativeUrlTrimmed(); ?>#changeOther"
                              method="post" id="__form_change_recover_type">
                            <?php load_partial('main/message/message-form', [
                                'errors' => $other_change_errors ?? [],
                                'success' => $other_change_success ?? '',
                                'warning' => $other_change_warning ?? '',
                            ]); ?>

                            <?php
                            $isSecQuestionType = RECOVER_PASS_TYPE_SECURITY_QUESTION == $user['recover_password_type'];
                            ?>

                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"
                                   data-ignored>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <div class="custome-radio">
                                        <input class="form-check-input" type="radio"
                                               name="inp-recover-type"
                                               value="<?= RECOVER_PASS_TYPE_SMS ?>"
                                            <?= RECOVER_PASS_TYPE_SMS == $user['recover_password_type'] ? 'checked="checked"' : ''; ?>
                                               id="smsChk">
                                        <label class="form-check-label" for="smsChk">
                                            <span>بازیابی با پیامک</span>
                                        </label>
                                    </div>
                                </div>

                                <?php if (count($security_questions ?? [])): ?>
                                    <div class="col-lg-6 form-group">
                                        <div class="custome-radio">
                                            <input class="form-check-input" type="radio"
                                                   name="inp-recover-type"
                                                   value="<?= RECOVER_PASS_TYPE_SECURITY_QUESTION ?>"
                                                <?= $isSecQuestionType ? 'checked="checked"' : ''; ?>
                                                   id="questionChk">
                                            <label class="form-check-label" for="questionChk">
                                                <span>بازیابی با سؤال امنیتی</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 collapse" id="secQuestionCollapse">
                                        <div class="row mt-4">
                                            <div class="col-lg-5 form-group">
                                                <label>
                                                    <span class="text-danger" aria-hidden="true">*</span>
                                                    انتخاب سؤال امنیتی:
                                                </label>
                                                <select name="inp-recover-sec-question"
                                                        class="selectric_dropdown">
                                                    <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                            selected="selected"
                                                            disabled="disabled">
                                                        انتخاب کنید
                                                    </option>
                                                    <?php foreach ($security_questions as $question): ?>
                                                        <option value="<?= $question['id']; ?>">
                                                            <?= $question['question']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-8 form-group">
                                                <label>
                                                    <span class="required">*</span>
                                                    پاسخ به سؤال:
                                                </label>
                                                <input required="" class="form-control"
                                                       name="inp-recover-sec-question-answer"
                                                       type="text">
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-fill-out mt-3"
                                            name="otherSubmit" value="Submit">
                                        اعمال تغییرات
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function ($) {
                'use strict';

                $(function () {
                    var secCollapseContainer = $('#secQuestionCollapse');
                    $('[name="inp-recover-type"]').on('change', function () {
                        if ($(this).val() == '<?= RECOVER_PASS_TYPE_SECURITY_QUESTION; ?>') {
                            secCollapseContainer.collapse('show');
                        } else {
                            secCollapseContainer.collapse('hide');
                        }
                    });
                });
            })(jQuery);
        </script>
    <?php endif; ?>
</div>