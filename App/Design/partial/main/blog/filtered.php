<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<?php if (count($blog ?? [])): ?>
    <div class="row">
        <?php foreach ($blog as $item): ?>
            <div class="col-xl-4 col-md-6">
                <div class="blog_post blog_style2 box_shadow1">
                    <div class="blog_img">
                        <a href="<?= url('home.blog.show', [
                            'id' => $item['id'],
                            'slug' => $item['slug'],
                        ]); ?>">
                            <img src="<?= url('image.show') . $item['image']; ?>" alt="<?= $item['title']; ?>">
                        </a>
                    </div>
                    <div class="blog_content bg-white">
                        <div class="blog_text">
                            <h6 class="blog_title">
                                <a href="<?= url('home.blog.show', [
                                    'id' => $item['id'],
                                    'slug' => $item['slug'],
                                ]); ?>">
                                    <?= $item['title']; ?>
                                </a>
                            </h6>
                            <ul class="list_none blog_meta">
                                <li>
                                    <a href="<?= url('home.blog', null, [
                                        'time' => $item['created_at'],
                                    ]); ?>">
                                        <i class="ti-calendar"></i>
                                        <?= Jdf::jdate('j F Y', $item['created_at']) ?>
                                    </a>
                                </li>
                            </ul>
                            <p>
                                <?= StringUtil::truncate_word($item['abstract'], 150); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- START SECTION PAGINATION -->
    <?php load_partial('main/section-pagination', ['pagination' => $pagination ?? []]); ?>
    <!-- END SECTION PAGINATION -->

<?php else: ?>
    <div class="row">
        <div class="col-12">
            <?php load_partial('main/not-found-rows'); ?>
        </div>
    </div>
<?php endif; ?>