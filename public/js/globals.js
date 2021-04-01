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
        types: {
            success: 'success',
            warning: 'warning',
            error: 'error',
            info: 'info',
            alert: 'alert',
            question: 'question',
        },
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
    api: {
        types: {
            success: 'success',
            warning: 'warning',
            info: 'info',
            error: 'error',
        },
        codes: {},
    },
    url: {
        cart: {
            get: '/ajax/cart/get',
            save: '/ajax/cart/store',
            delete: '/ajax/cart/delete',
            add: '/ajax/cart/add',
            update: '/ajax/cart/update',
            remove: '/ajax/cart/remove',
            getItemsTable: '/ajax/cart/items-table',
            getTotalItemsInfo: '/ajax/cart/total-items-info',
            getTotalInfo: '/ajax/cart/total-info',
            checkCoupon: '/ajax/cart/check-coupon',
            checkStoredCoupon: '/ajax/cart/check-stored-coupon',
            checkPostPrice: '/ajax/cart/check-post-price',
        },
        newsletter: {
            add: '/ajax/newsletter/add',
            remove: '/ajax/newsletter/remove',
        },
        blog: {
            search: '/ajax/blog/search',
        },
        products: {
            get: {
                products: '/ajax/products',
                product: '/ajax/product/get',
                properties: '/ajax/product/price',
                comments: '/ajax/product/comments',
            },
            add: {
                comment: '/ajax/product/comment/add',
                wishList: '/ajax/product/wishlist/toggle',
            },
            remove: {
                wishList: '/ajax/product/wishlist/remove'
            },
            search: '/ajax/product/search',
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
                city: '/ajax/cities',
                province: '/ajax/provinces',
            },
            add: {
                contact: '/ajax/contact/add',
                complaint: '/ajax/complaint/add',
            },
        },
        captcha: '/ajax/captcha',
        image: '/images',
    },
    elements: {
        captcha: {
            mainContainer: '.__captcha_main_container',
            container: '.__captcha_container',
            refreshBtn: '.__captcha_regenerate_btn',
        },
    },
    validation: {
        common: {
            captcha: {
                presence: {
                    allowEmpty: false,
                    message: '^' + 'فیلد کد تصویر را خالی نگذارید.',
                },
            },
            name: {
                presence: {
                    allowEmpty: false,
                    message: '^' + 'فیلد ' + '{{name}}' + ' را خالی نگذارید.',
                },
                format: {
                    pattern: /^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u,
                    message: '^' + '{{name}}' + ' باید دارای حروف فارسی باشد.',
                },
            },
            lastName: {
                presence: {
                    allowEmpty: false,
                    message: '^' + 'فیلد ' + '{{last-name}}' + ' را خالی نگذارید.',
                },
                format: {
                    pattern: /^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u,
                    message: '^' + '{{last-name}}' + ' باید دارای حروف فارسی باشد.',
                },
            },
            enName: {
                presence: {
                    allowEmpty: false,
                    message: '^' + 'فیلد ' + '{{en-name}}' + ' را خالی نگذارید.',
                },
                format: {
                    pattern: /[a-zA-Z-_\s]*/,
                    message: '^' + '{{en-name}}' + ' باید دارای حروف انگلیسی باشد.',
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
            link: {
                format: {
                    pattern: /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi,
                    message: '^' + 'لینک وارد شده نامعتبر است.',
                },
            },
            percent: {
                length: {
                    minimum: 0,
                    maximum: 100,
                    message: '^' + '{percent}' + ' باید بین ۰ و ۱۰۰ باشد.',
                },
            }
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
            isChecked: function (t) {
                return -1 !== [1, '1', true, 'yes', 'on'].indexOf(t);
            },
            isEmptyObject: function (obj) {
                for (var prop in obj) {
                    if (obj.hasOwnProperty(prop)) {
                        return false;
                    }
                }
                return JSON.stringify(obj) === JSON.stringify({});
            },
            objSize: function (obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) ++size;
                }
                return size;
            },
            trim: function (string, char) {
                if (char === "]") char = "\\]";
                if (char === "\\") char = "\\\\";
                return string.replace(new RegExp(
                    "^[" + char + "]+|[" + char + "]+$", "g"
                ), "");
            },
            idGenerator: function (prefix) {
                var elNum, rndEl;
                do {
                    elNum = Math.floor(Math.random() * 10000000);
                    rndEl = $(document).find('#' + prefix + elNum).length;
                } while (rndEl !== 0);
                return prefix + elNum
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
            getCaptchaName: function (btn) {
                var _ = this, val;
                val = $(btn).find('[name="inp-captcha-name"]').val();
                val = _.isString(val) && _.trim(val).length ? val : '';
                return val;
            },
            noop: function () {
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

    /**
     * @see https://stackoverflow.com/a/30840530/12154893
     * @param elem
     * @param index
     * @param val
     * @returns {*}
     */
    $.expr[":"].attrFilter = function (elem, index, val) {
        var len = $(elem.attributes).filter(function () {
            return this.value === val[3];
        }).length;
        if (len > 0) {
            return elem;
        }
    };
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
                    type: data.type,
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
                                    onOkCallback.call(window);
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
             * @param [options]
             * @param [showError]
             * @param [errorCallback]
             */
            request: function (url, method, successCallback, options, showError, errorCallback) {
                var _ = this;
                options = typeof options === typeof {} ? options : {};
                showError = true === showError;
                errorCallback = window.TheCore.isFunction(errorCallback) ? errorCallback : window.TheCore.noop;
                window.axios($.extend(options, {
                    method: method,
                    url: url,
                }))
                    .then(function (response) {
                        var data = _.handleAPIData(response.data);
                        if (null === data.error) {
                            if (window.TheCore.isFunction(successCallback)) {
                                successCallback.call(data);
                            }
                        } else {
                            errorCallback.call(data);
                            if (data.error) {
                                if (showError) {
                                    _.toasts.toast(data.error, {
                                        type: 'error',
                                    });
                                } else {
                                    console.error(data.error);
                                }
                            }
                        }
                    })
                    .catch(function (error) {
                        errorCallback.call({catchErr: error});
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            console.log(error.response.data);
                            console.log(error.response.status);
                        } else if (error.request) {
                            if (showError) {
                                _.toasts.toast(window.MyGlobalVariables.messages.request.error, {
                                    type: 'error',
                                });
                            }
                        } else {
                            if (showError) {
                                _.toasts.toast(error.message, {
                                    type: 'error',
                                });
                            }
                        }
                    });
            },

            forms: {
                /**
                 * @param formKey
                 * @param constraints
                 * @param [validationSuccessCallback]
                 * @param [validationErrorCallback]
                 * @param [aliases]
                 * @param [check]
                 */
                submitForm: function (formKey, constraints, validationSuccessCallback, validationErrorCallback, aliases, check) {
                    var
                        self = this,
                        //-----
                        form,
                        formValues,
                        formErrors;

                    function formatErrors(errors, mapping) {
                        for (var attrName in errors) {
                            if (errors.hasOwnProperty(attrName)) {
                                for (var i = 0; i < errors[attrName].length; i++) {
                                    (function (i) {
                                        for (var alias in mapping) {
                                            if (mapping.hasOwnProperty(alias)) {
                                                errors[attrName][i] = errors[attrName][i].replace(
                                                    alias,
                                                    mapping[alias]);
                                            }
                                        }
                                    })(i);
                                }
                            }
                        }
                        return errors;
                    }

                    validationSuccessCallback = window.TheCore.isFunction(validationSuccessCallback) ? validationSuccessCallback : null;
                    validationErrorCallback = window.TheCore.isFunction(validationErrorCallback) ? validationErrorCallback : null;
                    check = !(false === check);

                    form = $(window.MyGlobalVariables.elements[formKey].form);
                    form.submit(function () {
                        if (check) {
                            formValues = self.convertFormObjectNumbersToEnglish(window.validate.collectFormValues(this), formKey);
                            /**
                             * @see https://github.com/ansman/validate.js/issues/69#issuecomment-567751903
                             * @type {ValidationResult}
                             */
                            formErrors = window.validate(formValues, constraints);
                            if (!formErrors) {
                                return validationSuccessCallback.apply(null, [formValues]);
                            }

                            formErrors = formatErrors(formErrors, aliases);

                            // , {
                            //         prettify: function prettify(string) {
                            //             console.log(validate.prettify(string));
                            //             return aliases[string] || validate.prettify(string);
                            //         },
                            //     }

                            return validationErrorCallback.apply(null, [formErrors]);
                        }
                    });
                },

                /**
                 * @param obj
                 * @param formKey
                 * @returns {boolean}
                 */
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

                /**
                 * @param errors
                 */
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
            },

            /**
             * @param url
             * @param [successCallback]
             * @param [options]
             * @param [showError]
             * @param [errorCallback]
             */
            deleteItem: function (url, successCallback, options, showError, errorCallback) {
                var _ = this;
                options = typeof options === typeof {} ? options : {};
                if (!window.TheCore.isFunction(successCallback)) {
                    successCallback = function () {
                        _.toasts.toast(this.data, {
                            type: 'success',
                        });
                    };
                }

                _.toasts.confirm(MyGlobalVariables.messages.confirm, function () {
                    _.request(url, 'delete', successCallback, options, showError, errorCallback);
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

            /**
             * @param successCallback
             */
            getProvinces: function (successCallback) {
                var _ = this;
                _.request(window.MyGlobalVariables.url.pages.get.province, 'get', successCallback, null, false);
            },

            /**
             * @param province_id
             * @param successCallback
             */
            getCities: function (province_id, successCallback) {
                var _ = this;
                _.request(window.MyGlobalVariables.url.pages.get.city + '/' + province_id, 'get', successCallback, null, false);
            },

            /**
             * @param provincesSelect
             */
            loadProvinces: function (provincesSelect) {
                this.getProvinces(function () {
                    var _, $this, newOption, i, len;
                    var currProvince;
                    _ = this;
                    if (provincesSelect.length) {
                        provincesSelect.each(function () {
                            $this = $(this);
                            currProvince = provincesSelect.attr('data-current-province');
                            currProvince = currProvince && !isNaN(parseInt(currProvince, 10)) ? parseInt(currProvince, 10) : null;
                            provincesSelect.find('.__removable_province_option').remove();
                            len = _.data.length;
                            // append all items to select
                            for (i = 0; i < len; ++i) {
                                newOption = '<option class="__removable_province_option" ' + (currProvince && currProvince == _.data[i].id ? 'selected="selected" ' : '') + 'value="' + _.data[i].id + '">' + _.data[i].name + '</option>';
                                provincesSelect.append(newOption);
                            }
                            // refresh select plugin if it's registered on element
                            if ($.fn.select2 && provincesSelect.data('select2')) {
                                provincesSelect.trigger('change');
                            } else if ($.fn.selectric && provincesSelect.data('selectric')) {
                                provincesSelect.selectric('refresh');
                            }
                            // trigger on change if there is a current province id
                            if (currProvince) {
                                provincesSelect.trigger('change');
                            }
                        });
                    }
                });
            },

            /**
             * @param citiesSelect
             * @param id
             */
            loadCities: function (citiesSelect, id) {
                this.getCities(id, function () {
                    var _ = this, currCity, newOption;
                    var i, len;
                    if (citiesSelect && citiesSelect.length) {
                        citiesSelect.each(function () {
                            currCity = citiesSelect.attr('data-current-city');
                            currCity = currCity && !isNaN(parseInt(currCity, 10)) ? parseInt(currCity, 10) : null;
                            citiesSelect.find('.__removable_city_option').remove();
                            len = _.data.length;
                            // append all items to select
                            for (i = 0; i < len; ++i) {
                                newOption = '<option class="__removable_city_option" ' + (currCity && currCity == _.data[i].id ? 'selected="selected" ' : '') + 'value="' + _.data[i].id + '">' + _.data[i].name + '</option>';
                                citiesSelect.append(newOption);
                            }
                            // refresh select plugin if it's registered on element
                            if ($.fn.select2 && citiesSelect.data('select2')) {
                                citiesSelect.trigger('change');
                            } else if ($.fn.selectric && citiesSelect.data('selectric')) {
                                citiesSelect.selectric('refresh');
                            }
                        });
                    }
                });
            }
        });

        return ShopBase;
    })();

    $(function () {
        var
            shop = new window.TheShopBase(),
            variables = window.MyGlobalVariables,
            core = window.TheCore,
            //-----
            $this,
            captchaReloadBtn = $(variables.elements.captcha.refreshBtn),
            provincesSelect = $('.city-loader-select'),
            citiesSelect = $(provincesSelect.attr('data-city-select-target'));

        //---------------------------------------------------------------
        // CAPTCHA RELOAD
        //---------------------------------------------------------------
        captchaReloadBtn.on('click' + variables.namespace, function () {
            var name;

            $this = $(this);
            name = core.getCaptchaName($this);
            shop.captchaReload(name, function () {
                $this
                    .closest(variables.elements.captcha.mainContainer)
                    .find(variables.elements.captcha.container)
                    .html(this.data);
            });
        });
        if ($(variables.elements.captcha.mainContainer).length) {
            captchaReloadBtn.trigger('click' + variables.namespace);
        }

        //---------------------------------------------------------------
        // LOAD CITIES ACCORDING TO PROVINCES
        //---------------------------------------------------------------
        provincesSelect.on('change' + variables.namespace, function () {
            var id;
            $this = $(this);
            id = $this.find(':selected').val();
            if (id) {
                shop.loadCities(citiesSelect, id);
            }
        });

        //---------------------------------------------------------------
        // LOAD PROVINCES AND SELECT CURRENT IF NEEDED
        //---------------------------------------------------------------
        shop.loadProvinces(provincesSelect);
    });
})(jQuery);
