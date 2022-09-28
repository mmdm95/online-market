<div class="col-12">
    <div class="mb-3 mt-2">
        <h5 class="mb-0 font-weight-semibold">
            نمودار پرفروش ترین محصولات
        </h5>
        <span class="text-muted d-block">
            <i class="icon-calendar2" aria-hidden="true"></i>
            <?= \App\Logic\Utils\Jdf::jdate(CHART_BOUGHT_STATUS_TIME_FORMAT, strtotime('today, -1 month', time())); ?>
            ←
            <?= \App\Logic\Utils\Jdf::jdate(CHART_BOUGHT_STATUS_TIME_FORMAT, strtotime('today, -1 second', time())); ?>
        </span>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="chart-container">
            <?php load_partial('admin/loader-static'); ?>
            <div class="chart has-fixed-height" id="__chart-of-top-bought-products"></div>
        </div>
    </div>
</div>