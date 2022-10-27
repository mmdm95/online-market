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
            cart,
            constraints,
            uriParser,
            //-----
            removeFiltersBtn,
            //-----
            mainProductContainer,
            sidebarMain,
            category,
            sortSelect,
            priceFilter,
            priceHandleMin,
            priceHandleMax,
            priceFilterBtn,
            attrChk,
            brandChk,
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
        cart = new window.TheCart();
        uriParser = new UriParser();

        //-----
        constraints = {
            product: {
                search: variables.validation.constraints.product.search,
            },
        };
        //-----

        removeFiltersBtn = $('#__remove-all-filters');
        mainProductContainer = $('#__main_product_container');
        sidebarMain = $('#__the_sticky_sidebar');
        category = mainProductContainer.attr('data-category');
        sortSelect = $('#__product_sort');
        priceFilterBtn = $('#__search_product_price_filter');
        priceFilter = $('#price_filter');
        priceHandleMin = $('#price_first');
        priceHandleMax = $('#price_second');
        attrChk = $('[class*=product_attr_switch-]');
        brandChk = $('.product_brand_switch');
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
                    if (!isNaN(page) && 0 !== page && uriParser.get('page') != page) {
                        uriParser.push('page', page, true);
                        loadProduct();
                    }
                });
        }

        function setCategoryToSearchParams() {
            if (-1 != category) {
                uriParser.push('category', category, true);
            }

            var c = uriParser.get('category', -1);
            if (-1 != c) {
                category = c;
            }
        }

        // search string changed
        function querySubmitting() {
            shop.forms.submitForm('product', constraints.product, function (values) {
                uriParser.push('q', $.trim($("[name='" + variables.elements.product.inputs.search + "']").val()), true);
                loadProduct();
                return false;
            }, function (errors) {
                shop.forms.showFormErrors(errors);
                return false;
            });
        }

        function removeFiltersBtnClick() {
            removeFiltersBtn.on('click' + variables.namespace, function () {
                // reset all filters in UI too
                var el;
                $('.list_attrs input[type="checkbox"]').each(function () {
                    $(this).removeAttr('checked', 'checked').prop('checked', false);
                });
                $('.list_attrs input[type="radio"]').each(function () {
                    $(this).removeAttr('checked', 'checked').prop('checked', false);
                });
                $('.list_brand input[type="checkbox"]').each(function () {
                    $(this).removeAttr('checked', 'checked').prop('checked', false);
                });
                sizeChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        el.removeClass('active');
                    }
                });
                colorChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        el.removeClass('active');
                    }
                });
                availabilitySwitch.mSwitch('turnOff', availabilitySwitch);
                offerSwitch.mSwitch('turnOff', offerSwitch);
                //-----
                uriParser.clear();
                uriParser.push('category', category, true);
                loadProduct();
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

        function attrCheckChange() {
            attrChk
                .off('change' + variables.namespace)
                .on('change' + variables.namespace, function () {
                    var
                        checked = [],
                        attrId = $('.list_attrs input[type="checkbox"]').first().attr('data-attr-id');
                    attrId = parseInt(attrId, 10);
                    attrId = !isNaN(attrId) ? attrId : -1;
                    $('.list_attrs input[type="checkbox"]:checked').each(function () {
                        checked.push($(this).val());
                        attrId = $(this).attr('data-attr-id');
                        attrId = parseInt(attrId, 10);
                        attrId = !isNaN(attrId) ? attrId : -1;
                    });
                    if (checked.length) {
                        uriParser.push('attrs_' + attrId, checked, true);
                    } else {
                        uriParser.clear('attrs_' + attrId);
                    }
                    //
                    attrId = $('.list_attrs input[type="radio"]').first().attr('data-attr-id');
                    attrId = parseInt(attrId, 10);
                    attrId = !isNaN(attrId) ? attrId : -1;
                    var v;
                    $('.list_attrs input[type="radio"]:checked').each(function () {
                        v = $(this).val();
                        attrId = $(this).attr('data-attr-id');
                        attrId = parseInt(attrId, 10);
                        attrId = !isNaN(attrId) ? attrId : -1;

                        if (v) {
                            uriParser.push('attrs_' + attrId, v, true);
                        } else {
                            uriParser.clear('attrs_' + attrId);
                        }
                    });
                    //
                    loadProduct();
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
            sizeChk.off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    var checked = [], val;
                    sizeChk.each(function () {
                        val = $(this).find('span.active').attr('data-value');
                        if (val && -1 === $.inArray(val, checked)) {
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
            availabilitySwitch.mSwitch({
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
            offerSwitch.mSwitch({
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

        function toggleRemoveFilterBtn(obj) {
            var x;
            x = $.extend(true, x, obj);
            delete x['category'];
            if (core.objSize(x) > 0) {
                removeFiltersBtn.closest('.widget').removeClass('d-none');
            } else {
                removeFiltersBtn.closest('.widget').addClass('d-none');
            }
        }

        function makeAdjustmentsAccordingToObj(obj) {
            var el, o;

            setCategoryToSearchParams();

            // search query
            if (obj['q'] && core.isString(obj['q'])) {
                $(variables.elements.product.form).val(obj['q']);
            } else {
                $(variables.elements.product.form).val('+');
            }
            // price filter
            if (obj['price'] && obj['price']['min'] && obj['price']['max']) {
                priceFilter.slider('values', 0, obj['price']['min']);
                priceFilter.slider('values', 1, obj['price']['max']);
            }
            // attrs checkboxes/radios
            for (var o in obj) {
                if (obj.hasOwnProperty(o)) {
                    if (/^attrs_.*]/.test(o)) {
                        if (core.isArray(obj[o])) {
                            $('.list_attrs input[type="checkbox"]').each(function () {
                                if (-1 !== $.inArray($(this).val(), obj[o])) {
                                    $(this).attr('checked', 'checked').prop('checked', true);
                                } else {
                                    $(this).removeAttr('checked', 'checked').prop('checked', false);
                                }
                            });
                        } else {
                            $('.list_attrs input[type="checkbox"]').each(function () {
                                if (core.isString(obj[o]) && $(this).val() == obj[o]) {
                                    $(this).attr('checked', 'checked').prop('checked', true);
                                } else {
                                    $(this).removeAttr('checked', 'checked').prop('checked', false);
                                }
                            });
                        }
                        //-----
                        $('.list_attrs input[type="radio"]').each(function () {
                            if (core.isString(obj[o]) && $(this).val() == obj[o]) {
                                $(this).attr('checked', 'checked').prop('checked', true);
                            } else {
                                $(this).removeAttr('checked', 'checked').prop('checked', false);
                            }
                        });
                    }
                }
            }
            // brands checkboxes
            if (obj['brands'] && core.isArray(obj['brands'])) {
                $('.list_brand input[type="checkbox"]').each(function () {
                    if (-1 !== $.inArray($(this).val(), obj['brands'])) {
                        $(this).attr('checked', 'checked').prop('checked', true);
                    } else {
                        $(this).removeAttr('checked', 'checked').prop('checked', false);
                    }
                });
            } else {
                $('.list_brand input[type="checkbox"]').each(function () {
                    if (core.isString(obj['brands']) && $(this).val() == obj['brands']) {
                        $(this).attr('checked', 'checked').prop('checked', true);
                    } else {
                        $(this).removeAttr('checked', 'checked').prop('checked', false);
                    }
                });
            }
            // sizes activate
            if (obj['size'] && core.isArray(obj['size'])) {
                for (o in obj['size']) {
                    if (obj['size'].hasOwnProperty(o)) {
                        obj['size'][o] = decodeURIComponent(obj['size'][o].replace(/\+/g, " "));
                    }
                }
                sizeChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        if (-1 !== $.inArray(el.attr('data-value'), obj['size'])) {
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
                        if (core.isString(obj['size']) && el.attr('data-value') == obj['size']) {
                            el.addClass('active');
                        } else {
                            el.removeClass('active');
                        }
                    }
                });
            }
            // colors activate
            if (obj['color'] && core.isArray(obj['color'])) {
                colorChk.each(function () {
                    el = $(this).find('span');
                    if (el.length) {
                        if (-1 !== $.inArray(el.attr('data-color'), obj['color'])) {
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
                        if (core.isString(obj['color']) && el.attr('data-color') == obj['color']) {
                            el.addClass('active');
                        } else {
                            el.removeClass('active');
                        }
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
            var obj = uriParser.get(null, {}, true);
            toggleRemoveFilterBtn(obj);
            makeAdjustmentsAccordingToObj(obj);
            if (!isInProgress) {
                // push query to window state
                if (canPushState) {
                    uriParser.pushState({obj});
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

                    // Lazy loader (pictures, videos, etc.)
                    shop.lazyFn();
                    cart.assignEventsToAddOrUpdateBtn();

                    // scroll to top of main product container
                    core.scrollTo('#__main_product_container', 85, 300);
                }, {
                    params: obj,
                }, true, function () {
                    shop.hideLoader(loaderId);
                    canPushState = true;
                    isInProgress = false;
                    createLoader = true;
                    stickySidebar();
                });
            }
        }

        removeFiltersBtnClick();
        stickySidebar();
        paginationClick();
        setCategoryToSearchParams();
        querySubmitting();
        sortSelectChange();
        priceFilterChange();
        attrCheckChange();
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