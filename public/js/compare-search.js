(function ($) {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.elements = $.extend({}, window.MyGlobalVariables.elements, {
        compareSearch: {
            form: '#__frm_compare_products',
            inputs: {
                q: 'compare-q-inp',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            compareSearch: {
                q: {},
            },
        },
    });
    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        compare: {
            filter: '/ajax/compare/product/search',
        }
    });

    $(function () {
        var
            variables,
            core,
            shop,
            cart,
            constraints,
            //
            data = new FormData(),
            loaderId,
            isInProgress = false,
            //
            container = $('#__compare_products_container'),
            dialogBoxViewer = $('.__compare_dialog_box_viewer'),
            removeFilterBtn = $('#filter_remover_btn'),
            //
            idx = 1,
            page = 1,
            q;

        variables = window.MyGlobalVariables;
        shop = new window.TheShop();

        //-----
        constraints = {
            compareSearch: {
                q: variables.validation.constraints.compareSearch.q,
            },
        };
        //-----

        removeFilterBtn.on('click' + variables.namespace, function () {
            q = '';
            page = 1;
            loadProduct();
            $('input[name="' + variables.elements.compareSearch.inputs.q + '"]').val('');
            $(this).addClass('d-none');
        });

        dialogBoxViewer.on('click' + variables.namespace, function () {
            idx = $(this).index();
            loadProduct();
        });

        // change page event
        function paginationClick() {
            $('a[data-page-no]')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function (e) {
                    e.preventDefault();
                    //-----
                    var pg = parseInt($(this).attr('data-page-no'), 10);
                    if (!isNaN(pg) && 0 !== pg && pg != page) {
                        page = pg;
                        loadProduct();
                    }
                });
        }

        //---------------------------------------------------------------
        // SUBMIT COMPARE SEARCH FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('compareSearch', constraints.compareSearch, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = shop.showLoader();
            }
            //
            q = values.get(variables.elements.compareSearch.inputs.q);
            loadProduct();
            //
            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        // load product functionality
        function loadProduct() {
            if (!isInProgress) {
                isInProgress = true;

                if ($.trim(q) != '') {
                    removeFilterBtn.removeClass('d-none');
                }

                shop.request(variables.url.compare.filter, 'get', function () {
                    // place received items in their place
                    container.html(this.data);
                    // back to normal
                    shop.hideLoader(loaderId ? loaderId : null);
                    isInProgress = false;
                    createLoader = true;
                    // reload function(s)
                    paginationClick();
                    // Lazy loader (pictures, videos, etc.)
                    shop.lazyFn(container);
                }, {
                    params: {
                        q,
                        idx,
                        page,
                    },
                }, true, function () {
                    shop.hideLoader(loaderId);
                    isInProgress = false;
                    createLoader = true;
                });
            }
        }
    });
})(jQuery);