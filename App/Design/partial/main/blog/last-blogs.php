<?php

use App\Logic\Utils\Jdf;

?>

<?php if (count($last_blog ?? [])): ?>
    <div class="widget">
        <h5 class="widget_title">پست های اخیر</h5>
        <ul class="widget_recent_post">
            <?php foreach ($last_blog as $item): ?>
                <li>
                    <div class="post_footer">
                        <div class="post_img">
                            <a href="<?= url('home.blog.show', [
                                'id' => $item['id'],
                                'slug' => $item['slug'],
                            ]); ?>">
                                <img src="" data-src="<?= url('image.show') . $item['image']; ?>"
                                     alt="<?= $item['title']; ?>" class="lazy">
                            </a>
                        </div>
                        <div class="post_content">
                            <h6>
                                <a href="<?= url('home.blog.show', [
                                    'id' => $item['id'],
                                    'slug' => $item['slug'],
                                ]); ?>">
                                    <?= $item['title']; ?>
                                </a>
                            </h6>
                            <p class="small m-0">
                                <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $item['created_at']) ?>
                            </p>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>