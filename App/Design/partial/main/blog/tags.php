<?php if (count($tags ?? [])): ?>
    <div class="widget">
        <h5 class="widget_title">برچسب ها</h5>
        <div class="tags">
            <?php foreach ($tags as $tag): ?>
                <a href="<?= url('home.blog', null, [
                    'tag' => $tag,
                ]); ?>"><?= $tag; ?></a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>