(function ($) {
    'use strict';

    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        walletCharge: {
            check: '/ajax/wallet/charge/check',
        }
    });
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
        chargeCheck: {
            form: '#__frm_charge_user_wallet',
            inputs: {
                price: 'inp-wallet-price',
                gateway: 'inp-wallet-payment-method-option',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            chargeCheck: {
                price: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد مبلغ شارژ را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^\d$/,
                        message: '^' + 'مبلغ شارژ باید از نوع عددی باشد.',
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
            createLoader = true,
            //-----
            canSubmit = true;

        //-----
        constraints = {
            chargeCheck: {
                price: variables.validation.constraints.chargeCheck.price,
            },
        };

        //---------------------------------------------------------------
        // SUBMIT CHECKING
        //---------------------------------------------------------------
        shop.forms.submitForm(false, 'chargeCheck', constraints.chargeCheck, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = shop.showLoader();
            }

            if (canSubmit) {
                shop.request(variables.url.walletCharge.check, 'post', function () {
                    if (this.type !== variables.toasts.types.success) {
                        shop.toasts.toast(this.data, {
                            type: variables.toasts.types.warning,
                        });
                    } else {
                        var data = this.data;
                        if (data.redirect) {
                            shop.toasts.toast('لطفا چند لحظه صبر کنید...', {
                                type: 'info',
                                layout: 'topCenter',
                                timeout: false,
                            });

                            // Simulate an HTTP redirect:
                            window.location.replace(data.url);
                        } else {
                            // create a new form and submit it with hidden inputs
                            var frm = $('<form method="post" action="' +
                                data.url + '" ' +
                                ((data.multipart_form || false) ? 'enctype="multipart/form-data"' : '') +
                                ' style="display: none; position: absolute; top: -9999px; left: -9999px; visibility: hidden; opacity: 0; border: 0; background: transparent;" />');
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
            }
            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });
    });
})(jQuery);