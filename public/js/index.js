(function ($) {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        address: {
            get: '/ajax/user/address/get',
            add: '/ajax/user/address/add',
            edit: '/ajax/user/address/edit',
            remove: '/ajax/user/address/remove',
            all: '/ajax/user/address/all',
        },
        returnOrder: {
            items: '/ajax/user/return-order/add',
        },
    });
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
        cart: {
            container: '#__cart_main_container',
            addBtn: '.__add_to_cart_btn',
            removeBtn: '.__remove_from_cart_btn',
        },
        register: {
            form: '#__form_register',
            inputs: {
                username: 'inp-register-username',
                captcha: 'inp-register-captcha',
                ruleCheckbox: 'inp-register-terms-chk',
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
        addAddress: {
            form: '#__form_add_address',
            inputs: {
                fullName: 'inp-add-address-full-name',
                mobile: 'inp-add-address-mobile',
                province: 'inp-add-address-province',
                city: 'inp-add-address-city',
                postalCode: 'inp-add-address-postal-code',
                address: 'inp-add-address-address',
            },
        },
        editAddress: {
            form: '#__form_edit_address',
            inputs: {
                fullName: 'inp-edit-address-full-name',
                mobile: 'inp-edit-address-mobile',
                province: 'inp-edit-address-province',
                city: 'inp-edit-address-city',
                postalCode: 'inp-edit-address-postal-code',
                address: 'inp-edit-address-address',
            },
        },
        changeUserInfo: {
            form: '#__form_change_info',
            inputs: {
                firstName: 'inp-info-first-name',
                lastName: 'inp-info-last-name',
                email: 'inp-info-email',
                nationalNum: 'inp-info-national-num',
                shabaNum: 'inp-info-shaba-num',
            },
        },
        changeUserPassword: {
            form: '#__form_change_password',
            inputs: {
                prevPassword: 'inp-pass-prev-password',
                password: 'inp-pass-password',
                rePassword: 'inp-pass-re-password',
            },
        },
        recoverType: {
            form: '#__form_change_recover_type',
            inputs: {
                recoverType: 'inp-recover-type',
            },
        },
        userEditComment: {
            form: '#__form_edit_comment',
            inputs: {
                message: 'inp-comment-message',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            register: {
                ruleCheckbox: {
                    rules: {
                        required: true,
                    },
                    messages: {
                        required: 'نیاز به موافقت با شرایط و سیاست سایت است.',
                    },
                },
                password: {
                    rules: {
                        requiredNotEmpty: true,
                        minlength: 8,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کلمه عبور اجباری می‌باشد.',
                        minlength: 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    },
                },
                confirmPassword: {
                    rules: {
                        equalTo: '[name="inp-register-password"]',
                    },
                    messages: {
                        equalTo: 'تکرار کلمه عبور با کلمه عبور یکسان نمی‌باشد.',
                    },
                },
            },
            forgetStep3: {
                password: {
                    rules: {
                        requiredNotEmpty: true,
                        minlength: 8,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کلمه عبور اجباری می‌باشد.',
                        minlength: 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    },
                },
                confirmPassword: {
                    rules: {
                        equalTo: '[name="inp-forget-new-password"]',
                    },
                    messages: {
                        equalTo: 'تکرار کلمه عبور با کلمه عبور یکسان نمی‌باشد.',
                    },
                },
            },
            contactUs: {
                subject: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد موضوع را خالی نگذارید.',
                        maxlength: 'فیلد موضوع باید حداکثر دارای ۲۵۰ کاراکتر باشد.',
                    },
                },
                message: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد پیام خالی نگذارید.',
                    },
                },
            },
            complaint: {
                subject: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد موضوع را خالی نگذارید.',
                        maxlength: 'فیلد موضوع باید حداکثر دارای ۲۵۰ کاراکتر باشد.',
                    },
                },
                message: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد پیام خالی نگذارید.',
                    },
                },
            },
            addAddress: {
                province: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /^\d{1,10}$/,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کد پستی را خالی نگذارید.',
                        format: 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            editAddress: {
                province: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /^\d{1,10}$/,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کد پستی را خالی نگذارید.',
                        format: 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            changeUserInfo: {
                nationalNum: {
                    rules: {
                        format: /^[0-9]*$/,
                    },
                    messages: {
                        format: 'کد ملی باید از نوع عددی باشد.',
                    },
                },
                shabaNum: {
                    rules: {
                        format: /^[0-9]*$/,
                    },
                    messages: {
                        format: 'کد شبا باید از نوع عددی باشد.',
                    },
                },
            },
            changeUserPassword: {
                prevPassword: {
                    rules: {
                        requiredNotEmpty: true,
                        minlength: 8,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کلمه عبور قبلی اجباری می‌باشد.',
                        minlength: 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    },
                },
                password: {
                    rules: {
                        requiredNotEmpty: true,
                        minlength: 8,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کلمه عبور جدید اجباری می‌باشد.',
                        minlength: 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    },
                },
                rePassword: {
                    password: {
                        rules: {
                            requiredNotEmpty: true,
                            equalTo: '[name="password"]',
                        },
                        messages: {
                            requiredNotEmpty: 'فیلد تکرار کلمه عبور اجباری می‌باشد.',
                            equalTo: 'تکرار کلمه عبور با کلمه عبور یکسان نمی‌باشد.',
                        },
                    },
                },
            },
            recoverType: {
                recoverType: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /^[0-9]*$/,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد نوع بازگردانی کلمه عبور را انتخاب کنید.',
                        format: 'نوع بازگردانی کلمه عبور باید از نوع عددی باشد.',
                    },
                },
            },
            userEditComment: {
                message: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد متن نظر اجباری می‌باشد.',
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
                    $('<div class="preloader preloader-opacity" data-is-preloader="true" id="' + id + '" />').append(
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

            showLoaderInsideElement: function (el) {
                $(el)
                    .addClass('inside-loader-parent')
                    .append(
                        $('<div class="inside-loader-container" />')
                            .append($('<div class="inside-loader" />'))
                    );
            },

            hideLoaderFromInsideElement: function (el) {
                $(el)
                    .removeClass('inside-loader-parent')
                    .find('.inside-loader-container')
                    .fadeOut(function () {
                        $(this).remove();
                    });
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
            createLoader = true,
            //
            editAddrId = null,
            //
            addressesContainer;

        shop = new window.TheShop();

        addressesContainer = $('.address-elements-container');

        //-----
        constraints = {
            newsletter: {
                mobile: variables.validation.common.mobile,
            },
            register: {
                username: variables.validation.common.mobile,
                captcha: variables.validation.common.captcha,
                ruleCheckbox: variables.validation.constraints.register.ruleCheckbox,
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
            addAddress: {
                name: variables.validation.common.name,
                mobile: variables.validation.common.mobile,
                province: variables.validation.constraints.addAddress.province,
                city: variables.validation.constraints.addAddress.city,
                postalCode: variables.validation.constraints.addAddress.postalCode,
                address: variables.validation.constraints.addAddress.address,
            },
            editAddress: {
                name: variables.validation.common.name,
                mobile: variables.validation.common.mobile,
                province: variables.validation.constraints.editAddress.province,
                city: variables.validation.constraints.editAddress.city,
                postalCode: variables.validation.constraints.editAddress.postalCode,
                address: variables.validation.constraints.editAddress.address,
            },
            changeUserInfo: {
                firstName: variables.validation.common.name,
                lastName: variables.validation.common.lastName,
                email: variables.validation.common.email,
                nationalNum: variables.validation.constraints.changeUserInfo.nationalNum,
                shabaNum: variables.validation.constraints.changeUserInfo.shabaNum,
            },
            changeUserPassword: {
                prevPassword: variables.validation.constraints.changeUserPassword.prevPassword,
                password: variables.validation.constraints.changeUserPassword.password,
                rePassword: variables.validation.constraints.changeUserPassword.rePassword,
            },
            recoverType: {
                recoverType: variables.validation.constraints.recoverType.recoverType,
            },
            userEditComment: {
                message: variables.validation.constraints.userEditComment.message,
            },
        };

        function reloadUserAddresses() {
            // append created address to address container
            shop.showLoaderInsideElement(addressesContainer);
            shop.request(variables.url.address.all, 'get', function () {
                shop.hideLoaderFromInsideElement(addressesContainer);
                addressesContainer.html(this.data);
                setTimeout(function () {
                    reAssignThings();
                }, 100);
            }, {}, false, function () {
                shop.hideLoaderFromInsideElement(addressesContainer);
            })
        }

        /**
         * Delete anything from a specific url
         *
         * @param btn
         * @param [onSuccess]
         */
        function deleteOperation(btn, onSuccess) {
            var url, id;
            url = $(btn).attr('data-remove-url');
            id = $(btn).attr('data-remove-id');

            if (url && id) {
                shop.deleteItem(url + '/' + id, function () {
                    if (core.types.function === typeof onSuccess) {
                        onSuccess.apply(this);
                    }
                }, {}, true);
            }
        }

        function reAssignThings() {
            // delete button click event
            $('.__item_remover_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    var $this = $(this);
                    deleteOperation($this, function () {
                        $this.closest('tr').fadeOut(300, function () {
                            if (1 === $(this).parent().find('tr').length) {
                                $(this).parent()
                                    .append(
                                        $('<tr/>')
                                            .append(
                                                $('<td class="text-center p-2" colspan="' +
                                                    $(this).parent().find('tr').first().find('td').length +
                                                    '"/>')
                                                    .html('هیچ موردی وجود ندارد')
                                            )
                                    );
                            }
                            //
                            $(this).remove();
                        });
                    });
                });

            // custom delete button click event
            $('.__item_custom_remover_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    var $this = $(this);
                    deleteOperation($this, function () {
                        $this.closest('.remove-element-item').fadeOut(300, function () {
                            if (1 === $(this).parent().find('.remove-element-item').length) {
                                $(this).parent().append($('<p class="text-center border p-3"/>')
                                    .html($('<i class="icon-info icon-2x d-block mb-1"/>'))
                                    .append('هیچ موردی یافت نشد')
                                );
                            }
                            //
                            $(this).remove();
                        });
                    });
                });

            $('.__send_data_through_request').each(function () {
                var _, url, status;

                _ = $(this);
                _.off('click' + variables.namespace)
                    .on('click' + variables.namespace, function () {
                        var $this = $(this);
                        url = $this.attr('data-internal-request-url');
                        status = $this.attr('data-internal-request-status');

                        if (url && status) {
                            shop.toasts.confirm(null, function () {
                                shop.request(url, 'post', function () {
                                    var _ = this;
                                    if (_.type === variables.api.types.warning) {
                                        shop.toasts.toast(_.data);
                                    } else {
                                        shop.toasts.toast(_.data, {
                                            type: variables.toasts.types.success,
                                        });
                                    }
                                }, {
                                    data: {
                                        'status': status,
                                    },
                                }, true);
                            });
                        }
                    });
            });

            $('.edit-element-item')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editAddressBtnClick(this);
                });
        }

        if ($.fn.theiaStickySidebar) {
            $('#__theia_sticky_sidebar').theiaStickySidebar({
                containerSelector: '#__theia_sticky_sidebar_container',
                additionalMarginTop: 90,
                additionalMarginBottom: 20,
            });
        }

        //-----
        reAssignThings();
        //-----

        //---------------------------------------------------------------
        // Events
        //---------------------------------------------------------------

        /**
         *
         * @param btn
         */
        function editAddressBtnClick(btn) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#__user_addr_edit_modal');
            // clear element after each call
            $(variables.elements.editAddress.form).get(0).reset();
            if (id && editModal.length) {
                shop.request(variables.url.address.get + '/' + id, 'get', function () {
                    var _ = this;
                    var provincesSelect = $('select[name="' + variables.elements.editAddress.inputs.province + '"]'),
                        citiesSelect = $(provincesSelect.attr('data-city-select-target'));
                    if (core.objSize(_.data) && provincesSelect.length && citiesSelect.length) {
                        editAddrId = id;
                        //-----
                        shop.loadProvinces(provincesSelect.attr('data-current-province', _.data['province_id']));
                        shop.loadCities(citiesSelect.attr('data-current-city', _.data['city_id']), _.data['province_id']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.fullName + '"]').val(_.data['full_name']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.mobile + '"]').val(_.data['mobile']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.postalCode + '"]').val(_.data['postal_code']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.address + '"]').val(_.data['address']);
                    }
                });
            }
        }

        //---------------------------------------------------------------
        // CHECK SCROLL TO ELEMENT
        //---------------------------------------------------------------
        var
            hashval = window.location.hash.substr(1),
            elementsHash = [
                '__contact_form_container',
                '__register_form_container',
                '__forget_form_container',
                //-----
                'Reviews',
            ],
            modalsHash = [],
            tabsHash = [
                'changeInfo',
                'changePassword',
                'changeOther',
                //-----
                'Reviews',
            ];

        if ($.inArray(hashval, elementsHash) !== -1) {
            core.scrollTo('#' + hashval, 140);
        }
        if ($.inArray(hashval, tabsHash) !== -1) {
            $('[data-toggle="tab"][href="' + '#' + hashval + '"]').tab('show');
        }

        //---------------------------------------------------------------
        // REGISTER FORM - STEP 1
        //---------------------------------------------------------------
        shop.forms.submitForm('register', constraints.register, function () {
            loaderId = shop.showLoader();
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // REGISTER FORM - STEP 3
        //---------------------------------------------------------------
        shop.forms.submitForm('registerStep3', constraints.registerStep3, function () {
            loaderId = shop.showLoader();
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // FORGET PASSWORD FORM - STEP 1
        //---------------------------------------------------------------
        shop.forms.submitForm('forgetStep1', constraints.forgetStep1, function () {
            loaderId = shop.showLoader();
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // FORGET PASSWORD FORM - STEP 3
        //---------------------------------------------------------------
        shop.forms.submitForm('forgetStep3', constraints.forgetStep3, function () {
            loaderId = shop.showLoader();
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
                $(variables.elements.newsletter.form).get(0).reset();
                $(variables.elements.newsletter.form).find('input[type="hidden"]').val('');
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
            loaderId = shop.showLoader();
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
            loaderId = shop.showLoader();
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
        wishListBtn = $('.add_wishlist');
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
                shop.request(variables.url.products.add.wishList + '/' + id, 'post', function () {
                    shop.hideLoader(loaderId);
                    var type, message;
                    type = this.type;
                    message = this.data;
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

        //---------------------------------------------------------------
        // ADD ADDRESS
        //---------------------------------------------------------------
        shop.forms.submitForm('addAddress', constraints.addAddress, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = shop.showLoader();
            }
            shop.request(variables.url.address.add, 'post', function () {
                shop.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addAddress.form).get(0).reset();
                $(variables.elements.addAddress.form).find('input[type="hidden"]').val('');
                // load province and city again
                var p = $('select[name="' + variables.elements.addAddress.inputs.province + '"]');
                var c = $('select[name="' + variables.elements.addAddress.inputs.city + '"]');
                p.removeAttr('data-current-province');
                shop.loadProvinces(p);
                c.removeAttr('data-current-city');
                shop.loadCities(c, -1);
                //-----
                shop.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
                reloadUserAddresses();
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                shop.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'نام',
        });

        //---------------------------------------------------------------
        // EDIT ADDRESS
        //---------------------------------------------------------------
        shop.forms.submitForm('editAddress', constraints.editAddress, function (values) {
            if (editAddrId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = shop.showLoader();
                }
                shop.request(variables.url.address.edit + '/' + editAddrId, 'post', function () {
                    shop.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editAddress.form).get(0).reset();
                    $(variables.elements.editAddress.form).find('input[type="hidden"]').val('');
                    // load province and city again
                    var p = $('select[name="' + variables.elements.editAddress.inputs.province + '"]');
                    var c = $('select[name="' + variables.elements.editAddress.inputs.city + '"]');
                    p.removeAttr('data-current-province');
                    shop.loadProvinces(p);
                    c.removeAttr('data-current-city');
                    shop.loadCities(c, -1);
                    editAddrId = null;
                    //-----
                    shop.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                    reloadUserAddresses();
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    shop.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'نام',
        });

        //---------------------------------------------------------------
        // CHANGE USER INFO FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('changeUserInfo', constraints.changeUserInfo, function () {
            loaderId = shop.showLoader();
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'نام',
            '{{last-name}}': 'نام خانوادگی',
        });

        //---------------------------------------------------------------
        // CHANGE USER PASSWORD FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('changeUserPassword', constraints.changeUserPassword, function () {
            loaderId = shop.showLoader();
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // CHANGE USER OTHER SETTING FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('recoverType', constraints.recoverType, function () {
            loaderId = shop.showLoader();
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // EDIT USER COMMENT FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('userEditComment', constraints.userEditComment, function () {
            loaderId = shop.showLoader();
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });
    });
})(jQuery);
