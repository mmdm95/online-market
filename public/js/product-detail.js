(function ($) {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
        productComment: {
            form: '#__form_product_detail_comment',
            inputs: {
                name: 'inp-comment-name',
                message: 'inp-comment-message',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            productComment: {
                message: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد نظر خالی نگذارید.',
                    },
                },
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
            //-----
            priceContainer,
            commentsContainer,
            loadedPrices = {},
            currentProductId,
            //-----
            changeableStuffsSelect,
            addToCartBtn,
            inpQuantity,
            //-----
            loaderId,
            createLoader = true;

        variables = window.MyGlobalVariables;
        core = window.TheCore;
        shop = new window.TheShop();

        constraints = {
            productComment: {
                name: variables.validation.common.name,
                message: variables.validation.constraints.productComment.message,
            },
        };

        currentProductId = $('#__current_product_id').val();

        priceContainer = $('#__product_price_container');
        commentsContainer = $('#__product_comments_container');

        changeableStuffsSelect = $('select[name="changeable-stuffs"]');
        addToCartBtn = $('#__main_add_to_cart');
        inpQuantity = $('input[name="quantity"]');

        /**
         * @param code
         */
        function setFetchedProductPropertyToPlaces(code) {
            priceContainer.html(loadedPrices[code]['partial']);
            inpQuantity.attr('data-max-cart-count', loadedPrices[code]['max_cart_count'] ? loadedPrices[code]['max_cart_count'] : 0);
            addToCartBtn.attr('data-cart-item-code', code);
        }

        /**
         * It has [partial], [max_cart_count] inside {loadedPrices[code]} variable
         *
         * @param code
         */
        function loadProductProperties(code) {
            if (code) {
                if (core.isDefined(loadedPrices[code]) && core.isDefined(loadedPrices[code]['partial'])) {
                    setFetchedProductPropertyToPlaces(code);
                } else {
                    shop.request(variables.url.products.get.properties + '/' + code, 'get', function () {
                        var d = this.data;

                        if (!loadedPrices[code]) {
                            loadedPrices[code] = {};
                        }
                        loadedPrices[code]['partial'] = d['html'];
                        loadedPrices[code]['max_cart_count'] = d['max_cart_count'];

                        setFetchedProductPropertyToPlaces(code);
                    }, false);
                }
            }
        }

        /**
         * @param page
         */
        function loadPaginatedComments(page) {
            page = page && !isNaN(parseInt(page, 10)) ? parseInt(page, 10) : 1;
            shop.request(variables.url.products.get.comments + '/' + currentProductId, 'get', function () {
                commentsContainer.html(this.data);
                paginationClick();
            }, {
                params: {
                    page: page,
                }
            }, false);
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
                        loadPaginatedComments(page);
                    }
                });
        }

        //-----
        loadPaginatedComments();

        //---------------------------------------------------------------
        // CHANGEABLE STUFFS CHANGE
        //---------------------------------------------------------------
        changeableStuffsSelect.on('selectric-init', function () {
            loadProductProperties(changeableStuffsSelect.find(':selected').val());
        }).on('selectric-change', function (event, element) {
            var code;
            code = $(element).val();
            if (code && $.trim(code) !== '') {
                loadProductProperties(code);
            }
        });

        //---------------------------------------------------------------
        // QUANTITY INPUT CHANGE
        //---------------------------------------------------------------
        inpQuantity.on('input', function (e) {
            var count;
            count = $(this).val();
            count = count && !isNaN(parseInt(count, 10)) ? parseInt(count, 10) : 0;

            if (count > 0) {
                addToCartBtn.attr('data-cart-item-quantity', count);
            }
        });

        //---------------------------------------------------------------
        // PRODUCT COMMENT FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('productComment', constraints.productComment, function () {
            shop.toasts.confirm('آیا از ارسال نظر مطمئن هستید؟', function () {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = shop.showLoader();
                }
                shop.request(variables.url.products.add.comment + '/' + currentProductId, 'post', function () {
                    shop.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.productComment.form).reset();
                    shop.toasts.toast(this.data, {
                        type: 'success',
                    });
                    paginationClick();
                    createLoader = true;
                }, {}, true, function () {
                    createLoader = true;
                });
            });

            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });
    });
})(jQuery);