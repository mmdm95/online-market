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
        nationalNum: 'natnum',
        receiverName: 'inp-addr-full-name',
        mobile: 'inp-addr-mobile',
        province: 'inp-addr-province',
        city: 'inp-addr-city',
        postalCode: 'inp-addr-postal-code',
        address: 'inp-addr-address',
        sendMethod: 'send_method_option',
        gateway: 'payment_method_option',
        companyName: 'inp-addr-company-name',
        companyEcoCode: 'inp-addr-company-eco-code',
        companyEcoNID: 'inp-addr-company-eco-nid',
        companyRegNum: 'inp-addr-company-reg-num',
        companyTel: 'inp-addr-tel',
        companyProvince: 'inp-addr-company-province',
        companyCity: 'inp-addr-company-city',
        companyPostalCode: 'inp-addr-company-postal-code',
        companyAddress: 'inp-addr-company-address',
        legalOrReal: 'inp-is-real-or-legal',
      },
    },
  });
  window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
    constraints: {
      checkoutCheck: {},
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
      sendMethodRadio,
      realOrLegalRadio,
      //-----
      addressChoosingContainer,
      addressChoosingBtn,
      addressCompanyChoosingContainer,
      addressCompanyChoosingBtn,
      //-----
      couponInp,
      couponApplyBtn,
      removeFromCartBtn,
      //-----
      shopCartTable,
      shopCartItemsInfoTable,
      shopCartInfoTable,
      //-----
      userCity,
      userCompanyCity,
      //-----
      inPersonDeliveryChk,
      shouldCalcSendPrice = true,
      //-----
      canSubmit = true;

    const
      RECEIVER_TYPE_REAL = 1,
      RECEIVER_TYPE_LEGAL = 2;

    //-----
    constraints = {
      checkoutCheck: {},
    };

    sendMethodRadio = $('input[name="send_method_option"]');
    realOrLegalRadio = $('input[name="inp-is-real-or-legal"]');

    addressChoosingContainer = $('#__address_choice_container');
    addressChoosingBtn = $('#__address_choice_button');

    addressCompanyChoosingContainer = $('#__address_company_choice_container');
    addressCompanyChoosingBtn = $('#__address_company_choice_button');

    couponInp = $('.__coupon_field_inp');
    couponApplyBtn = $('.__apply_coupon');

    shopCartTable = $('.shop_cart_table');
    shopCartItemsInfoTable = $('.shop-cart-items-info-table');
    shopCartInfoTable = $('.shop-cart-info-table');

    userCity = $('select[name="inp-addr-city"]');
    userCompanyCity = $('select[name="inp-addr-company-city"]');

    inPersonDeliveryChk = $('#inPersonDeliveryChk');

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
              cart.removeNPlaceCartFunctionality($(this), function () {
                if (this.code == 301) {
                  window.location.reload();
                } else {
                  loadNPlaceCartItemsNInfo();
                }
              }, false);
            });

          shop.hideLoaderFromInsideElement(shopCartTable);
          loadNPlaceCartItemsInfo();
        });
      } else {
        loadNPlaceCartItemsInfo();
      }
    }

    function checkPostPriceRemote(sendMethod, city, province) {
      var form = new FormData();
      form.append('send_method_option', sendMethod);
      form.append('city', city);
      form.append('province', province);
      form.append('should_calc_send_price', shouldCalcSendPrice);

      canSubmit = false;
      shop.request(variables.url.cart.checkPostPrice, 'post', function () {
        loadNPlaceCartInfo();
        canSubmit = true;
        createLoader = true;
        shop.hideLoader(loaderId);
      }, {
        data: form,
      }, false, function () {
        canSubmit = true;
        createLoader = true;
        shop.hideLoader(loaderId);
      });
    }

    function determinePostPriceRemote(showLoader) {
      var sendMethodRadio = $('input[name="send_method_option"]:checked');
      var legalRadio = $('input[name="inp-is-real-or-legal"]:checked');
      var city, province;

      if (legalRadio.val() == RECEIVER_TYPE_LEGAL) {
        city = $('select[name="inp-addr-company-city"]').find('option:selected').val();
        province = $('select[name="inp-addr-company-province"]').find('option:selected').val();
      } else {
        city = $('select[name="inp-addr-city"]').find('option:selected').val();
        province = $('select[name="inp-addr-province"]').find('option:selected').val();
      }

      if (showLoader !== false) {
        if (createLoader) {
          createLoader = false;
          loaderId = shop.showLoader();
        }
      }

      checkPostPriceRemote(sendMethodRadio.val(), city, province);
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
            cart.update(code, val);
          }
        });

      // just need to show and load table's info for just one time
      loadNPlaceCartItemsNInfo();
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
          }
          loadNPlaceCartItemsInfo();
        });
      }
    });

    sendMethodRadio.on('change' + variables.namespace, function () {
      determinePostPriceRemote();
    });

    realOrLegalRadio.on('change' + variables.namespace, function () {
      // Get the selected radio button's ID
      var selectedTabId = $(this).attr('id');
      var checkedRadio = $('input[name="inp-is-real-or-legal"]:checked');

      realOrLegalRadio.parent().find('label').removeClass('active-real-or-legal-radio');
      checkedRadio.parent().find('label').addClass('active-real-or-legal-radio');

      if (checkedRadio.val() == RECEIVER_TYPE_LEGAL) {
        userCompanyCity.trigger('change' + variables.namespace);
      } else {
        userCity.trigger('change' + variables.namespace);
      }

      // Show the corresponding tab-pane and hide others
      $('#realOrLegalTabContent .tab-pane').removeClass('show active');
      $('#realOrLegalContent' + selectedTabId.slice(-1)).addClass('show active');
    });

    // choose address button click event
    addressChoosingBtn.on('click' + variables.namespace, function () {
      var checked, info, provincesSelect;
      checked = addressChoosingContainer.find('input[type="radio"]:checked');
      if (checked.length) {
        info = checked.attr('data-address-obj');
        try {
          info = JSON.parse(info);
          if (info) {

            provincesSelect = $('select[name="inp-addr-province"]');
            // assign values to inputs
            $('input[name="inp-addr-full-name"]').val(info.full_name);
            $('input[name="inp-addr-mobile"]').val(info.mobile);
            $('textarea[name="inp-addr-address"]').val(info.address);
            $('input[name="inp-addr-postal-code"]').val(info.postal_code);

            // load province and city
            provincesSelect.attr('data-current-province', info.province_id);
            $('select[name="inp-addr-city"]').attr('data-current-city', info.city_id);

            shop.loadProvinces(provincesSelect);

            $('#__user_addr_choose_modal').modal('toggle');
          }
        } catch (e) {
          // do nothing
        }
      }
    });

    // choose address button click event
    addressCompanyChoosingBtn.on('click' + variables.namespace, function () {
      var checked, info, provincesSelect;
      checked = addressCompanyChoosingContainer.find('input[type="radio"]:checked');
      if (checked.length) {
        info = checked.attr('data-address-obj');
        try {
          info = JSON.parse(info);
          if (info) {
            provincesSelect = $('select[name="inp-addr-company-province"]');
            // assign values to inputs
            $('input[name="inp-addr-company-name"]').val(info.company_name);
            $('input[name=inp-addr-company-eco-code]').val(info.economic_code);
            $('input[name=inp-addr-company-eco-nid]').val(info.economic_national_id);
            $('input[name=inp-addr-company-reg-num]').val(info.registration_number);
            $('input[name="inp-addr-tel"]').val(info.landline_tel);
            $('textarea[name="inp-addr-company-address"]').val(info.address);
            $('input[name="inp-addr-company-postal-code"]').val(info.postal_code);

            // load province and city
            provincesSelect.attr('data-current-province', info.province_id);
            $('select[name="inp-addr-company-city"]').attr('data-current-city', info.city_id);

            shop.loadProvinces(provincesSelect);

            $('#__user_addr_company_choose_modal').modal('toggle');
          }
        } catch (e) {
          // do nothing
        }
      }
    });

    // when city changed, calculate send price and update side info table
    userCity.on('change' + variables.namespace, function () {
      determinePostPriceRemote();
    });

    // when city changed in legal section, calculate send price and update side info table
    userCompanyCity.on('change' + variables.namespace, function () {
      determinePostPriceRemote();
    });

    inPersonDeliveryChk.mSwitch({
      onTurnOn: function (btn) {
        btn.closest('.alert').addClass('alert-primary').removeClass('bg-light');
        shouldCalcSendPrice = false;

        if (createLoader) {
          createLoader = false;
          loaderId = shop.showLoader();
        }
        shop.request(variables.url.cart.removePostPrice, 'post', function () {
          loadNPlaceCartInfo();
          canSubmit = true;
          createLoader = true;
          shop.hideLoader(loaderId);
        }, {}, false, function () {
          createLoader = true;
          shop.hideLoader(loaderId);
        });
      },
      onTurnOff: function (btn) {
        btn.closest('.alert').addClass('bg-light').removeClass('alert-primary');
        shouldCalcSendPrice = true;
        if ($('input[name="inp-is-real-or-legal"]:checked').val() == RECEIVER_TYPE_LEGAL) {
          userCompanyCity.trigger('change' + variables.namespace);
        } else {
          userCity.trigger('change' + variables.namespace);
        }
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

      if (canSubmit) {
        shop.request(variables.url.checkout.check, 'post', function () {
          if (this.type !== variables.toasts.types.success) {
            shop.toasts.toast(this.data, {
              type: variables.toasts.types.warning,
            });

            createLoader = true;
            shop.hideLoader(loaderId);
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

    loadNPlaceCartItemsNInfo();
    loadNPlaceCartInfo();

    determinePostPriceRemote(false);
  });
})(jQuery);
