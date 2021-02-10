<div class="card-header header-elements-inline">
    <h5 class="card-title"><?= $header_title ?? ''; ?></h5>
    <div class="header-elements">
        <div class="list-icons">
            <?php if ($collapse ?? true): ?>
                <a class="list-icons-item" data-action="collapse"></a>
            <?php endif; ?>
        </div>
    </div>
</div>