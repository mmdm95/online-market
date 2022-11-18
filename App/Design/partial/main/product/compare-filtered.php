<?php if (count($products ?? [])): ?>
    <div class="col-12">
        <div class="row">
            <?php
            $limit = config()->get('settings.product_each_page.value');
            $current = $pagination['current_page'];
            $total = $pagination['total'];
            $firstCount = ($current - 1) * $limit;
            $lastCount = $current * $limit;
            ?>
            <div class="col-12 my-2">
                نمایش
                <?= $firstCount + 1; ?>
                تا
                <?= $total < $lastCount ? $total : $lastCount; ?>
                از مجموع
                <?= $total ?>
                رکورد
            </div>
        </div>
        <div class="row">
            <?php foreach ($products as $item): ?>
                <?php
                if (isset($linkParams['p' . $idx])) {
                    $linkParams['p' . $idx] = $item['product_id'];
                }
                ?>

                <div class="col-md-4 col-6">
                    <div class="item">
                        <div class="product_wrap">
                            <?php if (isset($item['festival_discount'])): ?>
                                <span class="pr_flash bg-danger">جشنواره</span>
                            <?php elseif (DB_YES == $item['is_special']): ?>
                                <span class="pr_flash">ویژه</span>
                            <?php elseif ($item['created_at'] >= (time() - TIME_THREE_DAYS_IN_SECONDS)): ?>
                                <span class="pr_flash bg-success">جدید</span>
                            <?php endif; ?>

                            <div class="product_img product_img_no_before">
                                <a href="<?= url('home.compare', $linkParams)->getRelativeUrlTrimmed(); ?>">
                                    <img src="<?= (config()->get('settings.default_image_placeholder.value') != '') ? url('image.show', ['filename' => config()->get('settings.default_image_placeholder.value')]) : ''; ?>"
                                         data-src="<?= url('image.show') . $item['image']; ?>"
                                         alt="<?= $item['title']; ?>" class="lazy">
                                </a>
                            </div>
                            <div class="product_info">
                                <h6 class="product_title">
                                    <a class="__compare_item_selection"
                                       href="<?= url('home.compare', $linkParams)->getRelativeUrlTrimmed(); ?>">
                                        <?= $item['title']; ?>
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-12">
        <!-- START SECTION PAGINATION -->
        <?php load_partial('main/section-pagination', ['pagination' => $pagination ?? []]); ?>
        <!-- END SECTION PAGINATION -->
    </div>

<?php else: ?>
    <div class="col-12">
        <?php load_partial('main/not-found-rows'); ?>
    </div>
<?php endif; ?>