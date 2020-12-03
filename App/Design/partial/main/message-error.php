<?php if (isset($errors) && count($errors)): ?>
    <div class="alert alert-danger">
        <ul class="list-unstyled">
            <?php foreach ($errors as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>