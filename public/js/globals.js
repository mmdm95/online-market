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
        cart: {
            get: '/ajax/cart/get',
            save: '/ajax/cart/store',
            add: '/ajax/cart/add',
            update: '/ajax/cart/update',
            remove: '/ajax/cart/remove',
        },
        products: {
            get: {
                products: '/ajax/products',
                product: '/ajax/product/get',
                properties: '/ajax/product/property/get',
                comments: '/ajax/product/comments',
            },
            add: {
                comment: '/ajax/product/comment/add',
                like: '/ajax/product/like/add',
            },
            remove: {
                like: '/ajax/product/like/remove'
            },
        },
        users: {
            get: {
                favoriteProducts: '/ajax/product/favorites',
                orders: '/ajax/orders',
                addresses: '/ajax/user/addresses',
                ticket: '/ajax/tickets',
                wallet: '/ajax/user/wallet',
                returnOrders: '/ajax/user/return-orders',
            },
            add: {
                favoriteProduct: '/ajax/product/favorite/add',
                addresses: '/ajax/user/address/add',
                ticket: '/ajax/ticket/add',
                returnOrders: '/ajax/user/return-order/add',
            },
            update: {
                addresses: '/ajax/user/address/update',
            },
            remove: {
                favoriteProduct: '/ajax/product/favorite/remove',
                addresses: '/ajax/user/address/remove',
                ticket: '/ajax/ticket/remove',
                returnOrders: '/ajax/user/return-order/remove',
            },
        },
        orders: {
            add: '/ajax/order/add',
            confirm: '/ajax/order/confirm',
        },
        pages: {
            get: {
                faq: '/ajax/faq/get',
            },
            add: {
                contact: '/ajax/contact/add',
                complaint: '/ajax/complaint/add',
            },
        },
    },
};

(function ($) {
    'use strict';

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
            types: {
                "array": "array",
                "boolean": "boolean",
                "date": "date",
                "function": "function",
                "number": "number",
                "object": "object",
                "regexp": "regexp",
                "string": "string",
            },
            isArray: function (t) {
                return $.type(t) === window.TheCore.types.array;
            },
            isBoolean: function (t) {
                return $.type(t) === window.TheCore.types.boolean;
            },
            isDate: function (t) {
                return $.type(t) === window.TheCore.types.date;
            },
            isFunction: function (t) {
                return $.type(t) === window.TheCore.types['function'];
            },
            isNumber: function (t) {
                return $.type(t) === window.TheCore.types.number;
            },
            isObject: function (t) {
                return $.type(t) === window.TheCore.types.object;
            },
            isRegexp: function (t) {
                return $.type(t) === window.TheCore.types.regexp;
            },
            isString: function (t) {
                return $.type(t) === window.TheCore.types.string;
            },
            trim: function (string, char) {
                if (char === "]") char = "\\]";
                if (char === "\\") char = "\\\\";
                return string.replace(new RegExp(
                    "^[" + char + "]+|[" + char + "]+$", "g"
                ), "");
            },
            getCookie: function (cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (' ' === c.charAt(0)) {
                        c = c.substring(1);
                    }
                    if (0 === c.indexOf(name)) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            getCsrfTokenFromCookie: function () {
                return window.TheCore.getCookie('CSRF-TOKEN');
            },
        };
    })();

    //=========================================================================
    // Axios
    window.axios.defaults.xsrfCookieName = 'CSRF-TOKEN';
    window.axios.defaults.xsrfHeaderName = 'X-CSRF-TOKEN';
    //=========================================================================
    // jQuery
    function csrfSafeMethod(method) {
        // these HTTP methods do not require CSRF protection
        return (/^(GET|HEAD|OPTIONS)$/.test(method));
    }

    $.ajaxSetup({
        beforeSend: function (xhr, settings) {
            if (!csrfSafeMethod(settings.type) && !this.crossDomain) {
                xhr.setRequestHeader("X-CSRF-TOKEN", window.TheCore.getCsrfTokenFromCookie());
            }
        }
    });
    //=========================================================================

    /**
     * Make ShopBase global
     * @type {TheShopBase}
     */
    window.TheShopBase = (function () {
        function ShopBase() {
            // nothing for now!
        }

        $.extend(ShopBase.prototype, {
            /**
             * @param data
             * @returns {{code: *, data: *, error: *}}
             */
            handleAPIData: function (data) {
                return {
                    data: data.data,
                    error: data.error,
                    code: data.status_code,
                };
            },

            toasts: {
                /**
                 * @param message
                 * @param options
                 */
                toast: function (message, options) {
                    if ($.iziToast) {
                        $.iziToast(options);
                    } else {
                        alert(message);
                    }
                },
                /**
                 * @param message
                 * @param options
                 * @returns {boolean}
                 */
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

            /**
             * @param url
             * @param method
             * @param successCallback
             * @param options
             */
            request: function (url, method, successCallback, options) {
                var _ = this;
                options = typeof options === typeof {} ? options : {};
                window.axios($.extend(options, {
                    method: method,
                    url: url,
                }))
                    .then(function (response) {
                        var data = _.handleAPIData(response.data);
                        if (null === data.error) {
                            successCallback.call(data);
                        } else {
                            _.toasts.toast(data.error);
                        }
                    })
                    .catch(function (error) {
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            console.log(error.response.data);
                            console.log(error.response.status);
                        } else if (error.request) {
                            _.toasts.toast('تمکان ارتباط با سرور وجود ندارد!');
                        } else {
                            _.toasts.toast(error.message);
                        }
                    });
            },

            /**
             * @param url
             * @param options
             * @param successCallback
             */
            deleteItem: function (url, options, successCallback) {
                var _ = this;
                options = typeof options === typeof {} ? options : {};

                if (_.confirm(MyGlobalVariables.messages.confirm)) {
                    _.request(url, 'delete', successCallback, options);
                }
            },
        });

        return ShopBase;
    })();
})(jQuery);
