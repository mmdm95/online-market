<div class="col-12">
    <div class="mb-3 mt-2">
        <h5 class="mb-0 font-weight-semibold">
            نمودار تعداد سفارشات ثبت شده از تاریخ
            (<?= \App\Logic\Utils\Jdf::jdate('j F', strtotime('today, -4 weeks', time())); ?>)
            تا تاریخ
            (<?= \App\Logic\Utils\Jdf::jdate('j F', strtotime('tomorrow, -1 second', time())); ?>)
        </h5>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="chart-container">
            <?php load_partial('admin/loader-static'); ?>
            <div class="chart has-fixed-height" id="__chart-of_bought-status"></div>
        </div>
    </div>
</div>