<?php foreach ($switch as $item): ?>
    <?php if($item['status'] == $status): ?>
        <span class="badge <?= $item['badge']; ?>"><?= $item['text']; ?></span>
        <?php break; ?>
    <?php endif; ?>
<?php endforeach; ?>