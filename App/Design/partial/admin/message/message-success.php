<?php if (isset($success) && !empty($success)): ?>
<div class="alert bg-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <p class="m-0">
        <?= $success; ?>
    </p>
</div>
<?php endif; ?>