<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION BLOG -->
    <div class="section">
        <div class="custom-container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="single_post">
                        <h2 class="blog_title"><?= $blog['title']; ?></h2>
                        <ul class="list_none blog_meta">
                            <li>
                                <a href="<?= url('home.blog', null, [
                                    'time' => $blog['created_at'],
                                ]); ?>">
                                    <i class="ti-calendar"></i>
                                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $blog['created_at']); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?= url('home.blog', null, [
                                    'category' => $blog['category_id'],
                                ]); ?>">
                                    <i class="ti-layout-grid2"></i>
                                    <?= $blog['category_name']; ?>
                                </a>
                            </li>
                        </ul>
                        <div class="blog_img">
                            <img src="" data-src="<?= url('image.show') . $blog['image']; ?>"
                                 alt="<?= $blog['title']; ?>" class="lazy">
                        </div>
                        <div class="blog_content">
                            <div class="blog_text">
                                <?= $blog['body']; ?>
                                <div class="blog_post_footer">
                                    <?php if (count($keywords ?? [])): ?>
                                        <div class="row justify-content-between align-items-center">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <div class="tags">
                                                    <?php foreach ($keywords as $keyword): ?>
                                                        <a href="<?= url('home.blog', null, [
                                                            'tag' => $keyword,
                                                        ]); ?>">
                                                            <?= $keyword; ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="post_navigation bg_gray mb-5">
                        <div class="row align-items-center justify-content-between p-4">
                            <?php if (count($prev_blog ?? [])): ?>
                                <div class="col-5">
                                    <a href="<?= url('home.blog.show', [
                                        'id' => $prev_blog['id'],
                                        'slug' => $prev_blog['slug'],
                                    ]); ?>">
                                        <div class="post_nav post_nav_prev">
                                            <i class="ti-arrow-left"></i>
                                            <span><?= $prev_blog['title']; ?></span>
                                        </div>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!count($prev_blog ?? []) || !count($next_blog ?? [])): ?>
                                <div class="col-2">
                                    <a class="post_nav_home">
                                        <i class="ti-layout-grid2"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (count($next_blog ?? [])): ?>
                                <div class="col-5">
                                    <a href="<?= url('home.blog.show', [
                                        'id' => $next_blog['id'],
                                        'slug' => $next_blog['slug'],
                                    ]); ?>">
                                        <div class="post_nav post_nav_next">
                                            <i class="ti-arrow-right"></i>
                                            <span><?= $next_blog['title']; ?></span>
                                        </div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (count($related_blog ?? [])): ?>
                        <div class="related_post">
                            <div class="content_title">
                                <h5>پست های مرتبط</h5>
                            </div>
                            <div class="row">
                                <?php foreach ($related_blog as $item): ?>
                                    <div class="col-md-6">
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
                    <?php endif; ?>
                </div>
                <div class="col-xl-3 mt-4 pt-2 mt-xl-0 pt-xl-0">
                    <div class="sidebar">

                        <!-- START SECTION SEARCH -->
                        <?php load_partial('main/blog/section-search'); ?>
                        <!-- END SECTION SEARCH -->

                        <!-- START SECTION LAST BLOG -->
                        <?php load_partial('main/blog/last-blogs', [
                            'last_blog' => $last_blog ?? [],
                        ]); ?>
                        <!-- END SECTION LAST BLOG -->

                        <!-- START SECTION ARCHIVE -->
                        <?php load_partial('main/blog/archive', [
                            'archives' => $archives ?? [],
                        ]); ?>
                        <!-- END SECTION ARCHIVE -->

                        <!-- START SECTION TAGS -->
                        <?php load_partial('main/blog/tags', [
                            'tags' => $tags ?? [],
                        ]); ?>
                        <!-- END SECTION LAST TAGS -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION BLOG -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->