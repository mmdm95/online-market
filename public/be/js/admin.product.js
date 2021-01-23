(function ($) {

    'use strict';

    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        product: {
            remove: '/ajax/product/remove',
            pub: '/ajax/product/pub-status',
            av: '/ajax/product/av-status',
        },
    });
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
        addProduct: {
            form: '#__form_add_product',
            inputs: {
                status: 'inp-add-product-status',
                availability: 'inp-add-product-availability',
                special: 'inp-add-product-special',
                commenting: 'inp-add-product-commenting',
                image: 'inp-add-product-img',
                title: 'inp-add-product-title',
                simpleProp: 'inp-add-product-simple-properties',
                keywords: 'inp-add-product-keywords',
                brand: 'inp-add-product-brand',
                category: 'inp-add-product-category',
                stockCount: 'inp-add-product-stock-count[]',
                maxCartCount: 'inp-add-product-max-count[]',
                color: 'inp-add-product-color[]',
                size: 'inp-add-product-size[]',
                guarantee: 'inp-add-product-guarantee[]',
                price: 'inp-add-product-price[]',
                discountPrice: 'inp-add-product-discount-price[]',
                discountDate: 'inp-add-product-discount-date[]',
                productAvailability: 'inp-add-product-product-availability[]',
                gallery: 'inp-add-product-gallery-img[]',
                desc: 'inp-add-product-desc',
            },
        },
        editProduct: {
            form: '#__form_edit_product',
            inputs: {
                name: 'inp-edit-product-',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend({}, window.MyGlobalVariables.validation, {
        constraints: {},
    });

    $(function () {
        var
            admin;

        admin = new window.TheAdmin();
    });
})(jQuery);