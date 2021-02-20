<div class="bg-slate-300 px-3 py-2 mb-3 rounded shadow-1 <?= isset($element) ? 'd-flex align-items-center justify-content-between' : ''; ?>">
    <div class="col text-center">
        <h5 class="d-flex align-items-center m-0 justify-content-center">
            <?= $header_title ?? ''; ?>
        </h5>
    </div>
    <?= $element ?? ''; ?>
</div>