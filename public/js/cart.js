(function ($) {
    /**
     * Cart class contains all cart functionality
     * @returns {Cart}
     * @constructor
     */
    const Cart = function () {
        var _ = this;

        /*************************************************************
         ********************* Private Functions *********************
         *************************************************************/

        /**
         * @param url
         * @param code
         * @param qnt
         */
        function addORUpdate(url, code, qnt) {
            axios({
                method: 'put',
                url: url,
                data: {
                    code: code,
                    qnt: qnt ? qnt : 1,
                },
                headers: window.Shop.getAPIHeader(),
            }).then(function (response) {
                var data = window.Shop.handleAPIData(response.data);
                // do other stuffs to handle data items
            }).catch(function (error) {
                if (error.response) {
                    // The request was made and the server responded with a status code
                    // that falls out of the range of 2xx
                    console.log(error.response.data);
                    console.log(error.response.status);
                    console.log(error.response.headers);
                } else if (error.request) {
                    // The request was made but no response was received
                    // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                    // http.ClientRequest in node.js
                    console.log(error.request);
                } else {
                    // Something happened in setting up the request that triggered an Error
                    console.log('Error', error.message);
                }
                console.log(error.config);
            });
        }

        /*************************************************************
         ********************* Public Functions **********************
         *************************************************************/

        _.get = function () {
            axios({
                method: 'get',
                url: MyGlobalVariables.url.cart.get,
                headers: window.Shop.getAPIHeader(),
            }).then(function (response) {
                var data = window.Shop.handleAPIData(response.data);
                // do other stuffs to handle data items
            }).catch(function (error) {
                // catch error
            });
        };

        _.save = function () {
            axios({
                method: 'put',
                url: MyGlobalVariables.url.cart.save,
                headers: window.Shop.getAPIHeader(),
            }).then(function (response) {
                var data = window.Shop.handleAPIData(response.data);
                // do other stuffs to handle data items
            }).catch(function (error) {
                // catch error
            });
        };

        /**
         * @param code
         * @param qnt
         */
        _.add = function (code, qnt) {
            addORUpdate(MyGlobalVariables.url.cart.add, code, qnt);
        };

        /**
         * @param code
         * @param qnt
         */
        _.update = function (code, qnt) {
            addORUpdate(MyGlobalVariables.url.cart.update, code, qnt);
        };

        /**
         * @param code
         */
        _.remove = function (code) {
            axios({
                method: 'delete',
                url: MyGlobalVariables.cart.remove + '/' + code,
                headers: window.Shop.getAPIHeader(),
            }).then(function (response) {
                var data = window.Shop.handleAPIData(response.data);
                // do other stuffs to handle data items
            }).catch(function (error) {
                // catch error
            });
        };

        return _;
    };

    /**
     * Make Cart class global to window
     * @type {Cart}
     */
    window.Cart = new Cart();
})(jQuery);
