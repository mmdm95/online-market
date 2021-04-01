(function ($) {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        address: {
            get: '/ajax/user/address/get',
            add: '/ajax/user/address/add',
            edit: '/ajax/user/address/edit',
            remove: '/ajax/user/address/remove',
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
                fullName: 'inp-address-add-full-name',
                mobile: 'inp-address-add-mobile',
                province: 'inp-address-add-province',
                city: 'inp-address-add-city',
                postalCode: 'inp-address-add-postal-code',
                address: 'inp-address-add-address',
            },
        },
        editAddress: {
            form: '#__form_edit_address',
            inputs: {
                fullName: 'inp-address-edit-full-name',
                mobile: 'inp-address-edit-mobile',
                province: 'inp-address-edit-province',
                city: 'inp-address-edit-city',
                postalCode: 'inp-address-edit-postal-code',
                address: 'inp-address-edit-address',
            },
        },
        changeUserInfo: {
            form: '#__form_change_info',
            inputs: {
                firstName: 'inp-info-first-name',
                lastName: 'inp-info-last-name',
                email: 'inp-info-email',
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
            addAddress: {
                province: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کد پستی را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^\d{1,10}$/,
                        message: '^' + 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            editAddress: {
                province: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کد پستی را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^\d{1,10}$/,
                        message: '^' + 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            changeUserInfo: {
                shabaNum: {
                    format: {
                        pattern: /^[0-9]*$/,
                        message: '^' + 'کد شبا باید از نوع عددی باشد.',
                    },
                },
            },
            changeUserPassword: {
                prevPassword: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کلمه عبور قبلی اجباری می‌باشد.',
                    },
                    length: {
                        minimum: 8,
                        message: '^' + 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    }
                },
                password: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کلمه عبور جدید اجباری می‌باشد.',
                    },
                    length: {
                        minimum: 8,
                        message: '^' + 'فیلد کلمه عبور باید حداقل دارای ۸ کاراکتر باشد.',
                    }
                },
                rePassword: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد تکرار کلمه عبور اجباری می‌باشد.',
                    },
                    equality: "password",
                },
            },
            recoverType: {
                recoverType: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد نوع بازگردانی کلمه عبور را انتخاب کنید.',
                    },
                    format: {
                        pattern: /^[0-9]*$/,
                        message: '^' + 'نوع بازگردانی کلمه عبور باید از نوع عددی باشد.',
                    },
                },
            },
            userEditComment: {
                message: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد متن نظر اجباری می‌باشد.',
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
            editAddrId = null;

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
                shop.deleteItem(url + id, function () {
                    if (core.types.function === typeof onSuccess) {
                        onSuccess.apply(this);
                    }
                }, {}, true);
            }
        }

        if ($.fn.theiaStickySidebar) {
            $('#__theia_sticky_sidebar').theiaStickySidebar({
                containerSelector: '#__theia_sticky_sidebar_container',
                additionalMarginTop: 90,
                additionalMarginBottom: 20,
            });
        }

        // delete button click event
        $('.__item_remover_btn')
            .off('click' + variables.namespace)
            .on('click' + variables.namespace, function () {
                var $this = $(this);
                deleteOperation($this, function () {
                    $this.closest('tr').fadeOut(300, function () {
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
                        $(this).remove();
                    });
                });
            });

        $('.__send_data_through_request').each(function () {
            var $this, url, status;

            $this = $(this);
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
            editModal = $('#modal_form_address_edit');
            // clear element after each call
            $(variables.elements.editAddress.form).reset();
            if (id && editModal.length) {
                shop.request(variables.url.address.get + '/' + id, 'get', function () {
                    var _ = this;
                    var provincesSelect = $('select[name="' + variables.elements.editAddress.inputs.province + '"]'),
                        citiesSelect = $(provincesSelect.attr('data-city-select-target'));
                    if (_.data.length && provincesSelect.length && citiesSelect.length) {
                        editAddrId = id;
                        //-----
                        shop.loadProvinces(provincesSelect.attr('data-current-province', _.data['province_id']));
                        shop.loadCities(citiesSelect.attr('data-current-city', _.data['city_id']));
                        editModal.find('[name="' + variables.elements.editAddress.inputs.province + '"]').val(_.data['full_name']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.mobile + '"]').val(_.data['mobile']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.postalCode + '"]').val(_.data['postal_code']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.address + '"]').val(_.data['address']);
                    }
                });
            }
        }

        $('.edit-element-item').on('click' + variables.namespace, function () {
            editAddressBtnClick(this);
        });

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
                $(variables.elements.addAddress.form).reset();
                //-----
                shop.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
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
                    $(variables.elements.editAddress.form).reset();
                    // remove current id for province and city and reset current address id
                    $('select[name="' + variables.elements.editAddress.inputs.province + '"]').removeAttr('data-current-province');
                    $('select[name="' + variables.elements.editAddress.inputs.city + '"]').removeAttr('data-current-city');
                    editAddrId = null;
                    //-----
                    shop.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;

                    // append created address to address container
                    var addr = $('');
                    $('.address-elements-container').append(addr);
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
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // CHANGE USER OTHER SETTING FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('recoverType', constraints.recoverType, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // EDIT USER COMMENT FORM
        //---------------------------------------------------------------
        shop.forms.submitForm('userEditComment', constraints.userEditComment, function () {
            return true;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });
    });
})(jQuery);
