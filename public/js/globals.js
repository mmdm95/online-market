/**
 * Global variables
 */
window.MyGlobalVariables = {
    namespace: '.online_market',
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
        general: {
            confirmLabels: {
                ok: 'باشه',
            },
            btnClasses: {
                ok: 'btn btn-fill-out btn-sm',
            }
        },
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
            delete: '/ajax/cart/delete',
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
        captcha: '/ajax/captcha',
    },
    elements: {
        captcha: {
            mainContainer: '.__captcha_main_container',
            container: '.__captcha_container',
            refreshBtn: '.__captcha_regenerate_btn',
        },
        cart: {
            container: '#__cart_main_container',
            addBtn: '.__add_to_cart_btn',
        },
        newsletter: {
            form: '#__form_newsletter',
            inputs: {
                mobile: 'inp-newsletter-mobile',
            },
        },
        contactUs: {
            form: '#__form_contact',
            inputs: {
                name: 'inp-contact-name',
                email: 'inp-contact-email',
                mobile: 'inp-contact-mobile',
                subject: 'inp-contact-subject',
                message: 'inp-contact-message',
                captcha: 'inp-contact-captcha',
            },
        },
    },
    validation: {
        common: {
            name: {
                presence: {
                    allowEmpty: false,
                    message: '^' + 'فیلد نام را خالی نگذارید.',
                },
                format: {
                    pattern: /^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u,
                    message: '^' + 'نام باید دارای حروف فارسی باشد.',
                },
            },
            email: {
                email: {
                    message: '^' + 'ایمیل نامعتبر است.',
                }
            },
            mobile: {
                presence: {
                    allowEmpty: false,
                    message: '^' + 'فیلد موبایل را خالی نگذارید.',
                },
                format: {
                    pattern: /^(098|\+98|0)?9\d{9}$/,
                    message: '^' + 'موبایل وارد شده نامعتبر است.',
                },
                length: {
                    is: 11,
                    message: '^' + 'موبایل باید عددی ۱۱ رقمی باشد.',
                }
            },
            captcha: {
                presence: {
                    allowEmpty: false,
                    message: '^' + 'فیلد کد تصویر را خالی نگذارید.',
                },
            },
        },
        constraints: {
            contactUs: {
                subject: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد موضوع را خالی نگذارید.',
                    },
                },
                message: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد پیام خالی نگذارید.',
                    },
                },
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
                return null !== t && typeof t !== 'undefined';
            },
            trim: function (string, char) {
                if (char === "]") char = "\\]";
                if (char === "\\") char = "\\\\";
                return string.replace(new RegExp(
                    "^[" + char + "]+|[" + char + "]+$", "g"
                ), "");
            },
            scrollTo: function (el, gap, speed) {
                el = $(el);
                gap = gap && !isNaN(parseInt(gap, 10)) ? parseInt(gap, 10) : 0;
                speed = speed && !isNaN(parseInt(speed, 10)) ? parseInt(speed, 10) : 250;

                $('html, body').animate({
                    scrollTop: el.offset().top - gap,
                }, speed);
            },
            /**
             * @see https://stackoverflow.com/a/6969486/12154893
             * @param string
             * @returns {*}
             */
            escapeRegExp: function (string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
            },
            toEnglishNumbers: function (str) {
                var
                    _ = this,
                    persianDecimal = ['&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;'],
                    arabicDecimal = ['&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;'],
                    persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
                    arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
                    englishNumbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

                if (_.isString(str)) {
                    var i, len;
                    len = englishNumbers.length;
                    for (i = 0; i < len; i++) {
                        str = str.replace(new RegExp(_.escapeRegExp(arabicNumbers[i]), 'ig'), englishNumbers[i]);
                    }
                    for (i = 0; i < len; i++) {
                        str = str.replace(new RegExp(_.escapeRegExp(persianNumbers[i]), 'ig'), englishNumbers[i]);
                    }
                    for (i = 0; i < len; i++) {
                        str = str.replace(new RegExp(_.escapeRegExp(persianDecimal[i]), 'ig'), englishNumbers[i]);
                    }
                    for (i = 0; i < len; i++) {
                        str = str.replace(new RegExp(_.escapeRegExp(arabicDecimal[i]), 'i'), englishNumbers[i]);
                    }
                }

                return str;
            },
            /**
             * @see https://stackoverflow.com/a/9907509/12154893
             * @param obj
             * @param value
             * @returns {*}
             */
            getKeyByValue: function (obj, value) {
                for (var prop in obj) {
                    if (obj.hasOwnProperty(prop)) {
                        if (obj[prop] === value)
                            return prop;
                    }
                }
                return null;
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
            noop: function () {
            },
            //-----
            constraints: {
                contactUs: {
                    name: window.MyGlobalVariables.validation.common.name,
                    email: window.MyGlobalVariables.validation.common.email,
                    mobile: window.MyGlobalVariables.validation.common.mobile,
                    subject: window.MyGlobalVariables.validation.constraints.contactUs.subject,
                    message: window.MyGlobalVariables.validation.constraints.contactUs.message,
                    captcha: window.MyGlobalVariables.validation.common.captcha,
                },
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
             * @param showError
             */
            request: function (url, method, successCallback, options, showError) {
                var _ = this;
                options = typeof options === typeof {} ? options : {};
                showError = window.TheCore.isBoolean(showError) ? showError : false;
                window.axios($.extend(options, {
                    method: method,
                    url: url,
                }))
                    .then(function (response) {
                        var data = _.handleAPIData(response.data);
                        if (null === data.error) {
                            successCallback.call(data);
                        } else {
                            if (showError) {
                                _.toasts.toast(data.error);
                            } else {
                                console.error(data.error);
                            }
                        }
                    })
                    .catch(function (error) {
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            console.log(error.response.data);
                            console.log(error.response.status);
                        } else if (error.request) {
                            if (showError) {
                                _.toasts.toast(window.MyGlobalVariables.messages.request.error);
                            }
                        } else {
                            if (showError) {
                                _.toasts.toast(error.message);
                            }
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

            /**
             * @param name
             * @param successCallback
             */
            captchaReload: function (name, successCallback) {
                var _ = this;
                name = name && window.TheCore.isString(name) ? name : '';

                _.request(window.MyGlobalVariables.url.captcha, 'get', successCallback, {
                    params: {
                        name: name,
                    },
                }, true);
            },

            forms: {
                convertFormObjectNumbersToEnglish: function (obj, formKey) {
                    var
                        keyFromVal,
                        newFormValues = {};
                    for (var prop in obj) {
                        // skip loop if the property is from prototype
                        if (!obj.hasOwnProperty(prop)) continue;

                        // get key from value and if its null return false
                        keyFromVal = window.TheCore.getKeyByValue(window.MyGlobalVariables.elements[formKey].inputs, prop);
                        if (null === keyFromVal) return false;

                        newFormValues[keyFromVal] = window.TheCore.toEnglishNumbers(obj[prop]);
                    }

                    return newFormValues;
                },
                showFormErrors: function (errors) {
                    var shop = new ShopBase();
                    var s, p, a;

                    s = '<h5 class="text-white">' + 'خطا در اعتبار سنجی فرم' + '</h5><hr>';
                    for (p in errors) {
                        if (errors.hasOwnProperty(p)) {
                            for (a of errors[p]) {
                                s += a + "<br>";
                            }
                            s += "<br>";
                        }
                    }
                    shop.toasts.toast(s, {
                        type: 'error',
                        layout: 'center',
                        modal: true,
                        timeout: null,
                    });
                },
            }
        });

        return ShopBase;
    })();

    $(function () {
        var
            shop = new window.TheShopBase(),
            variables = window.MyGlobalVariables,
            $this,
            captchaReloadBtn = $(variables.elements.captcha.refreshBtn);

        //---------------------------------------------------------------
        // CAPTCHA RELOAD
        //---------------------------------------------------------------
        captchaReloadBtn.on('click' + variables.namespace, function () {
            $this = $(this);
            shop.captchaReload(window.captchaPageName, function () {
                $this
                    .closest(variables.elements.captcha.mainContainer)
                    .find(variables.elements.captcha.container)
                    .html(this.data);
            });
        });
        if ($(variables.elements.captcha.mainContainer).length) {
            captchaReloadBtn.trigger('click' + variables.namespace);
        }
    });
})(jQuery);
