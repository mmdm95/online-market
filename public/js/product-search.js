(function () {
    'use strict';

    $(function () {
        var
            variables,
            core,
            //-----
            shop,
            uriParser,
            //-----
            mainProductContainer,
            isInProgress = false;

        variables = window.MyGlobalVariables;
        core = window.TheCore;
        shop = new window.TheShop();
        uriParser = new UriParser();

        mainProductContainer = $('#__main_product_container');

        function loadProduct() {
            if (!isInProgress) {
                isInProgress = true;
                var loaderId = shop.showLoader();
                shop.request(variables.url.products.search, 'get', function () {
                    // place received items in their place
                    mainProductContainer.html(this.data);
                    // back to normal
                    shop.hideLoader(loaderId);
                    isInProgress = false;
                }, {
                    params: uriParser.uniqueObj(),
                }, true, function () {
                    shop.hideLoader(loaderId);
                    isInProgress = false;
                });
            }
        }

        var s = 'brand[0]=5288&brand[1]=1651&brand[2]=18&has_selling_stock=1&only_original=1&has_ready_to_shipment=1&price[min]=3410952&price[max]=12987857&pageno=1&sortby=4';


        loadProduct();
        uriParser.onStateChange(function () {
            this.pushState(this.uniqueObj());
            loadProduct();
        });


        // change page event
        $('a[data-page-no]').on('click' + variables.namespace, function (e) {
            e.preventDefault();
            //-----
            var page = parseInt($(this).attr('data-page-no'), 10);
            if (!isNaN(page) && 0 !== page && (!(uriParser.has('page')) || uriParser.getFirst('page'))) {
                uriParser.push('page', page, true);
            }
        });
    });
})();