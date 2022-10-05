<?php

use App\Logic\Utils\Jdf;

?>

<?php if (count($comments ?? [])): ?>
    <ul class="list_none comment_list mt-4">
        <?php foreach ($comments as $comment): ?>
            <li>
                <div>
                    <div class="comment_img">
                        <img src="" data-src="<?= asset_path('image/avatars/' . $comment['user_image'], false); ?>"
                             alt="<?= $comment['first_name']; ?>" class="lazy">
                    </div>
                    <div class="comment_block">
                        <p class="customer_meta">
                            <span class="review_author"><?= $comment['first_name']; ?></span>
                            <span class="comment-date">
                            <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $comment['sent_at']) ?>
                            </span>
                        </p>
                        <div class="description">
                            <p>
                                <?= $comment['body']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php if (!empty(trim($comment['reply']))): ?>
                    <div>
                        <ul class="children">
                            <li class="comment_info">
                                <div class="d-flex">
                                    <div class="comment_user">
                                        <img src="" data-src="<?= asset_path('image/avatars/avatars2.png', false); ?>"
                                             alt="ادمین" class="lazy">
                                    </div>
                                    <div class="comment_content">
                                        <div class="d-flex align-items-md-center">
                                            <div class="meta_data">
                                                <h6>ادمین</h6>
                                                <div class="comment-time">
                                                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $comment['reply_at']) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <?= $comment['reply']; ?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="row mt-4">
        <div class="col-12">
            <!-- START SECTION PAGINATION -->
            <?php load_partial('main/section-pagination', ['pagination' => $pagination ?? []]); ?>
            <!-- END SECTION PAGINATION -->
        </div>
    </div>
<?php endif; ?>