<?php
$slider = isset($slider) && count($slider) ? $slider : [];
$col_1 = 'col-xl-3';
$col_2 = 'col-xl-9';
$hasSideImage = true;

if (!isset($info['image']) || empty($info['image'])) {
    $col_1 = '';
    $col_2 = 'col-xl-12';
    $hasSideImage = false;
}
?>

<?php if (count($slider ?? [])): ?>
    <div class="section small_pt small_pb">
        <div class="custom-container container">

            <div class="row">
                <?php if ($hasSideImage): ?>
                    <div class="<?= $col_1; ?> d-none d-xl-block">
                        <div class="sale-banner">
                            <a class="hover_effect1" href="<?= $info['image_link']; ?>">
                                <img src="<?= url('image.show', ['filename' => $info['image']])->getRelativeUrlTrimmed(); ?>"
                                     alt="<?= $info['image']; ?>">
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="<?= $col_2; ?>">
                    <?php if (!empty($info['title'] ?? []) || !empty($info['link'] ?? [])): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <?php if (!empty($info['title'] ?? [])): ?>
                                        <div class="heading_s2">
                                            <h4><?= $info['title']; ?></h4>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($info['link'] ?? [])): ?>
                                        <div class="view_all">
                                            <a href="<?= $info['link']; ?>" class="text_default">
                                                <i class="linearicons-power"></i>
                                                <span>مشاهده همه</span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                 data-loop="true" data-margin="20"
                                 data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                <?php foreach ($slider as $item): ?>
                                    <?php load_partial('main/product-card', ['item' => $item]); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>