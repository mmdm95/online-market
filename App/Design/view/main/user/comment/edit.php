<?php

use Sim\Utils\StringUtil;

$validator = form_validator();

?>

<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>ویرایش نظر</h3>
        </div>
        <div class="card-body">
            <div class="d-block d-lg-flex m-0 align-items-start">
                <div>
                    <a href="<?= url('home.product.show', ['id' => $comment['product_id'], 'slug' => $product['slug']])->getRelativeUrl(); ?>">
                        <img src="<?= url('image.show', ['filename' => $product['image']]); ?>"
                             alt="<?= $product['title'] ?>"
                             class="mx-0 mx-lg-3"
                             width="100px" height="auto">
                    </a>
                </div>
                <div class="col">
                    <div>
                        <a href="<?= url('home.product.show', ['id' => $comment['product_id'], 'slug' => $product['slug']])->getRelativeUrl(); ?>">
                            <h6>
                                <?= $product['title'] ?>
                            </h6>
                        </a>
                    </div>

                    <div class="my-1">
                        <?php if (DB_YES == $product['is_available'] && count($price)): ?>
                            <label class="my-0">
                                <?= number_format(StringUtil::toEnglish($price['min_price'])); ?>
                                -
                                <?= number_format(StringUtil::toEnglish($price['max_price'])); ?>
                            </label>
                            <small>تومان</small>
                        <?php else: ?>
                            <span class="badge badge-danger p-1">ناموجود</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="<?= url('user.comment.edit', ['id' => $comment_id])->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_edit_comment">
                <label>
                    <span class="required">*</span>
                    نظر شما:
                </label>
                <textarea
                        name="inp-comment-message"
                        class="form-control form-control-min-height"
                        cols="30"
                        rows="10"
                        placeholder="وارد کنید..."
                ><?= $validator->setInput('inp-comment-message') ?: $comment['body']; ?></textarea>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="linearicons-check" aria-hidden="true"></i>
                        ویرایش نظر
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>