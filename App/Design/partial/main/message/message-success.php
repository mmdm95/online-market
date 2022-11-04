<?php if (isset($success) && !empty($success)): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <span class="m-0 d-block">
        <?= $success; ?>
    </span>
</div>
<?php endif; ?>