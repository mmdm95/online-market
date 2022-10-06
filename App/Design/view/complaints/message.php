<?php

use App\Logic\Utils\Jdf;

?>

<!-- Content area -->
<div class="content">

    <div class="row">
        <div class="card col-xl-9">
            <?php load_partial('admin/card-header', ['header_title' => $complaint['title']]); ?>

            <div class="card-body">
                <ul class="list-inline list-inline-dotted text-muted mb-3">
                    <li class="list-inline-item">
                        <span class="text-muted">ارسال شده توسط:</span>
                        <span class="text-dark"><?= $complaint['name']; ?></span>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">در تاریخ:</span>
                        <span class="text-dark"><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $complaint['created_at']); ?></span>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">موبایل:</span>
                        <span class="text-dark"><?= $complaint['mobile'] ?: "<i class='icon-minus2' aria-hidden='true'></i>"; ?></span>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">ایمیل:</span>
                        <span class="text-pink"><?= $complaint['email'] ?: "<i class='icon-minus2' aria-hidden='true'></i>"; ?></span>
                    </li>
                </ul>
                <div class="p-3 bg-light">
                    <p class="mb-0">
                        <?= $complaint['body']; ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /content area -->