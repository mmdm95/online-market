(function ($) {
    'use strict';

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var
            shop = new window.TheShop(),
            core = window.TheCore,
            variables = window.MyGlobalVariables;

        /**
         * Cart class contains all cart functionality
         * @returns {TheCart}
         * @constructor
         */
        window.TheCart = (function () {
            var
                _ = this,
                cartContainer = $(variables.elements.cart.container);

            /*************************************************************
             ********************* Private Functions *********************
             *************************************************************/

            /**
             * @param url
             * @param method
             * @param code
             * @param qnt
             * @param successCallback
             */
            function addORUpdate(url, method, code, qnt, successCallback) {
                shop.request(url, method, function () {
                    successCallback.call(this);
                }, {
                    data: {
                        code: code,
                        qnt: qnt ? qnt : 1,
                    },
                }, true);
            }

            /*************************************************************
             ********************* Public Functions **********************
             *************************************************************/

            /**
             * @param $successCallback
             */
            _.get = function ($successCallback) {
                shop.request(variables.url.cart.get, 'get', $successCallback, {}, true);
            };

            /**
             * Save a cart to database
             */
            _.save = function () {
                shop.request(variables.url.cart.save, 'put', function () {
                    // do other stuffs to handle data items
                }, {}, true);
            };

            /**
             * Delete a cart from database
             */
            _.delete = function () {
                shop.request(variables.url.cart.delete, 'delete', function () {
                    // do other stuffs to handle data items
                }, {}, true);
            };

            /**
             * @param code
             * @param successCallback
             */
            _.add = function (code, successCallback) {
                if (core.isDefined(code)) {
                    addORUpdate(variables.url.cart.add, 'post', code, 1, successCallback);
                }
            };

            /**
             * @param code
             * @param qnt
             * @param successCallback
             */
            _.update = function (code, qnt, successCallback) {
                if (core.isDefined(code)) {
                    addORUpdate(variables.url.cart.update, 'put', code, qnt, successCallback);
                }
            };

            /**
             * @param code
             */
            _.remove = function (code) {
                shop.request(variables.url.cart.remove + '/' + code, 'delete', function () {
                    // do other stuffs to handle data items
                }, {}, true);
            };

            _.getNPlaceCart = function () {
                _.get(function () {
                    cartContainer.html(this.data);
                });
            };

            return _;
        });

        var
            $this,
            cart,
            addToCartBtn;

        var
            dataItemCode = 'data-cart-item-code',
            dataItemQnt = 'data-cart-item-quantity';

        cart = new window.TheCart();
        addToCartBtn = $(variables.elements.cart.addBtn);

        /**
         * Cart add or update button click event
         */
        addToCartBtn.on('click' + variables.namespace + ' touchend' + variables.namespace, function (e) {
            e.preventDefault();

            var code, qnt;

            $this = $(this);
            code = $this.attr(dataItemCode);
            if (code) {
                qnt = $this.attr(dataItemQnt);

                qnt = core.isDefined(qnt) && !isNaN(parseInt(qnt, 10)) ? parseInt(qnt, 10) : null;
                qnt = null !== qnt && qnt > 0 ? qnt : null;

                if (null === qnt) {
                    cart.add(code, function () {
                        cart.getNPlaceCart();
                    });
                } else {
                    cart.update(code, qnt, function () {
                        cart.getNPlaceCart();
                    });
                }
            } else {
                shop.toasts.toast('لطفا محصول مورد نظر خود را انتخاب کنید.', {
                    type: 'warning',
                });
            }
        });
    });
})(jQuery);
