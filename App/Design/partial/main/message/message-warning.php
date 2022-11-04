<?php if (isset($warning) && !empty($warning)): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <span class="m-0 d-block">
        <?= $warning; ?>
    </span>
</div>
<?php endif; ?>