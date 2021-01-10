(function ($) {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
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
        complaint: {
            form: '#__form_complaint',
            inputs: {
                name: 'inp-complaint-name',
                email: 'inp-complaint-email',
                mobile: 'inp-complaint-mobile',
                subject: 'inp-complaint-subject',
                message: 'inp-complaint-message',
                captcha: 'inp-complaint-captcha',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend({}, window.MyGlobalVariables.validation, {
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
            complaint: {
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
    });

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

        $.extend(Shop.prototype, {
            showLoader: function () {
                var id = core.idGenerator('loader');
                $('body').append(
                    $('<div class="preloader preloader-opacity" id="' + id + '" />').append(
                        $('<div class="lds-ellipsis" />')
                            .append($('<span/>'))
                            .append($('<span/>'))
                            .append($('<span/>'))
                    )
                );
                return id;
            },
            hideLoader: function (id) {
                if (id) {
                    id = $('#' + id);
                    if (id.length) {
                        id.fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                }
            },
        });

        return Shop;
    })(window.TheShopBase, core);

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var
            shop,
            constraints,
            //-----
            loaderId,
            wishListBtn,
            //-----
            createLoader = true;

        shop = new window.TheShop();

        //-----
        constraints = {
            newsletter: {
                mobile: variables.validation.common.mobile,
            },
            register: {
                username: variables.validation.common.mobile,
                captcha: variables.validation.common.captcha,
            },
            registerStep3: {
                password: variables.validation.constraints.register.password,
                confirmPassword: variables.validation.constraints.register.confirmPassword,
            },
            forgetStep1: {
                mobile: variables.validation.common.mobile,
            },
            forgetStep3: {
                password: variables.validation.constraints.forgetStep3.password,
                confirmPassword: variables.validation.constraints.forgetStep3.confirmPassword,
            },
            contactUs: {
                name: variables.validation.common.name,
                email: variables.validation.common.email,
                mobile: variables.validation.common.mobile,
                subject: variables.validation.constraints.contactUs.subject,
                message: variables.validation.constraints.contactUs.message,
                captcha: variables.validation.common.captcha,
            },
            complaint: {
                name: variables.validation.common.name,
                email: variables.validation.common.email,
                mobile: variables.validation.common.mobile,
                subject: variables.validation.constraints.complaint.subject,
                message: variables.validation.constraints.complaint.message,
                captcha: variables.validation.common.captcha,
            },
        };

        wishListBtn = $('.add_wishlist');

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
        shop.forms.submitForm('register', constraints.register, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // REGISTER FORM - STEP 3
        //---------------------------------------------------------------
        shop.forms.submitForm('registerStep3', constraints.registerStep3, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // FORGET PASSWORD FORM - STEP 1
        //---------------------------------------------------------------
        shop.forms.submitForm('forgetStep1', constraints.forgetStep1, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // FORGET PASSWORD FORM - STEP 3
        //---------------------------------------------------------------
        shop.forms.submitForm('forgetStep3', constraints.forgetStep3, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // NEWSLETTER FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('newsletter', constraints.newsletter, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = shop.showLoader();
            }
            shop.request(variables.url.newsletter.add, 'post', function () {
                shop.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.newsletter.form).reset();
                shop.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
            });
            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // CONTACT US FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('contactUs', constraints.contactUs, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'نام',
        });

        //---------------------------------------------------------------
        // COMPLAINT FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('complaint', constraints.complaint, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'نام',
        });

        //---------------------------------------------------------------
        // ADD TO WISH LIST
        //---------------------------------------------------------------
        wishListBtn.on('click' + variables.namespace, function (e) {
            e.preventDefault();

            var $this, id;
            $this = $(this);
            id = $(this).attr('data-product-id');
            if (id) {
                if (createLoader) {
                    createLoader = false;
                    loaderId = shop.showLoader();
                }
                shop.request(variables.url.products.add.wishList, 'post', function () {
                    shop.hideLoader(loaderId);
                    var type, message;
                    type = this.data.type;
                    message = this.data.message;
                    if (type === variables.api.types.success) {
                        $this.addClass('active');
                    } else if (type === variables.api.types.info) {
                        $this.removeClass('active');
                    }
                    shop.toasts.toast(message, {
                        type: type,
                    });
                    createLoader = true;
                }, {
                    data: {
                        product_id: id,
                    },
                }, true, function () {
                    createLoader = true;
                });
            }
        });
    });
})(jQuery);
