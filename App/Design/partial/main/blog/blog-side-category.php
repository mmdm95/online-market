<?php if (count($blog_side_categories ?? [])): ?>
    <div class="widget">
        <h5 class="widget_title">دسته‌ها</h5>
        <ul class="widget_archive">
            <?php foreach ($blog_side_categories as $category): ?>
                <li>
                    <a href="<?= url('home.blog', null, ['category' => $category['id']]); ?>">
                        <span class="archive_year"><?= $category['name']; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>