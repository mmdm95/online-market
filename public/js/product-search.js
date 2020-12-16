(function () {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.elements = $.extend({}, window.MyGlobalVariables.elements, {
        product: {
            form: '#__form_blog_search',
            inputs: {
                search: 'inp-blog-search-side',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            product: {
                search: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد جستجو را وارد کنید.',
                    },
                },
            },
        },
    });

    $(function () {
        var
            variables,
            core,
            //-----
            shop,
            constraints,
            uriParser,
            //-----
            mainProductContainer,
            sidebarMain,
            isInProgress = false;

        variables = window.MyGlobalVariables;
        core = window.TheCore;
        shop = new window.TheShop();
        uriParser = new UriParser();

        //-----
        constraints = {
            product: {
                search: variables.validation.constraints.product.search,
            },
        };
        //-----

        mainProductContainer = $('#__main_product_container');
        sidebarMain = $('#__the_sticky_sidebar');

        // make side menu sticky
        function stickySidebar() {
            sidebarMain.theiaStickySidebar({
                containerSelector: '#__sticky_sidebar_container',
                additionalMarginTop: 90,
                additionalMarginBottom: 20,
            });
        }

        // change page event
        function paginationClick() {
            $('a[data-page-no]').off('click' + variables.namespace).on('click' + variables.namespace, function (e) {
                e.preventDefault();
                //-----
                var page = parseInt($(this).attr('data-page-no'), 10);
                if (!isNaN(page) && 0 !== page) {
                    uriParser.push('page', page, true);
                }
            });
        }

        // search string changed
        function querySubmitting() {
            shop.forms.submitForm('product', constraints.product, function (values) {
                uriParser.push('q', values[variables.elements.product.inputs.search], true);
                return false;
            }, function (errors) {
                shop.forms.showFormErrors(errors);
                return false;
            });
        }

        // load product functionality
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
                    // reload function(s)
                    paginationClick();
                }, {
                    params: uriParser.get(null, {}, true),
                }, true, function () {
                    shop.hideLoader(loaderId);
                    isInProgress = false;
                    stickySidebar();
                });
            }
        }

        stickySidebar();
        querySubmitting();
        loadProduct();
        uriParser.onStateChange(function () {
            this.pushState(this.get(null, {}, true));
            loadProduct();
        });
    });
})();