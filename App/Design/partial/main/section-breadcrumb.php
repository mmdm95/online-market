<?php if (!empty($sub_title)): ?>
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="custom-container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1><?= $sub_title ?? ''; ?></h1>
                    </div>
                </div>
                <?php if (count($breadcrumb ?? [])): ?>
                    <div class="col-md-6">
                        <ol class="breadcrumb justify-content-md-end">
                            <?php foreach ($breadcrumb as $item): ?>
                                <?php if ((bool)($item['is_active'] ?? false)): ?>
                                    <li class="breadcrumb-item active">
                                        <?= $item['text'] ?? ''; ?>
                                    </li>
                                <?php else: ?>
                                    <li class="breadcrumb-item">
                                        <a href="<?= $item['url'] ?? '#'; ?>">
                                            <?= $item['text'] ?? ''; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                <?php endif; ?>
            </div>
        </div><!-- END CONTAINER-->
    </div>
<?php endif; ?>