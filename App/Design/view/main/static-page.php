<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION PAGE -->
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_conditions">
                        <?= $page_content ?? ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION PAGE -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->