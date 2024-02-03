<?php if (isset($warning) && !empty($warning)): ?>
    <?php
    $isDismissible = $dismissible ?? true;
    ?>
    <div class="alert alert-warning <?= $class ?? ''; ?> <?= $isDismissible ? 'alert-dismissible' : ''; ?> fade show"
         role="alert">
        <?php if ($isDismissible): ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        <?php endif; ?>
        <span class="m-0 d-block">
        <?= $warning; ?>
    </span>
    </div>
<?php endif; ?>