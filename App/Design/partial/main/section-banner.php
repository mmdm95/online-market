<?php if (count($three_images ?? [])): ?>
    <div class="section pb_20 small_pt">
        <div class="custom-container container">
            <div class="row">
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
                        <div class="sale-banner mb-3 mb-md-4">
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