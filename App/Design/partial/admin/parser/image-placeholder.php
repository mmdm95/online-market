<?php if (isset($lightbox) && (bool)$lightbox): ?>
    <a data-fancybox data-src="<?= url('image.show')->getRelativeUrl() . $img; ?>"
        class="cursor-pointer">
        <img src=""
             data-src="<?= url('image.show')->getRelativeUrl() . $img; ?>"
             alt="<?= $alt; ?>"
             class="img-preview img-rounded lazy">
    </a>
<?php else: ?>
    <img class="img-preview img-rounded lazy"
         src=""
         data-src="<?= url('image.show')->getRelativeUrl() . $img; ?>"
         alt="<?= $alt; ?>">
<?php endif; ?>