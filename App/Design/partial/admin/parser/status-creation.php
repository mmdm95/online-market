<?php $found = false; ?>
<?php if (isset($switch) && count($switch)): ?>
    <?php foreach ($switch as $k => $item): ?>
        <?php if (($item[$k]['status'] ?? $item['status'] ?? '') == $status): ?>
            <?php $found = true; ?>
            <span class="badge <?= $item[$k]['badge'] ?? $item['badge'] ?? ''; ?>"
                  style="<?= $item[$k]['style'] ?? $item['style'] ?? ''; ?>">
            <?= $item[$k]['text'] ?? $item['text']; ?>
        </span>
            <?php break; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (isset($default) && !$found): ?>
    <span class="badge <?= $default['badge'] ?? ''; ?>" style="<?= $default['style'] ?? ''; ?>">
            <?= $default['text']; ?>
        </span>
<?php endif; ?>
