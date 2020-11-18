(function ($) {
    /**
     * Make Shop class global to window
     * @type {Shop}
     */
    window.TheShop = (function (_super) {
        // inherit from Base class
        window.TheCore.extend(Shop, _super);

        // now the class definition
        function Shop() {
            _super.call(this);
        }

        $.extend(Shop.prototype, {
            // extra functionality
        });

        return Shop;
    })(window.TheShopBase);
})(jQuery);
