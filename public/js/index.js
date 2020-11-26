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
        var shop = new window.TheShop();


    });
})(jQuery);
