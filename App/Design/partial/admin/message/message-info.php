<?php if (isset($info) && !empty($info)): ?>
    <?php
    $isDismissible = $dismissible ?? true;
    ?>
    <div class="alert bg-info text-white <?= $isDismissible ? 'alert-dismissible' : ''; ?> fade show" role="alert">
        <?php if ($isDismissible): ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        <?php endif; ?>
        <p class="m-0">
            <?= $info; ?>
        </p>
    </div>
<?php endif; ?>