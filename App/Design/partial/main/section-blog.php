<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>
<?php if (count($blog ?? [])): ?>
    <div class="section pb_20">
        <div class="custom-container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="heading_s1 text-center">
                        <h2>آخرین خبرها</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php foreach ($blog as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="blog_post blog_style2 box_shadow1">
                            <div class="blog_img">
                                <a href="<?= url('home.blog.show', [
                                    'id' => $item['id'],
                                    'slug' => $item['slug'],
                                ]); ?>">
                                    <img src="" data-src="<?= url('image.show') . $item['image']; ?>"
                                         alt="<?= $item['title']; ?>" class="lazy">
                                </a>
                            </div>
                            <div class="blog_content bg-white">
                                <div class="blog_text">
                                    <h5 class="blog_title">
                                        <a href="<?= url('home.blog.show', [
                                            'id' => $item['id'],
                                            'slug' => $item['slug'],
                                        ]); ?>">
                                            <?= $item['title']; ?>
                                        </a>
                                    </h5>
                                    <ul class="list_none blog_meta">
                                        <li>
                                            <a href="<?= url('home.blog', null, [
                                                'time' => $item['created_at'],
                                            ]); ?>">
                                                <i class="ti-calendar"></i>
                                                <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $item['created_at']) ?>
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
        </div>
    </div>
<?php endif; ?>