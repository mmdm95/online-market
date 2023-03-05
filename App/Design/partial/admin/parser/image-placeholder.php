<?php
if (!isset($is_raw_image) || !$is_raw_image) {
    $img = url('image.show', ['filename' => $img])->getRelativeUrl();
}
?>

<?php if ($lightbox ?? false): ?>
    <a data-fancybox data-src="<?= $img; ?>"
       class="cursor-pointer">
        <img src=""
             data-src="<?= $img; ?>"
             alt="<?= $alt; ?>"
             class="img-preview img-rounded lazy">
    </a>
<?php else: ?>
    <img class="img-preview img-rounded lazy"
         src=""
         data-src="<?= $img; ?>"
         alt="<?= $alt; ?>">
<?php endif; ?>