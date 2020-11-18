/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

window.MyGlobalVariables.icons = {
    success: '',
    info: '',
    warning: '',
    error: '',
};

(function ($) {
    $.extend(window.MyGlobalVariables.url, {
        url: {},
    });

    /**
     * Make Admin class global to window
     * @type {Admin}
     */
    window.TheAdmin = (function (_super) {
        window.TheCore.extend(Admin, _super);

        function Admin() {
            _super.call(this);
        }

        $.extend(Admin.prototype, {
            // extra functionality
        });

        return Admin;
    })(window.TheShopBase);

    $(function () {
        // Add bottom spacing if reached bottom,
        // to avoid footer overlapping
        // -------------------------

        $(window).on('scroll', function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 40) {
                $('.fab-menu-bottom-left, .fab-menu-bottom-right').addClass('reached-bottom');
            } else {
                $('.fab-menu-bottom-left, .fab-menu-bottom-right').removeClass('reached-bottom');
            }
        });

        // Image lightbox
        if ($().fancybox) {
            $('[data-popup="lightbox"]').fancybox({
                padding: 3
            });
        }

        // uniform initialize
        if ($().uniform) {
            // Default initialization
            $('.form-check-input-styled').uniform();

            $('.form-input-styled').uniform({
                fileButtonClass: 'action btn bg-pink-400',
                fileButtonHtml: 'انتخاب فایل',
                filesButtonHtml: 'انتخاب فایل',
                fileDefaultHtml: 'هیچ فایلی انتخاب نشده است',
                resetDefaultHtml: 'بازنشانی',
            });
        }

        if ($().select2) {
            // Basic example
            $('.form-control-select2').select2();

            //
            // Select with icons
            //

            // Format icon
            function iconFormat(icon) {
                var originalOption = icon.element;
                if (!icon.id) {
                    return icon.text;
                }
                var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

                return $icon;
            }

            // Initialize with options
            $('.form-control-select2-icons').select2({
                templateResult: iconFormat,
                minimumResultsForSearch: Infinity,
                templateSelection: iconFormat,
                escapeMarkup: function (m) {
                    return m;
                }
            });
        }

        // Display color formats
        if ($().spectrum) {
            $('.colorpicker-show-input').spectrum({
                showInput: true
            });
        }

        if ($().DataTable) {
            // Setting datatable defaults
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 100,
                    targets: [5]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>جست‌و‌جو:</span> _INPUT_',
                    searchPlaceholder: 'کلمه مورد نظر را تایپ کنید ...',
                    lengthMenu: '<span>نمایش:</span> _MENU_',
                    paginate: {
                        'first': 'صفحه اول',
                        'last': 'صفحه آخر',
                        'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                        'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                    },
                    emptyTable: 'موردی یافت نشد.',
                    zeroRecords: 'مورد جستجو شده وجود ندارد.',
                    info: 'نمایش' + '<span class="text-primary ml-1 mr-1">_START_</span>' + 'تا' +
                        '<span class="text-primary ml-1 mr-1">_END_</span>' + 'از' + 'مجموع' + '<span class="text-primary ml-1 mr-1">_TOTAL_</span>' + 'رکورد',
                    infoEmpty: 'نمایش' + '<span class="text-primary ml-1 mr-1">0</span>' + 'تا' +
                        '<span class="text-primary ml-1 mr-1">0</span>' + 'از' + 'مجموع' + '<span class="text-primary ml-1 mr-1">0</span>' + 'رکورد'
                }
            });

            // Highlighting rows and columns on mouseover
            var lastIdx = null;
            var table = $('.datatable-highlight').DataTable();

            $('.datatable-highlight tbody').on('mouseover', 'td', function () {
                var colIdx = table.cell(this).index().column;

                if (colIdx !== lastIdx) {
                    $(table.cells().nodes()).removeClass('active');
                    $(table.column(colIdx).nodes()).addClass('active');
                }
            }).on('mouseleave', function () {
                $(table.cells().nodes()).removeClass('active');
            });
        }
    });
})(jQuery);
