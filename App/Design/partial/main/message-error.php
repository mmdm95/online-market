<?php if (isset($errors) && count($errors)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul class="list-unstyled">
            <?php foreach ($errors as $error): ?>
                <?php if (is_string($error)): ?>
                    <li><?= $error; ?></li>
                <?php elseif (is_array($error)): ?>
                    <?php foreach ($error as $e): ?>
                        <li><?= $e; ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>