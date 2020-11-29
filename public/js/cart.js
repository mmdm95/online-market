(function ($) {
    'use strict';

    var shop, core, variables;

    shop = new window.TheShop();
    core = window.TheCore;
    variables = window.MyGlobalVariables;

    /**
     * Cart class contains all cart functionality
     * @returns {TheCart}
     * @constructor
     */
    window.TheCart = function () {
        var _ = this;

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
            });
        }

        /*************************************************************
         ********************* Public Functions **********************
         *************************************************************/

        _.get = function ($successCallback) {
            shop.request(variables.url.cart.get, 'get', $successCallback);
        };

        _.save = function () {
            shop.request(variables.url.cart.save, 'put', function () {
                // do other stuffs to handle data items
            });
        };

        _.delete = function () {
            shop.request(variables.url.cart.delete, 'delete', function () {
                // do other stuffs to handle data items
            });
        };

        /**
         * @param code
         * @param qnt
         * @param successCallback
         */
        _.add = function (code, qnt, successCallback) {
            addORUpdate(variables.url.cart.add, 'post', code, qnt, successCallback);
        };

        /**
         * @param code
         * @param qnt
         * @param successCallback
         */
        _.update = function (code, qnt, successCallback) {
            addORUpdate(variables.url.cart.update, 'put', code, qnt, successCallback);
        };

        /**
         * @param code
         */
        _.remove = function (code) {
            shop.request(variables.url.cart.remove + '/' + code, 'delete', function () {
                // do other stuffs to handle data items
            });
        };

        return _;
    };

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var cart = new window.TheCart();

        // first fetch all items from cart and put in right place
        cart.get(function () {
            // do other stuffs to handle data items

        });
    });
})(jQuery);
