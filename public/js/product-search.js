(function ($) {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.elements = $.extend({}, window.MyGlobalVariables.elements, {
        product: {
            form: '#__product_search',
            inputs: {
                search: 'inp-product-search-side',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            product: {
                search: {},
            },
        },
    });

    $(function () {
        var
            variables,
            core,
            //-----
            shop,
            constraints,
            uriParser,
            //-----
            mainProductContainer,
            sidebarMain,
            category,
            sortSelect,
            priceFilter,
            priceHandleMin,
            priceHandleMax,
            priceFilterBtn,
            brandChk,
            modelChk,
            sizeChk,
            colorChk,
            availabilitySwitch,
            offerSwitch,
            //-----
            canPushState = true,
            createLoader = false,
            isInProgress = false;

        variables = window.MyGlobalVariables;
        core = window.TheCore;
        shop = new window.TheShop();
        uriParser = new UriParser();

        //-----
        constraints = {
            product: {
                search: variables.validation.constraints.product.search,
            },
        };
        //-----

        mainProductContainer = $('#__main_product_container');
        sidebarMain = $('#__the_sticky_sidebar');
        category = mainProductContainer.attr('data-category');
        sortSelect = $('#__product_sort');
        priceFilterBtn = $('#__search_product_price_filter');
        priceFilter = $('#price_filter');
        priceHandleMin = $('#price_first');
        priceHandleMax = $('#price_second');
        brandChk = $('.product_brand_switch');
        modelChk = $('.product_model_switch_multi');
        sizeChk = $('.product_size_switch_multi');
        colorChk = $('.product_color_switch_multi');
        availabilitySwitch = $('#available_product');
        offerSwitch = $('#offer_product');

        // make side menu sticky
        function stickySidebar() {
            sidebarMain.theiaStickySidebar({
                containerSelector: '#__sticky_sidebar_container',
                additionalMarginTop: 90,
                additionalMarginBottom: 20,
            });
        }

        // change page event
        function paginationClick() {
            $('a[data-page-no]')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function (e) {
                    e.preventDefault();
                    //-----
                    var page = parseInt($(this).attr('data-page-no'), 10);
                    if (!isNaN(page) && 0 !== page) {
                        uriParser.push('page', page, true);
                        loadProduct();
                    }
                });
        }

        function setCategoryToSearchParams() {
            if (-1 != category) {
                uriParser.push('category', category, true);
            }
        }

        // search string changed
        function querySubmitting() {
            shop.forms.submitForm('product', constraints.product, function (values) {
                uriParser.push('q', values[variables.elements.product.inputs.search], true);
                loadProduct();
                return false;
            }, function (errors) {
                shop.forms.showFormErrors(errors);
                return false;
            });
        }

        function sortSelectChange() {
            sortSelect.on('change' + variables.namespace, function () {
                var val, checked;
                checked = $(this).find(':checked');
                val = checked.val();
                if (checked && checked.length && val) {
                    uriParser.push('sort', val ? val : 0, true);
                    loadProduct();
                }
            });
        }

        function priceFilterChange() {
            var a, b, c, flt_price;
            flt_price = $("#flt_price");
            a = priceFilter.data("min-value");
            b = priceFilter.data("max-value");
            c = priceFilter.data("price-sign");
            priceFilter.slider({
                range: true,
                isRTL: true,
                min: priceFilter.data("min"),
                max: priceFilter.data("max"),
                values: [a, b],
                slide: function (event, ui) {
                    var min, max;
                    flt_price.html(ui.values[1] + c + " - " + ui.values[0] + c);
                    priceHandleMin.val(ui.values[0]);
                    priceHandleMax.val(ui.values[1]);
                    min = parseInt(ui.values[0], 10);
                    max = parseInt(ui.values[1], 10);
                    if ((min || 0 === min) && (max || 0 === max) && !isNaN(min) && !isNaN(max)) {
                        uriParser.push('price.min', min, true);
                        uriParser.push('price.max', max, true);
                    }
                    var attr = priceFilterBtn.attr('disabled');
                    if (typeof attr !== typeof undefined && attr !== false) {
                        priceFilterBtn.removeAttr('disabled');
                    }
                },
            });
            flt_price.html(priceFilter.slider("values", 1) + c + " - " + priceFilter.slider("values", 0) + c);
            //-----
            priceFilterBtn
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    var attr = priceFilterBtn.attr('disabled');
                    if (typeof attr === typeof undefined || attr === false) {
                        loadProduct();
                    }
                });
        }

        function brandsCheckChange() {
            brandChk
                .off('change' + variables.namespace)
                .on('change' + variables.namespace, function () {
                    var checked = [];
                    $('.list_brand input[type="checkbox"]:checked').each(function () {
                        checked.push($(this).val());
                    });
                    if (checked.length) {
                        uriParser.push('brands', checked, true);
                    } else {
                        uriParser.clear('brands');
                    }
                    loadProduct();
                });
        }

        function sizeCheckChange() {
            sizeChk
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    var checked = [], val;
                    sizeChk.each(function () {
                        val = $(this).find('span.active').attr('data-value');
                        if (val) {
                            checked.push(val);
                        }
                    });
                    if (checked.length) {
                        uriParser.push('size', checked, true);
                    } else {
                        uriParser.clear('size');
                    }
                    loadProduct();
                });
        }

        function colorCheckChange() {
            colorChk
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    var checked = [], val;
                    colorChk.each(function () {
                        val = $(this).find('span.active').attr('data-color');
                        if (val) {
                            checked.push(val);
                        }
                    });
                    if (checked.length) {
                        uriParser.push('color', checked, true);
                    } else {
                        uriParser.clear('color');
                    }
                    loadProduct();
                });
        }

        function availabilitySwitchClick() {
            availabilitySwitch.mSwitch('options', {
                onTurnOn: function () {
                    uriParser.push('is_available', 1, true);
                    loadProduct();
                },
                onTurnOff: function () {
                    uriParser.clear('is_available');
                    loadProduct();
                }
            });
        }

        function offersSwitchClick() {
            offerSwitch.mSwitch('options', {
                onTurnOn: function () {
                    uriParser.push('is_special', 1, true);
                    loadProduct();
                },
                onTurnOff: function () {
                    uriParser.clear('is_special');
                    loadProduct();
                }
            });
        }

        function makeAdjustmentsAccordingToObj(obj) {
            var el;

            // search query
            if (obj['q'] && core.isString(obj['q'])) {
                $(variables.elements.product.form).val(obj['q']);
            } else {
                $(variables.elements.product.form).val('');
            }
            // price filter
            if (obj['price'] && obj['price']['min'] && obj['price']['max']) {
                priceFilter.slider('values', 0, obj['price']['min']);
                priceFilter.slider('values', 1, obj['price']['max']);
            }
            // brands checkboxes
            if (obj['brands'] && core.isArray(obj['brands'])) {
                $('.list_brand input[type="checkbox"]').each(function () {
                    if ($.inArray($(this).val(), obj['brands'])) {
                        $(this).attr('checked', 'checked').prop('checked', true);
                    } else {
                        $(this).removeAttr('checked', 'checked').prop('checked', false);
                    }
                });
            } else {
                $('.list_brand input[type="checkbox"]').each(function () {
                    $(this).removeAttr('checked', 'checked').prop('checked', false);
                });
            }
            // sizes activate
            if (obj['size'] && core.isArray(obj['size'])) {
                sizeChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        if ($.inArray(el.attr('data-value'), obj['size'])) {
                            el.addClass('active');
                        } else {
                            el.removeClass('active');
                        }
                    }
                });
            } else {
                sizeChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        el.removeClass('active');
                    }
                });
            }
            // colors activate
            if (obj['color'] && core.isArray(obj['color'])) {
                colorChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        if ($.inArray(el.attr('data-color'), obj['color'])) {
                            el.addClass('active');
                        } else {
                            el.removeClass('active');
                        }
                    }
                });
            } else {
                colorChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        el.removeClass('active');
                    }
                });
            }
            // availability switch
            if (obj['is_available']) {
                if (core.isChecked(obj['is_available'])) {
                    availabilitySwitch.mSwitch('turnOn', availabilitySwitch);
                } else {
                    availabilitySwitch.mSwitch('turnOff', availabilitySwitch);
                }
            } else {
                availabilitySwitch.mSwitch('turnOff', availabilitySwitch);
            }
            // offers switch
            if (obj['is_special']) {
                if (core.isChecked(obj['is_special'])) {
                    offerSwitch.mSwitch('turnOn', offerSwitch);
                } else {
                    offerSwitch.mSwitch('turnOff', offerSwitch);
                }
            } else {
                offerSwitch.mSwitch('turnOff', offerSwitch);
            }
        }

        // load product functionality
        function loadProduct() {
            makeAdjustmentsAccordingToObj(uriParser.get(null, {}, true));
            if (!isInProgress) {
                // push query to window state
                if (canPushState) {
                    uriParser.pushState({obj: uriParser.get(null, {}, true)});
                }
                //-----
                isInProgress = true;
                if (createLoader) {
                    createLoader = false;
                    var loaderId = shop.showLoader();
                }
                shop.request(variables.url.products.search, 'get', function () {
                    // place received items in their place
                    mainProductContainer.html(this.data);
                    // back to normal
                    shop.hideLoader(loaderId ? loaderId : null);
                    // reload function(s)
                    paginationClick();
                    canPushState = true;
                    isInProgress = false;
                    createLoader = true;
                }, {
                    params: uriParser.get(null, {}, true),
                }, true, function () {
                    shop.hideLoader(loaderId);
                    canPushState = true;
                    isInProgress = false;
                    createLoader = true;
                    stickySidebar();
                });
            }
        }

        stickySidebar();
        paginationClick();
        setCategoryToSearchParams();
        querySubmitting();
        sortSelectChange();
        priceFilterChange();
        brandsCheckChange();
        sizeCheckChange();
        colorCheckChange();
        availabilitySwitchClick();
        offersSwitchClick();
        loadProduct();
        window.onpopstate = function (event) {
            if (event.state) {
                uriParser.pushObjOnly(event.state.obj);
                uriParser.updateSearch();
                canPushState = false;
                loadProduct();
            }
        };
    });
})(jQuery);