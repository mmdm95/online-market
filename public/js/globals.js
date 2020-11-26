/**
 * Global variables
 */
window.MyGlobalVariables = {
    icons: {
        success: '',
        info: '',
        warning: '',
        error: '',
    },
    messages: {
        request: {
            error: 'ارسال/دریافت اطلاعات با خطا روبرو شد!',
        },
    },
    toasts: {
        toast: {
            theme: 'sunset',
            layout: 'topRight',
        },
        confirm: {
            message: 'آیا از این عملیات مطمئن هستید؟',
            theme: 'sunset',
            type: 'question',
            confirmLabels: {
                yes: 'بله',
                no: 'خیر',
            },
            btnClasses: {
                yes: 'btn btn-fill-line ml-1 btn-sm',
                no: 'btn btn-fill-out btn-sm',
            }
        },
    },
    apiCodes: {},
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
                var f = function () {
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
            isDefined: function (t) {
                return typeof t !== 'undefined';
            },
            trim: function (string, char) {
                if (char === "]") char = "\\]";
                if (char === "\\") char = "\\\\";
                return string.replace(new RegExp(
                    "^[" + char + "]+|[" + char + "]+$", "g"
                ), "");
            },
            noop: function () {
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
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
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
                    options = typeof options === typeof {} ? options : {};
                    if (window.TheCore.isDefined(Noty)) {
                        new Noty($.extend({
                            theme: window.MyGlobalVariables.toasts.toast.theme,
                            type: 'alert',
                            layout: window.MyGlobalVariables.toasts.toast.layout,
                            text: message,
                            timeout: 5000,
                            modal: false,
                        }, options)).show();
                    } else {
                        alert(message);
                    }
                },
                /**
                 * @param message
                 * @param onOkCallback
                 * @param options
                 * @returns {boolean}
                 */
                confirm: function (message, onOkCallback, options) {
                    var res = false;
                    onOkCallback = window.TheCore.isFunction(onOkCallback) ? onOkCallback : window.TheCore.noop;
                    options = typeof options === typeof {} ? options : {};
                    message = message ? message : window.MyGlobalVariables.toasts.confirm.message;

                    if (window.TheCore.isDefined(Noty)) {
                        var n = new Noty($.extend({
                            theme: window.MyGlobalVariables.toasts.confirm.theme,
                            type: window.MyGlobalVariables.toasts.confirm.type,
                            layout: 'topCenter',
                            text: message,
                            timeout: false,
                            modal: true,
                            closeWith: ['button'],
                            buttons: [
                                Noty.button(window.MyGlobalVariables.toasts.confirm.confirmLabels.yes, window.MyGlobalVariables.toasts.confirm.btnClasses.yes, function () {
                                    onOkCallback.call(null);
                                    n.close();
                                }),

                                Noty.button(window.MyGlobalVariables.toasts.confirm.confirmLabels.no, window.MyGlobalVariables.toasts.confirm.btnClasses.no, function () {
                                    n.close();
                                })
                            ]
                        }, options)).show();
                    } else {
                        res = confirm(message);
                    }

                    if (res) {
                        onOkCallback.call(null);
                    }
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
                            _.toasts.toast(window.MyGlobalVariables.messages.request.error);
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

                _.confirm(MyGlobalVariables.messages.confirm, function () {
                    _.request(url, 'delete', successCallback, options);
                });
            },
        });

        return ShopBase;
    })();
})(jQuery);
