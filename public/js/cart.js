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

        _.get = function () {
            window.axios({
                method: 'get',
                url: variables.url.cart.get,
            }).then(function (response) {
                var data = shop.handleAPIData(response.data);
                // do other stuffs to handle data items
            }).catch(function (error) {
                // catch error
            });
        };

        _.save = function () {
            window.axios({
                method: 'put',
                url: variables.url.cart.save,
            }).then(function (response) {
                var data = shop.handleAPIData(response.data);
                // do other stuffs to handle data items
            }).catch(function (error) {
                // catch error
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
            window.axios({
                method: 'delete',
                url: variables.cart.remove + '/' + code,
            }).then(function (response) {
                var data = shop.handleAPIData(response.data);
                // do other stuffs to handle data items
            }).catch(function (error) {
                // catch error
            });
        };

        return _;
    };

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var cart = new window.TheCart();


    });
})(jQuery);
