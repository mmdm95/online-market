(function ($) {
    'use strict';

    var core, base, variables;
    var dataLoadableName, loadableAttributes;

    dataLoadableName = 'data-module-load';
    loadableAttributes = [
        // main
        'menu', 'menu-categories', 'categories', 'product-favorite',
        // settings
        'setting-logo', 'setting-mobile', 'setting-footer',
        // sliders
        'slider-tabed', 'slider-instagram', 'slider-off', 'slider-banner',
        'slider-blog', 'slider-brands',
        // modals
        'modal-login', 'modal-register',
    ];

    base = new window.TheShopBase();
    core = window.TheCore;
    variables = window.MyGlobalVariables;

    /**
     * Make Shop class global to window
     * @type {TheShop}
     */
    window.TheShop = (function (_super, core) {
        // inherit from Base class
        core.extend(Shop, _super);

        // now the class definition
        function Shop() {
            _super.call(this);

            this.page_elements = {};
        }

        $.extend(Shop.prototype, {
            getProductInfo: function (id) {
                id = parseInt(id, 10);

                if (core.isNumber(id)) {
                    window.axios({
                        method: 'get',
                        url: window.MyGlobalVariables.url.products.get,
                    });
                }
            },
        });

        return Shop;
    })(window.TheShopBase, core);

    /**
     * Do stuffs after DOM loaded
     */
    $(function () {

    });
})(jQuery);
