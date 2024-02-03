<?php if (isset($message) && !empty($message)): ?>
    <?php
    $isDismissible = $dismissible ?? true;
    ?>
    <div
            class="alert <?= isset($theme) && $theme === 'dark' ? 'alert-dark' : 'alert-light'; ?> <?= $class ?? ''; ?> <?= $isDismissible ? 'alert-dismissible' : ''; ?> fade show"
            role="alert"
    >
        <?php if ($isDismissible): ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        <?php endif; ?>
        <span class="m-0 d-block">
        <?= $message; ?>
    </span>
    </div>
<?php endif; ?>