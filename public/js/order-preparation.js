(function ($) {
    'use strict';

    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        checkout: {
            check: '/ajax/checkout/check',
        }
    });
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
        checkoutCheck: {
            form: '#__checkout_payment_gateway',
            inputs: {
                firstName: 'fname',
                lastName: 'lname',
                receiverName: 'inp-addr-full-name',
                mobile: 'inp-addr-mobile',
                province: 'inp-addr-province',
                city: 'inp-addr-city',
                postalCode: 'inp-addr-postal-code',
                address: 'inp-addr-address',
                gateway: 'payment_method_option',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            checkoutCheck: {
                receiverName: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد نام گیرنده را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u,
                        message: '^' + ' نام گیرنده باید دارای حروف فارسی باشد.',
                    },
                },
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
        }
    });

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var
            shop = new window.TheShop(),
            cart = new window.TheCart(),
            core = window.TheCore,
            variables = window.MyGlobalVariables,
            constraints,
            //-----
            loaderId,
            createLoader,
            //-----
            addressChoosingContainer,
            addressChoosingBtn,
            //-----
            couponInp,
            couponApplyBtn,
            removeFromCartBtn,
            //-----
            shopCartTable,
            shopCartItemsInfoTable,
            shopCartInfoTable;

        //-----
        constraints = {
            checkoutCheck: {
                firstName: variables.validation.common.name,
                lastName: variables.validation.common.lastName,
                mobile: variables.validation.common.mobile,
                province: variables.validation.constraints.checkoutCheck.province,
                city: variables.validation.constraints.checkoutCheck.city,
                postalCode: variables.validation.constraints.checkoutCheck.postalCode,
                address: variables.validation.constraints.checkoutCheck.address,
            },
        };

        addressChoosingContainer = $('#__address_choise_container');
        addressChoosingBtn = $('#__address_choise_button');

        couponInp = $('.__coupon_field_inp');
        couponApplyBtn = $('.__apply_coupon');

        shopCartTable = $('.shop_cart_table');
        shopCartItemsInfoTable = $('.shop-cart-items-info-table');
        shopCartInfoTable = $('.shop-cart-info-table');

        function initializeItemQuantityChanger() {
            $('.plus').off('click').on('click', function () {
                var val, inp, max;
                inp = $(this).prev();
                val = inp.val();
                val = val && !isNaN(parseInt(val, 10)) ? parseInt(val, 10) : 0;
                max = inp.attr('data-max-cart-count');
                max = max && !isNaN(parseInt(max, 10)) ? parseInt(max, 10) : 0;
                if (val >= 0 && (0 !== max && val < max)) {
                    inp.val(+inp.val() + 1);
                }
            });
            $('.minus').off('click').on('click', function () {
                var val, inp;
                inp = $(this).next();
                val = inp.val();
                val = val && !isNaN(parseInt(val, 10)) ? parseInt(val, 10) : 0;
                if (val > 1) {
                    if (inp.val() > 1) inp.val(+inp.val() - 1);
                }
            });
            $('input[name="quantity"]').off('input').on('input', function () {
                var val, max;
                val = $(this).val();
                val = val && !isNaN(parseInt(val, 10)) ? parseInt(val, 10) : 0;
                max = $(this).attr('data-max-cart-count');
                max = max && !isNaN(parseInt(max, 10)) ? parseInt(max, 10) : 0;
                if (val <= 0) {
                    $(this).val(1);
                } else if ((0 !== max && val > max)) {
                    $(this).val(max);
                } else {
                    $(this).val(val);
                }
            });
        }

        function loadNPlaceCartItemsInfo() {
            if (shopCartItemsInfoTable.length) {
                shop.showLoaderInsideElement(shopCartItemsInfoTable);
                cart.getTotalCartItemsInfo(function () {
                    var self = this;
                    // put content in correct place
                    shopCartItemsInfoTable.html(self.data);
                    shop.hideLoaderFromInsideElement(shopCartItemsInfoTable);
                });
            }
        }

        function loadNPlaceCartInfo() {
            if (shopCartInfoTable.length) {
                shop.showLoaderInsideElement(shopCartInfoTable);
                cart.getTotalCartInfo(function () {
                    var self = this;
                    // put content in correct place
                    shopCartInfoTable.html(self.data);
                    shop.hideLoaderFromInsideElement(shopCartInfoTable);
                });
            }
        }

        function loadNPlaceCartItemsNInfo() {
            if (shopCartTable.length) {
                shop.showLoaderInsideElement(shopCartTable);
                cart.getNPlaceCart();
                cart.getCartItems(function () {
                    var self = this;
                    // put content in correct place
                    shopCartTable.html(self.data);

                    initializeItemQuantityChanger();
                    removeFromCartBtn = $(variables.elements.cart.removeBtn);
                    /**
                     * Cart remove button click event
                     */
                    removeFromCartBtn
                        .off('click' + variables.namespace + ' touchend' + variables.namespace)
                        .on('click' + variables.namespace + ' touchend' + variables.namespace, function (e) {
                            e.preventDefault();
                            cart.removeNPlaceCartFunctionality($(this));
                            loadNPlaceCartItemsNInfo();
                        });

                    shop.hideLoaderFromInsideElement(shopCartTable);
                    loadNPlaceCartItemsInfo();
                });
            } else {
                loadNPlaceCartItemsInfo();
            }
        }

        // change item quantity event
        $('#__update_main_cart').on('click' + variables.namespace, function () {
            shopCartTable
                .find('input[name="quantity"]')
                .each(function () {
                    var code, val;
                    code = $(this).attr('data-cart-item-code');
                    val = $(this).val();
                    val = val && !isNaN(parseInt(val, 10)) ? parseInt(val, 10) : 1;

                    if (code) {
                        cart.update(code, val, function () {
                            shop.toasts.toast(this.data, {
                                type: variables.toasts.types.success,
                            });
                            loadNPlaceCartItemsNInfo();
                        });
                    }
                });
        });

        // check and apply coupon
        couponApplyBtn.on('click' + variables.namespace, function () {
            var coupon;
            coupon = couponInp.val();
            if (coupon && core.trim(coupon) !== '') {
                cart.checkCoupon(coupon, function () {
                    var _ = this;
                    if (_.type === variables.api.types.warning) {
                        shop.toasts.toast(_.data);
                    } else {
                        shop.toasts.toast(_.data, {
                            type: variables.toasts.types.success,
                        });
                        loadNPlaceCartItemsInfo();
                    }
                });
            }
        });

        // choose address button click event
        addressChoosingBtn.on('click' + variables.namespace, function () {
            var checked, info, provincesSelect;
            checked = addressChoosingContainer.find('input[type="radio"]:checked');
            if (checked.length) {
                info = checked.val();
                try {
                    info = JSON.parse(info);
                    if (info) {
                        provincesSelect = $('input[name="inp-addr-province"]');
                        // assign values to inputs
                        $('input[name="inp-addr-full-name"]').val(info.full_name);
                        $('input[name="inp-addr-mobile"]').val(info.mobile);
                        $('input[name="inp-addr-address"]').val(info.address);
                        $('input[name="inp-addr-postal-code"]').val(info.postal_code);

                        // load province and city
                        provincesSelect.attr('data-current-province', info.province);
                        $('input[name="inp-addr-city"]').attr('data-current-city', info.city);

                        shop.loadProvinces(provincesSelect);
                    }
                } catch (e) {
                    // do nothing
                }
            }
        });

        // when city changed, calculate send price and update side info table
        $('input[name="inp-addr-city"]').on('change' + variables.namespace, function () {
            var checked, checkedProvince;
            checked = $(this).find(':checked').val();
            checkedProvince = $('input[name="inp-addr-province"]').find(':checked').val();

            if (checked) {
                shop.request(variables.url.cart.checkPostPrice, 'post', function () {
                    loadNPlaceCartInfo();
                }, {
                    data: {
                        city: checked,
                        province: checkedProvince,
                    }
                });
            }
        });

        //---------------------------------------------------------------
        // SUBMIT CHECKING
        //---------------------------------------------------------------
        shop.forms.submitForm(false, 'checkoutCheck', constraints.checkoutCheck, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = shop.showLoader();
            }
            
            shop.request(variables.url.checkout.check, 'post', function () {
                shop.hideLoader(loaderId);
                createLoader = true;
                //-----
                if (this.type !== variables.toasts.types.success) {
                    shop.toasts.toast(this.data, {
                        type: variables.toasts.types.warning,
                    });
                } else {
                    var data = this.data;
                    if(data.redirect) {
                        // Simulate an HTTP redirect:
                        window.location.replace(data.url);
                    } else {
                        // create a new form and submit it with hidden inputs
                        var frm = $('<form method="post" action="' +
                            data.url +
                            '" style="display: none; position: absolute; top: -9999px; left: -9999px; visibility: hidden; opacity: 0;" />');
                        for (var i = 0; i < data.inputs.length; ++i) {
                            frm.append($('<input type="hidden" value="' + data.inputs[i].value + '" name="' + data.inputs[i].name + '">'));
                        }
                        // add form to body
                        $('body').append(frm);
                        // submit it to go to the gateway
                        frm.submit();
                    }
                }
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
            '{{last-name}}': 'نام خانوادگی',
        });

        loadNPlaceCartItemsNInfo();
        loadNPlaceCartInfo();
    });
})(jQuery);