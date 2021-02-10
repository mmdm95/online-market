<?php $found = false; ?>
<?php foreach ($switch as $item): ?>
    <?php if ($item['status'] == $status): ?>
        <?php $found = true; ?>
        <span class="badge <?= $item['badge'] ? $item['badge'] : ''; ?>" <?= $item['style'] ? $item['style'] : ''; ?>>
            <?= $item['text']; ?>
        </span>
        <?php break; ?>
    <?php endif; ?>
<?php endforeach; ?>
<?php if(isset($default) && !$found): ?>
    <span class="badge <?= $default['badge'] ? $default['badge'] : ''; ?>" <?= $default['style'] ? $default['style'] : ''; ?>>
            <?= $default['text']; ?>
        </span>
<?php endif; ?>
