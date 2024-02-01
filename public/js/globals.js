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
      tokenError: 'درخواست توکن امنیتی با خطا روبرو شد، لطفا دوباره تلاش نمایید.',
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
      removePostPrice: '/ajax/cart/remove-post-price',
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
    csrfToken: '/ajax/csrf-token',
    image: '/images',
    resendCode: {
      forgetPassword: '/ajax/resend-recover-forget-password',
    },
  },
  elements: {
    captcha: {
      mainContainer: '.__captcha_main_container',
      container: '.__captcha_container',
      refreshBtn: '.__captcha_regenerate_btn',
    },
  },
  validationSetting: {
    methods: {
      requiredNotEmpty: function (value) {
        return $.trim(value) !== '';
      },
      format: function (value, element, regex) {
        return this.optional(element) || (new RegExp(regex,)).test(value);
      },
      length: function (value, element, length) {
        if (isNaN(parseInt(length, 10))) {
          return this.optional(element) || false;
        }
        return this.optional(element) || $(element).val().length === parseInt(length, 10);
      },
    },
    messages: {
      requiredNotEmpty: 'تکمیل این فیلد اجباری است.',
      format: 'مقدار وارد شده با فرمت مورد نظر مغایرت دارد.',
    },
  },
  validation: {
    common: {
      captcha: {
        rules: {
          requiredNotEmpty: true,
        },
        messages: {
          requiredNotEmpty: 'فیلد کد تصویر را خالی نگذارید.',
        },
      },
      name: {
        rules: {
          requiredNotEmpty: true,
          format: /^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u,
        },
        messages: {
          requiredNotEmpty: 'فیلد ' + '{{name}}' + ' را خالی نگذارید.',
          format: '{{name}}' + ' باید دارای حروف فارسی باشد.',
        },
      },
      lastName: {
        rules: {
          requiredNotEmpty: true,
          format: /^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u,
        },
        messages: {
          requiredNotEmpty: 'فیلد ' + '{{last-name}}' + ' را خالی نگذارید.',
          format: '{{last-name}}' + ' باید دارای حروف فارسی باشد.',
        },
      },
      enName: {
        rules: {
          requiredNotEmpty: true,
          format: /[a-zA-Z-_\s]*/,
        },
        messages: {
          requiredNotEmpty: 'فیلد ' + '{{en-name}}' + ' را خالی نگذارید.',
          format: '{{en-name}}' + ' باید دارای حروف انگلیسی باشد.',
        },
      },
      email: {
        rules: {
          email: true
        },
        messages: {
          email: 'ایمیل نامعتبر است.',
        },
      },
      mobile: {
        rules: {
          requiredNotEmpty: true,
          format: /^(098|\+98|0)?9\d{9}$/,
          length: 11,
        },
        messages: {
          requiredNotEmpty: 'فیلد موبایل را خالی نگذارید.',
          format: 'موبایل وارد شده نامعتبر است.',
          length: 'موبایل باید عددی ۱۱ رقمی باشد.',
        },
      },
      link: {
        rules: {
          format: /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi,
        },
        messages: {
          format: 'لینک وارد شده نامعتبر است.',
        },
      },
      percent: {
        rules: {
          min: 0,
          max: 100,
        },
        messages: {
          min: '{percent}' + ' باید بین ۰ و ۱۰۰ باشد.',
          max: '{percent}' + ' باید بین ۰ و ۱۰۰ باشد.',
        },
      },
    },
  },
};

