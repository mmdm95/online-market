<?php

use App\Logic\Utils\Jdf;

?>

<!-- Content area -->
<div class="content">

    <div class="row">
        <div class="card col-xl-9">
            <?php load_partial('admin/card-header', ['header_title' => $contact['title']]); ?>

            <div class="card-body">
                <ul class="list-inline list-inline-dotted text-muted mb-3">
                    <li class="list-inline-item">
                        <span class="text-muted">ارسال شده توسط:</span>
                        <?php if (!is_null($contact['creator_id'])): ?>
                            <a href="<?= url('admin.user.view', ['id' => $contact['creator_id']])->getRelativeUrl(); ?>">
                                <?= ($contact['creator_name'] ?? '') . ' ' . ($contact['creator_family'] ?? ''); ?>
                            </a>
                        <?php else: ?>
                            <span class="text-info-600"><?= $contact['name']; ?></span>
                        <?php endif; ?>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">در تاریخ:</span>
                        <span class="text-success"><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $contact['created_at']); ?></span>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">موبایل:</span>
                        <span class="text-success"><?= $contact['mobile'] ?: "<i class='icon-minus2' aria-hidden='true'></i>"; ?></span>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">ایمیل:</span>
                        <span class="text-success"><?= $contact['email'] ?: "<i class='icon-minus2' aria-hidden='true'></i>"; ?></span>
                    </li>
                </ul>
                <div class="p-3 bg-light">
                    <p>
                        <?= $contact['body']; ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /content area -->