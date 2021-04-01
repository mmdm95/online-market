(function ($) {
    'use strict';

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {
        var
            shop = new window.TheShop(),
            cart = new window.TheCart(),
            core = window.TheCore,
            variables = window.MyGlobalVariables,
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
            shopCartInfoTable,
            //-----
            paymentGatewayForm;

        addressChoosingContainer = $('#__address_choise_container');
        addressChoosingBtn = $('#__address_choise_button');

        couponInp = $('.__coupon_field_inp');
        couponApplyBtn = $('.__apply_coupon');

        shopCartTable = $('.shop_cart_table');
        shopCartItemsInfoTable = $('.shop-cart-items-info-table');
        shopCartInfoTable = $('.shop-cart-info-table');

        paymentGatewayForm = $('#__checkout_payment_gateway');

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
        $('input[name="inp-addr-province"]').on('change' + variables.namespace, function () {
            var checked = $(this).find(':checked').val();
            if (checked) {
                shop.request(variables.url.cart.checkPostPrice, 'post', function () {
                    loadNPlaceCartInfo();
                }, {
                    data: {
                        province: checked,
                    }
                });
            }
        });

        // submit checking
        paymentGatewayForm.submit(function () {
            $.ajax({
                url: '',
                method: 'POST',
                data: {},
                async: false,
            }).done(function () {
                // return true;
                // return false;
            }).fail(function () {
                return false;
            });
            return false;
        });

        loadNPlaceCartItemsNInfo();
        loadNPlaceCartInfo();
    });
})(jQuery);