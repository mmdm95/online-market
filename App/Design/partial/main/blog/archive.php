<?php if (count($archives ?? [])): ?>
    <div class="widget">
        <h5 class="widget_title">بایگانی</h5>
        <ul class="widget_archive">
            <?php foreach ($archives as $archive): ?>
                <li>
                    <a href="<?= url('home.blog', null, [
                        'archive' => $archive['archive_tag'],
                    ]); ?>">
                        <span class="archive_year"><?= $archive['archive_tag']; ?></span>
                        <span class="archive_num">(<?= local_number($archive['count']); ?>)</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>