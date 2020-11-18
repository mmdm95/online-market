/**
 * Global variables
 */
window.MyGlobalVariables = {
    icons: {
        success: 'la la-check',
        info: 'la la-info',
        warning: 'la la-bell',
        error: 'la la-danger',
    },
    messages: {
        request: {
            error: 'دریافت اطلاعات با خطا روبرو شد!',
        },
        confirm: 'آیا از این عملیات مطمئن هستید؟',
    },
    apiCodes: {
        success: 200,
    },
    url: {
        csrfToken: '/api/csrf-token',
        cart: {
            get: '/api/cart/get',
            save: '/api/cart/store',
            add: '/api/cart/add',
            update: '/api/cart/update',
            remove: '/api/cart/remove',
        },
        products: {
            get: {
                products: '/api/products',
                product: '/api/product/get',
                properties: '/api/product/property/get',
                comments: '/api/product/comments',
            },
            add: {
                comment: '/api/product/comment/add',
                like: '/api/product/like/add',
            },
            remove: {
                like: '/api/product/like/remove'
            },
        },
        users: {
            get: {
                favoriteProducts: '/api/product/favorites',
                orders: '/api/orders',
                addresses: '/api/user/addresses',
                ticket: '/api/tickets',
                wallet: '/api/user/wallet',
                returnOrders: '/api/user/return-orders',
            },
            add: {
                favoriteProduct: '/api/product/favorite/add',
                addresses: '/api/user/address/add',
                ticket: '/api/ticket/add',
                returnOrders: '/api/user/return-order/add',
            },
            update: {
                addresses: '/api/user/address/update',
            },
            remove: {
                favoriteProduct: '/api/product/favorite/remove',
                addresses: '/api/user/address/remove',
                ticket: '/api/ticket/remove',
                returnOrders: '/api/user/return-order/remove',
            },
        },
        orders: {
            add: '/api/order/add',
            confirm: '/api/order/confirm',
        },
        pages: {
            get: {
                faq: '/api/faq/get',
            },
            add: {
                contact: '/api/contact/add',
                complaint: '/api/complaint/add',
            },
        },
    },
};

(function ($) {
    window.TheCore = (function () {
        return {
            // Inheritance support
            extend: function (sub, sup) {
                let f = function () {
                };
                f.prototype = sup.prototype;
                sub.prototype = new f();
                sub.prototype.constructor = sub;
                return sub;
            },
            trim: function (string, char) {
                if (char === "]") char = "\\]";
                if (char === "\\") char = "\\\\";
                return string.replace(new RegExp(
                    "^[" + char + "]+|[" + char + "]+$", "g"
                ), "");
            },
        };
    })();

    // make ShopBase global
    window.TheShopBase = (function () {
        function ShopBase() {
            // nothing for now!
        }

        $.extend(ShopBase.prototype, {
            getAPIHeader: function () {
                return {
                    'HTTP_AUTHORIZATION': btoa(window.EnvGlobalVariables.REST_API_USER + ':' + window.EnvGlobalVariables.REST_API_KEY),
                };
            },

            handleAPIData: function (data) {
                return {
                    data: data.data,
                    error: data.error,
                    code: data.status_code,
                };
            },

            getCsrfToken: function (name, thenCallback, catchCallback) {
                let _ = this;
                name = typeof name === typeof '' ? name : '';
                name = '/' + window.TheCore.trim(name, '/');

                axios({
                    url: MyGlobalVariables.url.csrfToken + name,
                    method: 'get',
                    headers: _.getAPIHeader(),
                })
                    .then(thenCallback)
                    .catch(catchCallback);
            },

            toasts: {
                toast: function (message, options) {
                    if ($.iziToast) {
                        $.iziToast(options);
                    } else {
                        alert(message);
                    }
                },
                confirm: function (message, options) {
                    let res = false;

                    if ($.iziToast) {
                        $.iziToast(options);
                    } else {
                        res = confirm(message);
                    }

                    return res;
                }
            },

            deleteItem: function (url, method, options, thenCallback, catchCallback) {
                let _ = this;
                options = typeof options === typeof {} ? options : {};

                if (_.confirm(MyGlobalVariables.messages.confirm)) {
                    axios($.extend({
                        method: method,
                        url: url,
                    }, options))
                        .then(thenCallback)
                        .catch(catchCallback);
                }
            },
        });

        return ShopBase;
    })();
})(jQuery);
