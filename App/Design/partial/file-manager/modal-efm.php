<div id="modal_efm" class="modal fade lazyContainer" tabindex="-1">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h5 class="modal-title">انتخاب فایل</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <?php load_view('file-manager/mini-efm', [
                    'the_options' => $the_options ?? [],
                ]); ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">بستن</button>
                <button type="submit" class="btn bg-slate" data-dismiss="modal" id="__pick_file_btn">
                    انتخاب فایل
                </button>
            </div>
        </div>
    </div>
</div>