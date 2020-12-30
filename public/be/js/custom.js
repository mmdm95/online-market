/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

(function ($) {
    window.MyGlobalVariables.icons = {
        success: 'icon-checkmark3',
        info: 'icon-info3',
        warning: 'icon-warning',
        error: 'icon-cross2',
    };
    window.MyGlobalVariables.toasts.toast.theme = 'limitless';
    window.MyGlobalVariables.toasts.toast.layout = 'topLeft';
    window.MyGlobalVariables.toasts.confirm.theme = 'limitless';
    window.MyGlobalVariables.toasts.confirm.type = 'confirm';
    window.MyGlobalVariables.toasts.confirm.btnClasses.yes = 'btn bg-blue text-white ml-1';
    window.MyGlobalVariables.toasts.confirm.btnClasses.no = 'btn btn-link';

    $.extend(true, window.MyGlobalVariables.url, {
        url: {},
    });

    var core, variables;

    core = window.TheCore;
    variables = window.MyGlobalVariables;

    /**
     * Make Admin class global to window
     * @type {TheAdmin}
     */
    window.TheAdmin = (function (_super, c) {
        c.extend(Admin, _super);

        function Admin() {
            _super.call(this);
        }

        $.extend(Admin.prototype, {
            // extra functionality
        });

        return Admin;
    })(window.TheShopBase, core);

    $(function () {
        var
            admin;

        admin = new window.TheAdmin();

        function deleteOperation(btn) {
            var url, id;
            url = $(btn).attr('data-remove-url');
            id = $(btn).attr('data-remove-id');

            if (url && id) {
                admin.deleteItem(url, null, {}, true);
            }
        }

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

        if ($().spectrum) {
            var cps = $(".colorpicker-show-input");
            cps.each(function () {
                var $this = $(this);

                $this.spectrum({
                    color: $this.attr('data-color') ? $this.attr('data-color') : "#2196f3",
                    cancelText: "لغو",
                    chooseText: "انتخاب",
                    preferredFormat: "hex",
                    showInput: true
                })
            });
        }

        // Image lightbox
        if ($().fancybox) {
            $('[data-popup="lightbox"]').fancybox({
                padding: 3
            });
        }


        if (typeof Switchery !== 'undefined') {
            // Initialize multiple switches
            var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html);
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
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                pageLength: 10,
                language: {
                    processing: "در حال بارگذاری...",
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
                        '<span class="text-primary ml-1 mr-1">0</span>' + 'از' + 'مجموع' + '<span class="text-primary ml-1 mr-1">0</span>' + 'رکورد',
                    infoFiltered: '(' + 'فیلتر شده از مجموع' + ' _MAX_ ' + 'رکورد' + ')',
                }
            });

            /**
             * Pipelining function for DataTables. To be used to the `ajax` option of DataTables
             *
             * @see https://datatables.net/examples/server_side/pipeline.html
             * @param [opts]
             * @returns {Function}
             */
            $.fn.dataTable.pipeline = function (opts) {
                // Configuration options
                var conf = $.extend({
                    pages: 5,     // number of pages to cache
                    url: '',      // script url
                    data: null,   // function or object with parameters to send to the server
                                  // matching how `ajax.data` works in DataTables
                    method: 'GET' // Ajax HTTP method
                }, opts);

                // Private variables for storing the cache
                var
                    cacheLower = -1,
                    cacheUpper = null,
                    cacheLastRequest = null,
                    cacheLastJson = null;

                return function (request, drawCallback, settings) {
                    var
                        ajax = false,
                        requestStart = request.start,
                        drawStart = request.start,
                        requestLength = request.length,
                        requestEnd = requestStart + requestLength;

                    if (settings.clearCache) {
                        // API requested that the cache be cleared
                        ajax = true;
                        settings.clearCache = false;
                    } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                        // outside cached data - need to make a request
                        ajax = true;
                    } else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                        JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                        JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
                    ) {
                        // properties changed (ordering, columns, searching)
                        ajax = true;
                    }

                    // Store the request for checking next time around
                    cacheLastRequest = $.extend(true, {}, request);

                    if (ajax) {
                        // Need data from the server
                        if (requestStart < cacheLower) {
                            requestStart = requestStart - (requestLength * (conf.pages - 1));

                            if (requestStart < 0) {
                                requestStart = 0;
                            }
                        }

                        cacheLower = requestStart;
                        cacheUpper = requestStart + (requestLength * conf.pages);

                        request.start = requestStart;
                        request.length = requestLength * conf.pages;

                        // Provide the same `data` options as DataTables.
                        if (typeof conf.data === 'function') {
                            // As a function it is executed with the data object as an arg
                            // for manipulation. If an object is returned, it is used as the
                            // data object to submit
                            var d = conf.data(request);
                            if (d) {
                                $.extend(request, d);
                            }
                        } else if ($.isPlainObject(conf.data)) {
                            // As an object, the data given extends the default
                            $.extend(request, conf.data);
                        }

                        return $.ajax({
                            "type": conf.method,
                            "url": conf.url,
                            "data": request,
                            "dataType": "json",
                            "cache": false,
                            "success": function (json) {
                                cacheLastJson = $.extend(true, {}, json);

                                if (json.data) {
                                    if (cacheLower != drawStart) {
                                        json.data.splice(0, drawStart - cacheLower);
                                    }
                                    if (requestLength >= -1) {
                                        json.data.splice(requestLength, json.data.length);
                                    }
                                } else {
                                    json.data = [];
                                    json.recordsFiltered = 0;
                                    json.recordsTotal = 0;
                                }

                                drawCallback(json);
                            }
                        });
                    } else {
                        var json = $.extend(true, {}, cacheLastJson);
                        json.draw = request.draw; // Update the echo for each response
                        if (json.data) {
                            json.data.splice(0, requestStart - cacheLower);
                            json.data.splice(requestLength, json.data.length);
                        } else {
                            json.data = [];
                            json.recordsFiltered = 0;
                            json.recordsTotal = 0;
                        }

                        drawCallback(json);
                    }
                }
            };

            // Register an API method that will empty the pipelined data, forcing an Ajax
            // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
            $.fn.dataTable.Api.register('clearPipeline()', function () {
                return this.iterator('table', function (settings) {
                    settings.clearCache = true;
                });
            });

            // Highlighting rows and columns on mouseover
            var lastIdx = null;
            $.each($('.datatable-highlight'), function () {
                var $this, table, url;
                $this = $(this);
                url = $this.attr('data-ajax-url');
                if (url) {
                    table = $this.DataTable({
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        ajax: $.fn.dataTable.pipeline({
                            url: url,
                            method: 'POST',
                            pages: 5, // number of pages to cache
                        }),
                        deferRender: true,
                        initComplete: function () {
                            // Initialize select inputs in datatable
                            $('.dataTables_length select').select2({
                                minimumResultsForSearch: Infinity,
                                dropdownAutoWidth: true,
                                width: 'auto'
                            });

                            $('[data-toggle="dropdown"]').dropdown();

                            // delete button click event
                            $('.__item_remover_btn')
                                .off('click' + variables.namespace)
                                .on('click' + variables.namespace, function () {
                                    deleteOperation($(this));
                                });
                        },
                    });

                    $('.datatable-highlight tbody').off('mouseover').on('mouseover', 'td', function () {
                        if (table.cell(this).index()) {
                            var colIdx = table.cell(this).index().column;

                            if (colIdx !== lastIdx) {
                                $(table.cells().nodes()).removeClass('active');
                                $(table.column(colIdx).nodes()).addClass('active');
                            }
                        }
                    }).off('mouseleave').on('mouseleave', function () {
                        $(table.cells().nodes()).removeClass('active');
                    });
                } else {
                    table = $this.DataTable({
                        stateSave: true,
                    });
                }
            });
        }

        // this must be after datatable
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

            // Initialize
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                dropdownAutoWidth: true,
                width: 'auto'
            });
        }
    });
})(jQuery);
