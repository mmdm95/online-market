<?php if (count($steps ?? [])): ?>
    <div class="container pt-5 px-5">
        <div class="step-container">
            <?php
            $len = count($steps ?? []);
            $i = 0;
            ?>
            <?php foreach ($steps as $k => $step): ?>
                <div class="step-item <?= $step['is_done'] ?? false ? 'done' : ($step['is_active'] ?? false ? 'active' : ''); ?>"
                     title="<?= $step['text']; ?>">
                    <?php if (isset($step['icon'])): ?>
                        <i class="<?= $step['icon']; ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                </div>
                <?php if (++$i !== $len): ?>
                    <div class="step-separator <?= $step['is_done'] ?? false ? 'done' : ($step['is_active'] ?? false ? 'active' : ''); ?>"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>