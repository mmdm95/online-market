<?php

use App\Logic\Utils\Jdf;

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">

    <div class="row">
        <div class="card col-xl-10">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">
                </h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a href="javascript:void(0);"
                           class="rounded btn-icon btn-danger py-1 __send_data_through_request"
                           data-internal-request-url="<?= url('ajax.comment.condition', ['p_id' => $product_id, 'id' => $comment['id']])->getRelativeUrlTrimmed(); ?>"
                           data-internal-request-status="<?= COMMENT_CONDITION_REJECT ?>">
                            عدم تایید نظر
                            <i class="icon-cross2 ml-2" aria-hidden="true"></i>
                        </a>
                        <a href="javascript:void(0);"
                           class="rounded btn-icon btn-success py-1 __send_data_through_request"
                           data-internal-request-url="<?= url('ajax.comment.condition', ['p_id' => $product_id, 'id' => $comment['id']])->getRelativeUrlTrimmed(); ?>"
                           data-internal-request-status="<?= COMMENT_CONDITION_ACCEPT ?>">
                            تایید نظر
                            <i class="icon-checkmark3 ml-2" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <ul class="list-inline list-inline-dotted text-muted mb-3">
                    <li class="list-inline-item">
                        <span class="text-muted">ارسال شده توسط:</span>
                        <a href="<?= url('admin.user.view', ['id' => $comment['user_id']])->getRelativeUrl(); ?>">
                            <?= trim(($comment['first_name'] ?? '') . ' ' . ($comment['last_name'] ?? '')) ?: $comment['username']; ?>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">در تاریخ:</span>
                        <span class="text-success"><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $comment['sent_at']); ?></span>
                    </li>
                </ul>
                <div class="p-3 bg-light">
                    <p class="mb-0">
                        <?= $comment['body']; ?>
                    </p>
                </div>
            </div>

            <form action="<?= url('admin.comment.detail', ['p_id' => $product_id, 'id' => $comment['id']])->getRelativeUrlTrimmed(); ?>"
                  method="post">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $comment_answer_errors ?? [],
                    'success' => $comment_answer_success ?? '',
                    'warning' => $comment_answer_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <span class="text-danger">*</span>
                            پاسخ به نظر
                        </label>
                        <textarea
                                name="inp-ans-comment-desc"
                                cols="30"
                                rows="10"
                                placeholder="پاسخ به نظر"
                                class="form-control cntEditor"
                        ><?= $validator->setInput('inp-ans-comment-desc', $comment['reply'] ?? ''); ?></textarea>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-success">
                            ارسال پاسخ
                            <i class="icon-reply ml-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /content area -->