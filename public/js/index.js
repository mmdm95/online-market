(function ($) {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.elements = {
        captcha: {
            mainContainer: '.__captcha_main_container',
            container: '.__captcha_container',
            refreshBtn: '.__captcha_regenerate_btn',
        },
        cart: {
            container: '#__cart_main_container',
            addBtn: '.__add_to_cart_btn',
        },
        register: {
            form: '#__form_register',
            inputs: {
                username: 'inp-register-username',
                captcha: 'inp-register-captcha',
            },
        },
        registerStep3: {
            form: '#__form_register_step3',
            inputs: {
                password: 'inp-register-password',
                confirmPassword: 'inp-register-re-password',
            },
        },
        forgetStep1: {
            form: '#__forget_form_step1',
            inputs: {
                mobile: 'inp-forget-mobile',
            },
        },
        forgetStep3: {
            form: '#__forget_form_step3',
            inputs: {
                password: 'inp-forget-new-password',
                confirmPassword: 'inp-forget-new-re-password',
            },
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
    };
    window.MyGlobalVariables.validation = {
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
            register: {
                password: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کلمه عبور اجباری می‌باشد.',
                    },
                    length: {
                        minimum: 8,
                        message: '^' + 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    }
                },
                confirmPassword: {
                    equality: "password"
                },
            },
            forgetStep3: {
                password: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کلمه عبور اجباری می‌باشد.',
                    },
                    length: {
                        minimum: 8,
                        message: '^' + 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    }
                },
                confirmPassword: {
                    equality: "password"
                },
            },
            contactUs: {
                subject: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد موضوع را خالی نگذارید.',
                    },
                    length: {
                        maximum: 250,
                        message: '^' + 'فیلد موضوع باید حداکثر دارای ۲۵۰ کاراکتر باشد.',
                    }
                },
                message: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد پیام خالی نگذارید.',
                    },
                },
            },
        },
    };

    var core, variables;

    core = window.TheCore;
    variables = window.MyGlobalVariables;

    /**
     * Make Shop class global to window
     * @type {TheShop}
     */
    window.TheShop = (function (_super, c) {
        // inherit from Base class
        c.extend(Shop, _super);

        // now the class definition
        function Shop() {
            _super.call(this);
        }

        $.extend(Shop.prototype, {});

        return Shop;
    })(window.TheShopBase, core);

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var
            shop;

        shop = new window.TheShop();

        //---------------------------------------------------------------
        // CHECK SCROLL TO ELEMENT
        //---------------------------------------------------------------
        var
            hashval = window.location.hash.substr(1),
            elementsHash = [
                '__contact_form_container',
                '__register_form_container',
                '__forget_form_container',
            ],
            modalsHash = [];

        if ($.inArray(hashval, elementsHash) !== -1) {
            core.scrollTo('#' + hashval, 140);
        }

        //---------------------------------------------------------------
        // REGISTER FORM - STEP 1
        //---------------------------------------------------------------
        shop.forms.submitForm('register', 'home', function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false
        });

        //---------------------------------------------------------------
        // REGISTER FORM - STEP 3
        //---------------------------------------------------------------
        shop.forms.submitForm('registerStep3', 'home', function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false
        });

        //---------------------------------------------------------------
        // FORGET PASSWORD FORM - STEP 1
        //---------------------------------------------------------------
        shop.forms.submitForm('forgetStep1', 'home', function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false
        });

        //---------------------------------------------------------------
        // FORGET PASSWORD FORM - STEP 3
        //---------------------------------------------------------------
        shop.forms.submitForm('forgetStep3', 'home', function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false
        });

        //---------------------------------------------------------------
        // NEWSLETTER FORM
        //---------------------------------------------------------------

        shop.forms.submitForm('newsletter', 'home', function (values) {
            // do ajax
            // ...
            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false
        });

        //---------------------------------------------------------------
        // CONTACT US FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('contactUs', 'home', function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false
        });
    });
})(jQuery);
