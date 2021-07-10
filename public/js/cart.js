(function ($) {
    'use strict';

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var
            shop = new window.TheShop(),
            core = window.TheCore,
            variables = window.MyGlobalVariables,
            //-----
            createLoader = true,
            loaderId,
            timeout;

        /**
         * Cart class contains all cart functionality
         * @returns {TheCart}
         * @constructor
         */
        window.TheCart = (function () {
            var
                _ = this,
                cartContainer = $(variables.elements.cart.container),
                dataItemCode = 'data-cart-item-code',
                dataItemQnt = 'data-cart-item-quantity';

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
                shop.request(url + '/' + code, method, function () {
                    successCallback.call(this);
                }, {
                    data: {
                        qnt: qnt ? qnt : 1,
                    },
                }, true);
            }

            /*************************************************************
             ********************* Public Functions **********************
             *************************************************************/

            /**
             * @param [successCallback]
             */
            _.get = function (successCallback) {
                shop.request(variables.url.cart.get, 'get', successCallback, {}, true);
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
             *
             * @param id
             */
            _.delete = function (id) {
                shop.request(variables.url.cart.delete + '/' + id, 'delete', function () {
                    // do other stuffs to handle data items
                }, {}, true);
            };

            /**
             * @param code
             * @param [successCallback]
             */
            _.add = function (code, successCallback) {
                if (core.isDefined(code)) {
                    addORUpdate(variables.url.cart.add, 'post', code, 1, successCallback);
                }
            };

            /**
             * @param code
             * @param qnt
             * @param [successCallback]
             */
            _.update = function (code, qnt, successCallback) {
                if (core.isDefined(code)) {
                    addORUpdate(variables.url.cart.update, 'put', code, qnt, successCallback);
                }
            };

            /**
             * @param code
             * @param [successCallback]
             */
            _.remove = function (code, successCallback) {
                shop.request(variables.url.cart.remove + '/' + code, 'delete', function () {
                    successCallback.call(this);
                }, {}, true);
            };

            /**
             * it'll place the cart items info cart container
             */
            _.getNPlaceCart = function () {
                var self = this;
                _.get(function () {
                    cartContainer.html(this.data);
                    shop.lazyFn();
                    self.assignEventsToRemoveBtn();
                });
            };

            /**
             * @param btn
             */
            _.removeNPlaceCartFunctionality = function (btn) {
                var code;
                btn = $(btn);
                code = btn.attr(dataItemCode);
                if (code) {
                    shop.toasts.confirm('آیا مطمئن هستید؟', function () {
                        cart.remove(code, function () {
                            shop.toasts.toast(this.data, {
                                type: variables.toasts.types.info,
                            });
                            cart.getNPlaceCart();
                        });
                    });
                } else {
                    shop.toasts.toast('لطفا محصول مورد نظر خود را انتخاب کنید.', {
                        type: 'warning',
                    });
                }
            };

            /**
             * @param [successCallback]
             */
            _.getCartItems = function (successCallback) {
                shop.request(variables.url.cart.getItemsTable, 'get', successCallback, {}, true);
            };

            /**
             * @param [successCallback]
             */
            _.getTotalCartItemsInfo = function (successCallback) {
                shop.request(variables.url.cart.getTotalItemsInfo, 'get', successCallback, {}, true);
            };

            /**
             * @param [successCallback]
             */
            _.getTotalCartInfo = function (successCallback) {
                shop.request(variables.url.cart.getTotalInfo, 'get', successCallback, {}, true);
            };

            /**
             * @param [coupon]
             * @param [successCallback]
             * @param [showError]
             */
            _.checkCoupon = function (coupon, successCallback, showError) {
                var url;
                if (coupon) {
                    url = variables.url.cart.checkCoupon + '/' + coupon;
                } else {
                    url = variables.url.cart.checkStoredCoupon;
                }
                shop.request(url, 'post', successCallback, {}, false !== showError);
            };

            _.assignEventsToAddOrUpdateBtn = function () {
                var $this;
                /**
                 * Cart add or update button click event
                 */
                $(variables.elements.cart.addBtn).off('click' + variables.namespace + ' touchend' + variables.namespace).on('click' + variables.namespace + ' touchend' + variables.namespace, function (e) {
                    e.preventDefault();

                    var code, qnt;

                    $this = $(this);
                    code = $this.attr(dataItemCode);
                    if (code) {
                        qnt = $this.attr(dataItemQnt);

                        qnt = core.isDefined(qnt) && !isNaN(parseInt(qnt, 10)) ? parseInt(qnt, 10) : null;
                        qnt = null !== qnt && qnt > 0 ? qnt : null;

                        if (createLoader) {
                            createLoader = false;
                            loaderId = shop.showLoader();
                        }

                        if (null === qnt) {
                            cart.add(code, function () {
                                createLoader = true;
                                shop.hideLoader(loaderId);

                                shop.toasts.toast(this.data, {
                                    type: variables.toasts.types.success,
                                });
                                cart.getNPlaceCart();
                            });
                        } else {
                            cart.update(code, qnt, function () {
                                createLoader = true;
                                shop.hideLoader(loaderId);

                                shop.toasts.toast(this.data, {
                                    type: variables.toasts.types.success,
                                });
                                cart.getNPlaceCart();
                            });
                        }

                        clearTimeout(timeout);
                        timeout = setTimeout(function () {
                            createLoader = true;
                            shop.hideLoader(loaderId);
                        }, 3000);
                    } else {
                        shop.toasts.toast('لطفا محصول مورد نظر خود را انتخاب کنید.', {
                            type: 'warning',
                        });
                    }
                });
            };

            _.assignEventsToRemoveBtn = function () {
                /**
                 * Cart remove button click event
                 */
                $(variables.elements.cart.removeBtn)
                    .off('click' + variables.namespace + ' touchend' + variables.namespace)
                    .on('click' + variables.namespace + ' touchend' + variables.namespace, function (e) {
                        e.preventDefault();
                        _.removeNPlaceCartFunctionality($(this));
                    });
            };

            return _;
        });

        var cart = new window.TheCart();

        cart.assignEventsToAddOrUpdateBtn();
        cart.assignEventsToRemoveBtn();
    });
})(jQuery);
