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

    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        address: {
            get: '/ajax/address/get',
            add: '/ajax/address/add',
            edit: '/ajax/address/edit',
            remove: '/ajax/address/remove',
        },
        unit: {
            get: '/ajax/unit/get',
            add: '/ajax/unit/add',
            edit: '/ajax/unit/edit',
            remove: '/ajax/unit/remove',
        },
    });
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
        addAddress: {
            form: '#__form_add_address',
            inputs: {
                name: 'inp-add-address-full-name',
                mobile: 'inp-add-address-mobile',
                province: 'inp-add-address-province',
                city: 'inp-add-address-city',
                postalCode: 'inp-add-address-postal-code',
                address: 'inp-add-address-addr',
            },
        },
        editAddress: {
            form: '#__form_edit_address',
            inputs: {
                name: 'inp-edit-address-full-name',
                mobile: 'inp-edit-address-mobile',
                province: 'inp-edit-address-province',
                city: 'inp-edit-address-city',
                postalCode: 'inp-edit-address-postal-code',
                address: 'inp-edit-address-addr',
            },
        },
        addUnit: {
            form: '#__form_add_unit',
            inputs: {
                title: 'inp-add-unit-title',
                sign: 'inp-add-unit-sign',
            },
        },
        editUnit: {
            form: '#__form_edit_unit',
            inputs: {
                title: 'inp-edit-unit-title',
                sign: 'inp-edit-unit-sign',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend({}, window.MyGlobalVariables.validation, {
        constraints: {
            addAddress: {
                province: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کد پستی را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^\d{1,10}$/,
                        message: '^' + 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            editAddress: {
                province: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کد پستی را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^\d{1,10}$/,
                        message: '^' + 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            addUnit: {
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد عنوان واحد را خالی نگذارید.',
                    },
                    length: {
                        maximum: 250,
                        message: '^' + 'عنوان حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                sign: {
                    length: {
                        maximum: 250,
                        message: '^' + 'علامت حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
            }
        },
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
            showLoader: function () {
                var id = core.idGenerator('loader');
                $('body').append(
                    $('<div class="position-fixed w-100 h-100" style="z-index: 1001; left: 0; top: 0; right: 0; bottom: 0;" id="' + id + '" />')
                        .append($('<div class="position-absolute bg-dark-alpha" style="left: 0; top: 0; right: 0; bottom: 0; z-index: 1;" />'))
                        .append($('<div class="theme_corners text-center" style="z-index: 2" />').append($('<div class="pace_activity" />')))
                );
                return id;
            },
            hideLoader: function (id) {
                if (id) {
                    id = $('#' + id);
                    if (id.length) {
                        id.fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                }
            },
        });

        return Admin;
    })(window.TheShopBase, core);

    $(function () {
        var
            admin,
            constraints,
            //-----
            loaderId,
            createLoader = true,
            //-----
            userIdInp,
            userId = null,
            editAddrId = null,
            editUnitId = null;

        admin = new window.TheAdmin();

        //-----
        constraints = {
            addAddress: {
                name: variables.validation.common.name,
                mobile: variables.validation.common.mobile,
                province: variables.validation.constraints.addAddress.province,
                city: variables.validation.constraints.addAddress.city,
                postalCode: variables.validation.constraints.addAddress.postalCode,
                address: variables.validation.constraints.addAddress.address,
            },
            editAddress: {
                name: variables.validation.common.name,
                mobile: variables.validation.common.mobile,
                province: variables.validation.constraints.editAddress.province,
                city: variables.validation.constraints.editAddress.city,
                postalCode: variables.validation.constraints.editAddress.postalCode,
                address: variables.validation.constraints.editAddress.address,
            },
            addUnit: {
                title: variables.validation.constraints.addUnit.title,
                sign: variables.validation.constraints.addUnit.sign,
            },
        };

        userIdInp = $('input[type="hidden"][data-user-id]');
        if (userIdInp.length) {
            userId = userIdInp.val();
        }

        /**
         * Delete anything from a specific url
         *
         * @param btn
         * @param [table]
         */
        function deleteOperation(btn, table) {
            var url, id;
            url = $(btn).attr('data-remove-url');
            id = $(btn).attr('data-remove-id');

            if (url && id) {
                admin.deleteItem(url, null, {}, true);
            }
        }

        /**
         *
         * @param btn
         * @param [table]
         */
        function editAddressBtnClick(btn, table) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_address_edit');
            // clear element after each call
            $(variables.elements.editAddress.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.address.get + '/' + userId + '/' + id, 'get', function () {
                    var _ = this;
                    var provincesSelect = $('select[name="' + variables.elements.editAddress.inputs.province + '"]'),
                        citiesSelect = $(provincesSelect.attr('data-city-select-target'));
                    if (_.data.length && provincesSelect.length && citiesSelect.length) {
                        editAddrId = id;
                        //-----
                        admin.loadProvinces(provincesSelect.attr('data-current-province', _.data['province_id']));
                        admin.loadCities(citiesSelect.attr('data-current-city', _.data['city_id']));
                        editModal.find('[name="' + variables.elements.editAddress.inputs.province + '"]').val(_.data['full_name']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.mobile + '"]').val(_.data['mobile']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.postalCode + '"]').val(_.data['postal_code']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.address + '"]').val(_.data['address']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editUnitBtnClick(btn, table) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_edit_unit');
            // clear element after each call
            $(variables.elements.editAddress.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.unit.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (_.data.length) {
                        editUnitId = id;
                        //-----
                        editModal.find('[name="' + variables.elements.editUnit.inputs.title + '"]').val(_.data['title']);
                        editModal.find('[name="' + variables.elements.editUnit.inputs.sign + '"]').val(_.data['sign']);
                    }
                });
            }
        }

        /**
         * Actions to take after a datatable initialize(reinitialize)
         */
        function datatableInitCompleteActions(table) {
            // Initialize select inputs in datatable
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                dropdownAutoWidth: true,
                width: 'auto'
            });

            // reinitialize dropdown
            $('[data-toggle="dropdown"]').dropdown();

            // reinitialize lazy plugin for images
            $('.lazy').lazy({
                effect: "fadeIn",
                effectTime: 800,
                threshold: 0,
                // callback
                afterLoad: function (element) {
                    $(element).css({'background': 'none'});
                }
            });

            // delete button click event
            $('.__item_remover_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    deleteOperation($(this), table);
                });

            // edit address button click event
            $('.__item_address_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editAddressBtnClick($(this), table);
                });

            // edit unit button click event
            $('.__item_unit_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editUnitBtnClick($(this), table);
                });
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
                            datatableInitCompleteActions($this);
                        },
                    });
                } else {
                    table = $this.DataTable({
                        stateSave: true,
                    });
                }

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
            });
        }

        // this must be after datatable
        if ($().select2) {
            // Basic example
            $('.form-control-select2').each(function () {
                var obj, parent;
                obj = {
                    minimumResultsForSearch: Infinity,
                };
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });

            // With search
            $('.form-control-select2-searchable').each(function () {
                var obj, parent;
                obj = {};
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });

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
            $('.form-control-select2-icons').each(function () {
                var obj, parent;
                obj = {
                    templateResult: iconFormat,
                    dropdownParent: $(this).closest('.modal'),
                    minimumResultsForSearch: Infinity,
                    templateSelection: iconFormat,
                    escapeMarkup: function (m) {
                        return m;
                    }
                };
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });

            // Initialize
            $('.dataTables_length select').each(function () {
                var obj, parent;
                obj = {
                    minimumResultsForSearch: Infinity,
                    dropdownAutoWidth: true,
                    width: 'auto'
                };
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });
        }

        //---------------------------------------------------------------
        // ADD ADDRESS FORM
        //---------------------------------------------------------------
        var addrDatatable = $('#__datatable_addr_view');
        admin.forms.submitForm('addAddress', constraints.addAddress, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.address.add + '/' + userId, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addAddress.form).reset();
                // reinitialize address datatable
                if (addrDatatable.length && $.fn.DataTable.isDataTable(addrDatatable)) {
                    addrDatatable.DataTable().ajax.reload();
                }
                admin.toasts.toast(this.data, {
                    type: 'success',
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit ADDRESS FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editAddress', constraints.editAddress, function (values) {
            if (editAddrId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.address.edit + '/' + userId + '/' + editAddrId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editAddress.form).reset();
                    // remove current id for province and city and reset current address id
                    $('select[name="' + variables.elements.editAddress.inputs.province + '"]').removeAttr('data-current-province');
                    $('select[name="' + variables.elements.editAddress.inputs.city + '"]').removeAttr('data-current-city');
                    editAddrId = null;
                    //-----
                    admin.toasts.toast(this.data, {
                        type: 'success',
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD UNIT FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addUnit', constraints.addUnit, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.unit.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addUnit.form).reset();
                admin.toasts.toast(this.data, {
                    type: 'success',
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit UNIT FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editUnit', constraints.editUnit, function (values) {
            if (editUnitId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.unit.edit + '/' + editUnitId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editUnit.form).reset();
                    editUnitId = null;
                    //-----
                    admin.toasts.toast(this.data, {
                        type: 'success',
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });
    });
})(jQuery);
