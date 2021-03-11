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
            shopCartTable,
            shopCartInfoTable;

        shopCartTable = $('.shop_cart_table');
        shopCartInfoTable = $('.shop-cart-info-table');

        function loadNPlaceCartInfo() {
            cart.getTotalCartInfo(function () {
                var self = this;
                // put content in correct place
                shopCartInfoTable.html(self.data);
                shop.hideLoaderFromInsideElement(shopCartInfoTable);
            });
        }

        function loadNPlaceCartItemsNInfo() {
            shop.showLoaderInsideElement(shopCartTable);
            shop.showLoaderInsideElement(shopCartInfoTable);

            cart.getCartItems(function () {
                var self = this;
                // put content in correct place
                shopCartTable.html(self.data);
                shop.hideLoaderFromInsideElement(shopCartTable);
                loadNPlaceCartInfo();
            });
        }

        loadNPlaceCartItemsNInfo();
    });
})(jQuery);