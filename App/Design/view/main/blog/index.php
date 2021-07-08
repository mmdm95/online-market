<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION BLOG -->
    <div class="section">
        <div class="custom-container container">
            <div class="row">
                <div class="col-lg-9" id="__main_blog_container">
                </div>

                <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                    <div class="sidebar">

                        <!-- START SECTION SEARCH -->
                        <?php load_partial('main/blog/section-search'); ?>
                        <!-- END SECTION SEARCH -->

                        <!-- START SECTION LAST BLOG -->
                        <?php load_partial('main/blog/last-blogs', [
                            'last_blog' => $last_blog ?? [],
                        ]); ?>
                        <!-- END SECTION LAST BLOG -->

                        <!-- START SECTION ARCHIVE -->
                        <?php load_partial('main/blog/archive', [
                                'archives' => $archives ?? [],
                        ]); ?>
                        <!-- END SECTION ARCHIVE -->

                        <!-- START SECTION TAGS -->
                        <?php load_partial('main/blog/tags', [
                            'tags' => $tags ?? [],
                        ]); ?>
                        <!-- END SECTION LAST TAGS -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION BLOG -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->