<?php

use Sim\Utils\StringUtil;

?>

<?php
$hasItems = false;
foreach ($tabbed_slider['items'] ?? [] as $tab) {
    if (count($tab['items'] ?? [])) {
        $hasItems = true;
        break;
    }
}
?>
<?php if ($hasItems): ?>
    <div class="section small_pt pb-0">
        <div class="custom-container container">
            <?php
            $col_1 = 'col-xl-3';
            $col_2 = 'col-xl-9';
            $hasSideImage = true;

            if (!isset($side_image) || !isset($side_image['image']) || empty($side_image['image'])) {
                $col_1 = '';
                $col_2 = 'col-xl-12';
                $hasSideImage = false;
            }
            ?>

            <div class="row">
                <?php if ($hasSideImage): ?>
                    <div class="<?= $col_1; ?> d-none d-xl-block">
                        <div class="sale-banner">
                            <a class="hover_effect1" href="<?= $side_image['link']; ?>">
                                <img src="" data-src="<?= url('image.show', ['filename' => $side_image['image']]); ?>"
                                     alt="side_image" class="lazy">
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="<?= $col_2; ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading_tab_header">
                                <div class="heading_s2">
                                    <h4><?= $tabbed_slider['title']; ?></h4>
                                </div>
                                <div class="tab-style2">
                                    <?php
                                    $randomNum = StringUtil::randomString(9, StringUtil::RS_NUMBER);
                                    ?>
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                            data-target="#tabmenubar<?= $randomNum; ?>" aria-expanded="false">
                                        <span class="ion-android-menu"></span>
                                    </button>

                                    <ul class="nav nav-tabs justify-content-center justify-content-md-end __tabbed_slider_multi"
                                        id="tabmenubar<?= $randomNum; ?>" role="tablist">
                                        <?php $k = 0; ?>
                                        <?php foreach ($tabbed_slider['items'] as $tab): ?>
                                            <?php if (count($tab['items'])): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link  <?= 0 === $k ? 'active' : ''; ?>"
                                                       id="tab-<?= ($k + 1); ?>"
                                                       data-toggle="tab"
                                                       href="#tab<?= ($k + 1); ?>" role="tab"
                                                       aria-controls="tab<?= ($k + 1); ?>"
                                                       aria-selected=" <?= 0 === $k ? 'true' : 'false'; ?>"
                                                       aria-selected="true">
                                                        <?= $tab['info']['name']; ?>
                                                    </a>
                                                </li>
                                                <?php $k++; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="tab_slider">
                                <?php $k = 0; ?>
                                <?php foreach ($tabbed_slider['items'] as $tab): ?>
                                    <?php if (count($tab['items'])): ?>
                                        <div class="tab-pane fade  <?= 0 == $k ? 'show active' : ''; ?>"
                                             id="tab<?= ($k + 1); ?>" role="tabpanel"
                                             aria-labelledby="tab<?= ($k + 1); ?>">
                                            <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                                 data-loop="true" data-margin="20"
                                                 data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                                <?php foreach ($tab['items'] as $item): ?>
                                                    <?php load_partial('main/product-card', ['item' => $item]); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php $k++; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>