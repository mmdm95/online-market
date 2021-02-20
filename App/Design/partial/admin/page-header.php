<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4>
                <i class="icon-arrow-left52 mr-2"></i>
                <span class="font-weight-semibold"><?= $sub_title ?? ''; ?></span>
            </h4>
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <?php foreach ($breadcrumb as $item): ?>
                    <?php if ((bool)($item['is_active'] ?? false)): ?>
                        <span class="breadcrumb-item active"><?= $item['text'] ?? ''; ?></span>
                    <?php else: ?>
                        <a href="<?= $item['url'] ?? '#'; ?>"
                           class="breadcrumb-item">
                            <?php if (isset($item['icon']) && !is_null($item['icon'])): ?>
                                <i class="<?= $item['icon']; ?> mr-2"></i>
                            <?php endif; ?>
                            <?= $item['text'] ?? ''; ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>