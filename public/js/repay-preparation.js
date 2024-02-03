(function ($) {
  'use strict';

  window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
    repayCheck: '/ajax/user/order/re-pay',
  });
  window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
    repay: {
      form: '#__checkout_repay_gateway',
      inputs: {
        methodType: 'inp-re-payment-method-option',
      },
    },
  });
  window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
    constraints: {
      repay: {},
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
      createLoader = true;

    //-----
    constraints = {
      repay: {},
    };

    //---------------------------------------------------------------
    // SUBMIT CHECKING
    //---------------------------------------------------------------
    shop.forms.submitForm(false, 'repay', constraints.repay, function (values) {
      // do ajax
      if (createLoader) {
        createLoader = false;
        loaderId = shop.showLoader();
      }
      var currentId = $('#currentOrderId').val();

      if (currentId) {
        shop.request(variables.url.repayCheck + '/' + currentId, 'post', function () {
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