(function (factory) {
  if (typeof define === "function" && define.amd) {
    define(["jquery", "../jquery.validate"], factory);
  } else if (typeof module === "object" && module.exports) {
    module.exports = factory(require("jquery"));
  } else {
    factory(jQuery);
  }
}(function ($) {

  /*
   * Translated default messages for the jQuery validation plugin.
   * Locale: FA (Persian; فارسی)
   */
  $.extend($.validator.messages, {
    required: "تکمیل این فیلد اجباری است.",
    remote: "لطفا این فیلد را تصحیح کنید.",
    email: "لطفا یک ایمیل صحیح وارد کنید.",
    url: "لطفا آدرس صحیح وارد کنید.",
    date: "لطفا تاریخ صحیح وارد کنید.",
    dateFA: "لطفا یک تاریخ صحیح وارد کنید.",
    dateISO: "لطفا تاریخ صحیح وارد کنید (ISO).",
    number: "لطفا عدد صحیح وارد کنید.",
    digits: "لطفا تنها رقم وارد کنید.",
    creditcard: "لطفا کریدیت کارت صحیح وارد کنید.",
    equalTo: "لطفا مقدار برابری وارد کنید.",
    extension: "لطفا مقداری وارد کنید که",
    alphanumeric: "لطفا مقدار را عدد (انگلیسی) وارد کنید.",
    maxlength: $.validator.format("لطفا بیشتر از {0} حرف وارد نکنید."),
    minlength: $.validator.format("لطفا کمتر از {0} حرف وارد نکنید."),
    rangelength: $.validator.format("لطفا مقداری بین {0} تا {1} حرف وارد کنید."),
    range: $.validator.format("لطفا مقداری بین {0} تا {1} حرف وارد کنید."),
    max: $.validator.format("لطفا مقداری کمتر از {0} وارد کنید."),
    min: $.validator.format("لطفا مقداری بیشتر از {0} وارد کنید."),
    minWords: $.validator.format("لطفا حداقل {0} کلمه وارد کنید."),
    maxWords: $.validator.format("لطفا حداکثر {0} کلمه وارد کنید.")
  });
  return $;
}));

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
      isJqueryElement: function (o) {
        return o && this.isString(o.jquery);
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

        if (el.length) {
          $('html, body').animate({
            scrollTop: el.offset().top - gap,
          }, speed);
        }
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
      var self = this;

      $.validator.setDefaults({
        ignore: '[data-ignored]',
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        onsubmit: false,
        errorClass: 'border border-danger text-danger',
        validClass: 'border border-success text-success',
        normalizer: function (value) {
          return $.trim(self.forms.convertFormValueNumbersToEnglish(value));
        },
        errorPlacement: function (error, element) {
          // do nothing
        },
        invalidHandler: function (event, validator) {
          // do nothing
        },
      });
      //
      for (var o in window.MyGlobalVariables.validationSetting.methods) {
        if (window.MyGlobalVariables.validationSetting.methods.hasOwnProperty(o)) {
          $.validator.addMethod(
            o,
            window.MyGlobalVariables.validationSetting.methods[o],
            window.MyGlobalVariables.validationSetting.messages[o] || ''
          );
        }
      }
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

      // Lazy loader (pictures, videos, etc.)
      lazyFn: function (container) {
        if ($.fn.lazy) {
          $('.lazy').lazy({
            appendScroll: container && $(container).length ? $(container) : window,
            effect: "fadeIn",
            effectTime: 800,
            threshold: 50,
            // callback
            afterLoad: function (element) {
              $(element).css({'background': 'none'});
            }
          });
        }
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
         * @param [options]
         * @param [onCancelCallback]
         * @returns {boolean}
         */
        confirm: function (message, onOkCallback, options, onCancelCallback) {
          var res = false;
          onOkCallback = window.TheCore.isFunction(onOkCallback) ? onOkCallback : window.TheCore.noop;
          onCancelCallback = window.TheCore.isFunction(onCancelCallback) ? onCancelCallback : window.TheCore.noop;
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
                  onCancelCallback.call(window);
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
        if (!csrfSafeMethod(method)) {
          window.axios({
            method: 'GET',
            url: window.MyGlobalVariables.url.csrfToken,
          }).then(function (csrfRes) {
            // reset csrf token of axios
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfRes.data.csrfToken;

            _.unsafeRequest(url, method, successCallback, options, showError, errorCallback);
          }).catch(function () {
            _.toasts.toast(window.MyGlobalVariables.messages.request.tokenError, {
              type: 'warning',
            });
          });
        } else {
          _.unsafeRequest(url, method, successCallback, options, showError, errorCallback);
        }
      },

      /**
       * @param url
       * @param method
       * @param successCallback
       * @param options
       * @param showError
       * @param errorCallback
       */
      unsafeRequest: function (url, method, successCallback, options, showError, errorCallback) {
        var _ = this;
        options = typeof options === typeof {} ? options : {};
        showError = true === showError;
        errorCallback = window.TheCore.isFunction(errorCallback) ? errorCallback : window.TheCore.noop;

        window.axios($.extend(options, {
          method: method,
          url: url,
        })).then(function (response) {
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
        }).catch(function (error) {
          $('[data-is-preloader]').remove();

          errorCallback.call({catchErr: error});
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            if (showError) {
              _.toasts.toast(window.MyGlobalVariables.messages.request.error, {
                type: 'error',
              });
            }
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
         * @param defaultReturn
         * @param formKey
         * @param constraints
         * @param [validationSuccessCallback]
         * @param [validationErrorCallback]
         * @param [aliases]
         * @param [check]
         */
        submitForm: function (defaultReturn, formKey, constraints, validationSuccessCallback, validationErrorCallback, aliases, check) {
          var
            self = this,
            //-----
            form,
            formValues,
            formErrors;

          if (!window.TheCore.isBoolean(defaultReturn)) {
            check = aliases;
            aliases = validationErrorCallback;
            validationErrorCallback = validationSuccessCallback;
            validationSuccessCallback = constraints;
            constraints = formKey;
            formKey = defaultReturn;
            defaultReturn = true;
          }

          validationSuccessCallback = window.TheCore.isFunction(validationSuccessCallback) ? validationSuccessCallback : null;
          validationErrorCallback = window.TheCore.isFunction(validationErrorCallback) ? validationErrorCallback : null;
          check = !(false === check);

          form = $(window.MyGlobalVariables.elements[formKey].form);

          if (form.length) {

            form.submit(function () {
              if (check) {
                try {
                  formValues = self.convertFormObjectNumbersToEnglish(self.collectFormValues(this), formKey);
                  // remap form values to its config name
                  var data = new FormData();
                  self.addToFormData(formValues, formKey, data);

                  // show form data in this way because it does not show
                  // in console.log
                  // for (var pair of data.entries()) {
                  //   console.log(pair[0] + ', ' + pair[1]);
                  // }

                  var
                    theConstraints = {},
                    formattedConstraints;

                  formattedConstraints = self.formatConstraints(constraints, window.MyGlobalVariables.elements[formKey].inputs);
                  theConstraints.rules = formattedConstraints.rules;
                  theConstraints.messages = self.replaceFormAliases(formattedConstraints.messages, aliases);
                  theConstraints.showErrors = function (errorMap, errorList) {
                    formErrors = errorMap;
                  };

                  var validator = form.validate(theConstraints);
                  var isValid = validator.form();
                  validator.resetForm();

                  if (isValid) {
                    return validationSuccessCallback ? validationSuccessCallback.apply(null, [data]) : true;
                  }
                  return validationErrorCallback ? validationErrorCallback.apply(null, [formErrors]) : true;
                } catch (e) {
                  console.log(e)
                  return defaultReturn;
                }
              }
            });
          }
        },

        /**
         * @param formValues
         * @param formKey
         * @param formData
         */
        addToFormData: function (formValues, formKey, formData) {
          var val;

          for (var o in formValues) {
            if (formValues.hasOwnProperty(o)) {
              val = formValues[o];
              val = !window.TheCore.isDefined(val) ? '' : val;

              if (window.TheCore.isDefined(window.MyGlobalVariables.elements[formKey].inputs[o])) {
                if (window.TheCore.isArray(val)) {
                  for (let i of val) {
                    formData.append(window.MyGlobalVariables.elements[formKey].inputs[o], i);
                  }
                } else {
                  formData.append(window.MyGlobalVariables.elements[formKey].inputs[o], val);
                }
              } else {
                formData.append(o, val);
              }
            }
          }
        },

        /**
         * @param constraints
         * @param inputs
         * @return {{messages, rules}}
         */
        formatConstraints: function (constraints, inputs) {
          var
            rules = {},
            messages = {};

          for (var o in constraints) {
            if (constraints.hasOwnProperty(o) && inputs.hasOwnProperty(o)) {
              rules[inputs[o]] = constraints[o].rules;
              messages[inputs[o]] = constraints[o].messages;
            }
          }

          return {
            rules,
            messages,
          };
        },

        /**
         * @param errors
         * @param mapping
         * @return {*}
         */
        replaceFormAliases: function (errors, mapping) {
          for (var attrName in errors) {
            if (errors.hasOwnProperty(attrName)) {
              for (var constraint in errors[attrName]) {
                if (errors[attrName].hasOwnProperty(constraint)) {
                  for (var alias in mapping) {
                    if (mapping.hasOwnProperty(alias)) {
                      errors[attrName][constraint] = errors[attrName][constraint].replace(
                        alias,
                        mapping[alias]);
                    }
                  }
                }
              }
            }
          }

          return errors;
        },

        /**
         * @param obj
         * @param formKey
         * @return {object}
         */
        convertFormObjectNumbersToEnglish: function (obj, formKey) {
          var
            self = this,
            keyFromVal,
            newFormValues = {};
          for (var prop in obj) {
            // skip loop if the property is from prototype
            if (!obj.hasOwnProperty(prop)) continue;

            // get key from value and if its null return false
            keyFromVal = window.TheCore.getKeyByValue(window.MyGlobalVariables.elements[formKey].inputs, prop);
            if (null !== keyFromVal) {
              if (window.TheCore.isArray(obj[prop])) {
                if (!window.TheCore.isArray(newFormValues[keyFromVal])) {
                  newFormValues[keyFromVal] = [];
                }

                for (let i of obj[prop]) {
                  newFormValues[keyFromVal].push(self.convertFormValueNumbersToEnglish(i));
                }
              } else {
                newFormValues[keyFromVal] = self.convertFormValueNumbersToEnglish(obj[prop]);
              }
            }
          }

          return newFormValues;
        },

        /**
         * @param value
         * @return {*}
         */
        convertFormValueNumbersToEnglish: function (value) {
          return window.TheCore.toEnglishNumbers(value);
        },

        /**
         * @param errors
         */
        showFormErrors: function (errors) {
          if (errors && !window.TheCore.isEmptyObject(errors)) {
            var shop = new ShopBase();
            var s, p, a;

            s = '<h5 class="text-white">' + 'خطا در اعتبار سنجی فرم' + '</h5><hr>';
            for (p in errors) {
              if (errors.hasOwnProperty(p)) {
                if (window.TheCore.isObject(errors[p]) || window.TheCore.isArray(errors[p])) {
                  for (a of errors[p]) {
                    s += a + "<br>";
                  }
                } else {
                  s += errors[p];
                }
                s += "<br>";
              }
            }
            shop.toasts.toast(s, {
              type: 'error',
              layout: 'center',
              modal: true,
              closeWith: ['click', 'backdrop'],
              timeout: null,
            });
          }
        },

        /**
         * This returns an object with all the values of the form.
         * It uses the input name as key and the value as value
         * So for example this:
         * <input type="text" name="email" value="foo@bar.com" />
         * would return:
         * {email: "foo@bar.com"}
         *
         * @external From validate.js plugin
         *
         * @param form
         * @param options
         */
        collectFormValues: function (form, options) {
          var self = this;
          var values = {}
            , i
            , j
            , input
            , inputs
            , option
            , value;
          var name, tmpValues;

          if (window.TheCore.isJqueryElement(form)) {
            form = form[0];
          }

          if (!form) {
            return values;
          }

          options = options || {};

          inputs = form.querySelectorAll("input[name], textarea[name]");
          for (i = 0; i < inputs.length; ++i) {
            input = inputs.item(i);
            if (window.TheCore.isDefined(input.getAttribute("data-ignored"))) {
              continue;
            }

            name = input.name.replace(/\./g, "\\\\.");
            value = self.sanitizeFormValue(input.value, options);
            if (input.type === "number") {
              value = value ? +value : null;
            } else if (input.type === "checkbox") {
              if (input.attributes.value) {
                if (!input.checked) {
                  value = values[name][i] || values[name] || null;
                }
              } else {
                value = input.checked;
              }
            } else if (input.type === "radio") {
              if (!input.checked) {
                value = values[name][i] || values[name] || null;
              }
            }

            // support array names like 'x[]' if there is more than one in page
            if ((new RegExp('[\\d\\w\\-_]*\\[]', 'g')).test(name)) {
              tmpValues = undefined;
              if (window.TheCore.isArray(values[name])) {
                tmpValues = $.extend(true, [], values[name]);
              }
              values[name] = [];

              if (tmpValues && window.TheCore.isArray(tmpValues)) {
                tmpValues.map(function (v) {
                  values[name].push(v);
                });
              } else if (typeof tmpValues !== 'undefined') {
                values[name].push(tmpValues);
              }

              values[name].push(value);
            } else {
              values[name] = value;
            }
          }

          inputs = form.querySelectorAll("select[name]");
          for (i = 0; i < inputs.length; ++i) {
            input = inputs.item(i);
            if (window.TheCore.isDefined(input.getAttribute("data-ignored"))) {
              continue;
            }

            if (input.multiple) {
              value = [];
              for (j in input.options) {
                option = input.options[j];
                if (option && option.selected) {
                  value.push(self.sanitizeFormValue(option.value, options));
                }
              }
            } else {
              var _val = typeof input.options[input.selectedIndex] !== 'undefined' ? input.options[input.selectedIndex].value : /* istanbul ignore next */ '';
              value = self.sanitizeFormValue(_val, options);
            }

            name = input.name.replace(/\./g, "\\\\.");

            // support array names like 'x[]' if there is more than one in page
            if ((new RegExp('[\\d\\w\\-_]*\\[]', 'g')).test(name)) {
              tmpValues = undefined;
              if (window.TheCore.isArray(values[name])) {
                tmpValues = $.extend(true, [], values[name]);
              }
              values[name] = [];

              if (tmpValues && window.TheCore.isArray(tmpValues)) {
                tmpValues.map(function (v) {
                  values[name].push(v);
                });
              } else if (typeof tmpValues !== 'undefined') {
                values[name].push(tmpValues);
              }

              values[name].push(value);
            } else {
              values[name] = value;
            }
          }

          return values;
        },

        sanitizeFormValue: function (value, options) {
          if (options.trim && window.TheCore.isString(value)) {
            value = value.trim();
          }

          if (options.nullify !== false && value === "") {
            return null;
          }
          return value;
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
        var _ = this, successCb;
        options = typeof options === typeof {} ? options : {};
        successCb = function () {
          if (window.TheCore.isFunction(successCallback)) {
            successCallback.apply(_, [this]);
          }
          _.toasts.toast(this.data, {
            type: 'success',
          });
        };

        _.toasts.confirm(MyGlobalVariables.messages.confirm, function () {
          _.request(url, 'delete', successCb, options, showError, errorCallback);
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
        if (province_id) {
          _.request(window.MyGlobalVariables.url.pages.get.city + '/' + province_id, 'get', successCallback, null, false);
        }
      },

      /**
       * @param provincesSelect
       */
      loadProvinces: function (provincesSelect) {
        if (provincesSelect.length) {
          this.getProvinces(function () {
            var _, $this, newOption, i, len;
            var currProvince;
            _ = this;
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

              var target = $this.attr('data-city-select-target');
              if ($(target).length) {
                $(target).trigger('change');
              }
            });
          });
        }
      },

      /**
       * @param citiesSelect
       * @param id
       */
      loadCities: function (citiesSelect, id) {
        citiesSelect.find('.__removable_city_option').remove();
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
              // trigger on change if there is a current city id
              if (currCity) {
                citiesSelect.trigger('change');
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
      citiesSelect;

    //---------------------------------------------------------------
    // CSRF TOKEN LOAD
    //---------------------------------------------------------------
    shop.request(variables.url.csrfToken, 'GET');

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
    provincesSelect.each(function () {
      $(this).on('change' + variables.namespace, function () {
        var id;
        $this = $(this);
        id = $this.find(':selected').val();
        if (id) {
          citiesSelect = $($this.attr('data-city-select-target'));
          shop.loadCities(citiesSelect, id);
        }
      });
    });

    //---------------------------------------------------------------
    // LOAD PROVINCES AND SELECT CURRENT IF NEEDED
    //---------------------------------------------------------------
    shop.loadProvinces(provincesSelect);
  });
})(jQuery);
