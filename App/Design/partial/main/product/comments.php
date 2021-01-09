<?php

use App\Logic\Utils\Jdf;

?>

<?php if (count($comments ?? [])): ?>
    <ul class="list_none comment_list mt-4">
        <?php foreach ($comments as $comment): ?>
            <li>
                <div class="comment_img">
                    <img src="<?= url('image.show') . $comment['user_image']; ?>"
                         alt="<?= $comment['first_name']; ?>"/>
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