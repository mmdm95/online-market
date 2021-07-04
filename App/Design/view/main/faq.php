<!-- START MAIN CONTENT -->
<div class="main_content">
    <?php if (count($faq ?? [])): ?>
        <!-- STAT SECTION FAQ -->
        <div class="section">
            <div class="custom-container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="heading_s1 mb-3 mb-md-5">
                            <h3>سوالات عمومی</h3>
                        </div>
                        <div id="accordion" class="accordion accordion_style1">
                            <?php foreach ($faq as $k => $value): ?>
                                <div class="card">
                                    <div class="card-header" id="heading<?= ($k + 1); ?>">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapse<?= ($k + 1); ?>"
                                               aria-expanded="<?= 0 === $k ? 'true' : 'false'; ?>"
                                               aria-controls="collapse<?= ($k + 1); ?>">
                                                <?= $value['question']; ?>
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse<?= ($k + 1); ?>" class="collapse <?= 0 === $k ? 'show' : ''; ?>"
                                         aria-labelledby="heading<?= ($k + 1); ?>" data-parent="#accordion">
                                        <div class="card-body">
                                            <p>
                                                <?= $value['answer']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION FAQ -->
    <?php else: ?>
        <div class="product text-center mt-3 py-2">
            <p class="m-0">
                موردی یافت نشد.
            </p>
        </div>
    <?php endif; ?>

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->