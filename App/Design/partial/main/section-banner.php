<?php if (count($three_images ?? [])): ?>
    <div class="section pb_20 small_pt">
        <div class="container-fluid px-2">
            <div class="row no-gutters">
                <?php
                $colSize = 4;
                if (count($three_images) === 2) {
                    $colSize = 6;
                } elseif (count($three_images) === 4) {
                    $colSize = 3;
                }
                ?>
                <?php foreach ($three_images as $k => $image): ?>
                    <div class="col-md-<?= $colSize; ?>">
                        <div class="sale_banner">
                            <a class="hover_effect1" href="<?= $image['link'] ?>">
                                <img src="" data-src="<?= url('image.show') . $image['image']; ?>"
                                     alt="بنر شماره <?= $k; ?>" class="lazy">
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>