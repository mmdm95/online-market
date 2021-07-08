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
                <div class="col-md-4 col-6">
                    <?php load_partial('main/product-card', ['item' => $item, 'new_tab' => true]); ?>
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