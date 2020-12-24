<?php if (isset($warning) && !empty($warning)): ?>
<div class="alert  bg-warning text-white alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <p class="m-0">
        <?= $warning; ?>
    </p>
</div>
<?php endif; ?>