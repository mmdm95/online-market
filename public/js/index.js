(function ($) {
    'use strict';

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
            shop,
            registerForm,
            contactUsForm,
            newsletterForm,
            //-----
            registerConstraints,
            contactUsConstraints,
            //-----
            formValues,
            formErrors;

        shop = new window.TheShop();

        //---------------------------------------------------------------
        // CHECK SCROLL TO ELEMENT
        //---------------------------------------------------------------
        var
            hashval = window.location.hash.substr(1),
            elementsHash = [
                '__contact_form_container',
            ],
            modalsHash = [];

        if ($.inArray(hashval, elementsHash) !== -1) {
            core.scrollTo('#' + hashval, 140);
        }

        //---------------------------------------------------------------
        // REGISTER FORM
        //---------------------------------------------------------------
        registerForm = $(variables.elements.register.form);
        registerConstraints = core.constraints.register;
        registerForm.submit(function () {
            formValues = shop.forms.convertFormObjectNumbersToEnglish(window.validate.collectFormValues(this), 'register');
            formErrors = window.validate(formValues, registerConstraints);
            if (!formErrors) {
                return true;
            }
            shop.forms.showFormErrors(formErrors);
            return false;
        });

        //---------------------------------------------------------------
        // NEWSLETTER FORM
        //---------------------------------------------------------------
        newsletterForm = $(variables.elements.newsletter.form);
        newsletterForm.submit(function () {
            formValues = shop.forms.convertFormObjectNumbersToEnglish(window.validate.collectFormValues(this), 'newsletter');
            return false;
        });

        //---------------------------------------------------------------
        // CONTACT US FORM
        //---------------------------------------------------------------
        contactUsForm = $(variables.elements.contactUs.form);
        contactUsConstraints = core.constraints.contactUs;
        contactUsForm.submit(function () {
            formValues = shop.forms.convertFormObjectNumbersToEnglish(window.validate.collectFormValues(this), 'contactUs');
            formErrors = window.validate(formValues, contactUsConstraints);
            if (!formErrors) {
                return true;
            }
            shop.forms.showFormErrors(formErrors);
            return false;
        });
    });
})(jQuery);
