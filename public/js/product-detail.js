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
    window.MyGlobalVariables.validation = $.extend({}, window.MyGlobalVariables.validation, {
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
            //-----
            changeableStuffsSelect;

        variables = window.MyGlobalVariables;
        core = window.TheCore;
        shop = new window.TheShop();

        constraints = {
            productComment: {
                name: variables.validation.common.name,
                message: variables.validation.constraints.productComment.message,
            },
        };

        priceContainer = $('#__product_price_container');
        commentsContainer = $('#__product_comments_container');

        changeableStuffsSelect = $('select[name="changeable-stuffs"]');

        // use these for change [code] and [quantity] for add to cart
        // CODE: [data-cart-item-code]
        // QNT: [data-cart-item-quantity]

        /**
         * It has [partial], [max_cart_count] inside {loadedPrices[code]} variable
         *
         * @param code
         */
        function loadProductProperties(code) {
            if (core.isDefined(loadedPrices[code]) && core.isDefined(loadedPrices[code]['partial'])) {
                priceContainer.html(loadedPrices[code]['partial']);
                // loadedPrices[code]['max_cart_count'];
            } else {
                // send code to server and get price partial
                // put it in place and store it to [loadedPrices] variable
                // ...
            }
        }

        //---------------------------------------------------------------
        // CHANGEABLE STUFFS CHANGE
        //---------------------------------------------------------------
        changeableStuffsSelect.on('selectric-change', function (event, element) {
            var code;
            code = $(element).val();
            if (val && $.trim(code) !== '') {
                loadProductProperties(code);
            }
        });

        //---------------------------------------------------------------
        // PRODUCT COMMENT FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('productComment', constraints.productComment, function () {
            // confirm message sending
            // ...

            // send message to server
            // ...

            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });
    });
})(jQuery);