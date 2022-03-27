/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

(function ($) {

    'use strict';

    window.MyGlobalVariables.icons = {
        success: 'icon-checkmark3',
        info: 'icon-info3',
        warning: 'icon-warning',
        error: 'icon-cross2',
    };
    window.MyGlobalVariables.toasts.toast.theme = 'limitless';
    window.MyGlobalVariables.toasts.toast.layout = 'topLeft';
    window.MyGlobalVariables.toasts.confirm.theme = 'limitless';
    window.MyGlobalVariables.toasts.confirm.type = 'confirm';
    window.MyGlobalVariables.toasts.confirm.btnClasses.yes = 'btn bg-blue text-white ml-1';
    window.MyGlobalVariables.toasts.confirm.btnClasses.no = 'btn btn-link';

    window.MyGlobalVariables.url = $.extend(true, window.MyGlobalVariables.url, {
        address: {
            get: '/ajax/address/get',
            add: '/ajax/address/add',
            edit: '/ajax/address/edit',
            remove: '/ajax/address/remove',
        },
        unit: {
            get: '/ajax/unit/get',
            add: '/ajax/unit/add',
            edit: '/ajax/unit/edit',
            remove: '/ajax/unit/remove',
        },
        faq: {
            get: '/ajax/faq/get',
            add: '/ajax/faq/add',
            edit: '/ajax/faq/edit',
            remove: '/ajax/faq/remove',
        },
        slider: {
            get: '/ajax/slider/get',
            add: '/ajax/slider/add',
            edit: '/ajax/slider/edit',
            remove: '/ajax/slider/remove',
        },
        newsletter: {
            add: '/ajax/admin/newsletter/add',
            remove: '/ajax/admin/newsletter/remove',
        },
        instagram: {
            get: '/ajax/instagram/get',
            add: '/ajax/instagram/add',
            edit: '/ajax/instagram/edit',
            remove: '/ajax/instagram/remove',
        },
        badge: {
            get: '/ajax/badge/get',
            add: '/ajax/badge/add',
            edit: '/ajax/badge/edit',
            remove: '/ajax/badge/remove',
        },
        categoryImage: {
            get: '/ajax/category/image/get',
            add: '/ajax/category/image/add',
            edit: '/ajax/category/image/edit',
            remove: '/ajax/category/image/remove',
        },
        securityQuestion: {
            get: '/ajax/sec-question/get',
            add: '/ajax/sec-question/add',
            edit: '/ajax/sec-question/edit',
            remove: '/ajax/sec-question/remove',
        },
        productFestival: {
            addProduct: '/ajax/product/festival/add',
            addCategory: '/ajax/product/festival/add-category',
            remove: '/ajax/product/festival/remove',
            removeCategory: '/ajax/product/festival/category/remove',
        },
        product: {
            remove: '/ajax/product/remove',
            pub: '/ajax/product/pub-status',
            av: '/ajax/product/av-status',
        },
        orders: {
            info: '/ajax/order/info',
        },
        depositType: {
            get: '/ajax/deposit-type/get',
            add: '/ajax/deposit-type/add',
            edit: '/ajax/deposit-type/edit',
            remove: '/ajax/deposit-type/remove',
        },
        report: {
            user: {
                filter: '/ajax/report/users/filter',
                filterClear: '/ajax/report/users/filter/clear',
                excelExport: '/ajax/report/users/export/excel',
            },
            product: {
                filter: '/ajax/report/products/filter',
                filterClear: '/ajax/report/products/filter/clear',
                excelExport: '/ajax/report/products/export/excel',
            },
            order: {
                filter: '/ajax/report/orders/filter',
                filterClear: '/ajax/report/orders/filter/clear',
                excelExport: '/ajax/report/orders/export/excel',
                pdfExport: '/ajax/report/order/export/pdf',
            },
            wallet: {
                filter: '/ajax/report/wallet/filter',
                filterClear: '/ajax/report/wallet/filter/clear',
                excelExport: '/ajax/report/wallet/export/excel',
            },
        },
    });
    window.MyGlobalVariables.elements = $.extend(true, window.MyGlobalVariables.elements, {
        addAddress: {
            form: '#__form_add_address',
            inputs: {
                name: 'inp-add-address-full-name',
                mobile: 'inp-add-address-mobile',
                province: 'inp-add-address-province',
                city: 'inp-add-address-city',
                postalCode: 'inp-add-address-postal-code',
                address: 'inp-add-address-addr',
            },
        },
        editAddress: {
            form: '#__form_edit_address',
            inputs: {
                name: 'inp-edit-address-full-name',
                mobile: 'inp-edit-address-mobile',
                province: 'inp-edit-address-province',
                city: 'inp-edit-address-city',
                postalCode: 'inp-edit-address-postal-code',
                address: 'inp-edit-address-addr',
            },
        },
        addUnit: {
            form: '#__form_add_unit',
            inputs: {
                title: 'inp-add-unit-title',
                sign: 'inp-add-unit-sign',
            },
        },
        editUnit: {
            form: '#__form_edit_unit',
            inputs: {
                title: 'inp-edit-unit-title',
                sign: 'inp-edit-unit-sign',
            },
        },
        addBlog: {
            form: '#__form_add_blog',
            inputs: {
                image: 'inp-add-blog-img',
                status: 'inp-add-blog-status',
                title: 'inp-add-blog-title',
                category: 'inp-add-blog-category',
                abstract: 'inp-add-blog-abs',
                keywords: 'inp-add-blog-keywords',
                desc: 'inp-add-blog-desc',
            },
        },
        editBlog: {
            form: '#__form_edit_blog',
            inputs: {
                image: 'inp-edit-blog-img',
                status: 'inp-edit-blog-status',
                title: 'inp-edit-blog-title',
                category: 'inp-edit-blog-category',
                abstract: 'inp-edit-blog-abs',
                keywords: 'inp-edit-blog-keywords',
                desc: 'inp-edit-blog-desc',
            },
        },
        addFaq: {
            form: '#__form_add_faq',
            inputs: {
                question: 'inp-add-faq-q',
                answer: 'inp-add-faq-a',
                tags: 'inp-add-faq-tags',
                status: 'inp-add-faq-status',
            },
        },
        editFaq: {
            form: '#__form_edit_faq',
            inputs: {
                question: 'inp-edit-faq-q',
                answer: 'inp-edit-faq-a',
                tags: 'inp-edit-faq-tags',
                status: 'inp-edit-faq-status',
            },
        },
        addColor: {
            form: '#__form_add_color',
            inputs: {
                name: 'inp-add-color-name',
                color: 'inp-add-color-color',
                status: 'inp-add-color-status',
            },
        },
        editColor: {
            form: '#__form_edit_color',
            inputs: {
                name: 'inp-edit-color-name',
                color: 'inp-edit-color-color',
                status: 'inp-edit-color-status',
            },
        },
        addSlide: {
            form: '#__form_add_slide',
            inputs: {
                image: 'inp-add-slide-img',
                title: 'inp-add-slide-title',
                subTitle: 'inp-add-slide-sub-title',
                link: 'inp-add-slide-sub-link',
                priority: 'inp-add-slide-priority',
            },
        },
        editSlide: {
            form: '#__form_edit_slide',
            inputs: {
                image: 'inp-edit-slide-img',
                title: 'inp-edit-slide-title',
                subTitle: 'inp-edit-slide-sub-title',
                link: 'inp-edit-slide-sub-link',
                priority: 'inp-edit-slide-priority',
            },
        },
        addBlogCategory: {
            form: '#__form_add_blog_category',
            inputs: {
                name: 'inp-add-blog-category-name',
                status: 'inp-add-blog-category-status',
            },
        },
        editBlogCategory: {
            form: '#__form_edit_blog_category',
            inputs: {
                name: 'inp-edit-blog-category-name',
                status: 'inp-edit-blog-category-status',
            },
        },
        addNewsletter: {
            form: '#__form_add_newsletter',
            inputs: {
                mobile: 'inp-add-newsletter-mobile',
            },
        },
        addBrand: {
            form: '#__form_add_brand',
            inputs: {
                image: 'inp-add-brand-img',
                status: 'inp-add-brand-status',
                title: 'inp-add-brand-fa-title',
                enTitle: 'inp-add-brand-en-title',
                keywords: 'inp-add-brand-keywords',
                desc: 'inp-add-brand-desc',
            },
        },
        editBrand: {
            form: '#__form_edit_brand',
            inputs: {
                image: 'inp-edit-brand-img',
                status: 'inp-edit-brand-status',
                title: 'inp-edit-brand-fa-title',
                enTitle: 'inp-edit-brand-en-title',
                keywords: 'inp-edit-brand-keywords',
                desc: 'inp-edit-brand-desc',
            },
        },
        addInstagramImage: {
            form: '#__form_add_instagram_image',
            inputs: {
                image: 'inp-add-ins-img',
                link: 'inp-add-ins-link',
            },
        },
        editInstagramImage: {
            form: '#__form_edit_instagram_image',
            inputs: {
                image: 'inp-edit-ins-img',
                link: 'inp-edit-ins-link',
            },
        },
        addBadge: {
            form: '#__form_add_badge',
            inputs: {
                title: 'inp-add-badge-title',
                color: 'inp-add-badge-color',
                canReturnOrder: 'inp-add-badge-allow-return',
            },
        },
        editBadge: {
            form: '#__form_edit_badge',
            inputs: {
                title: 'inp-edit-badge-title',
                color: 'inp-edit-badge-color',
                canReturnOrder: 'inp-edit-badge-allow-return',
            },
        },
        addCategory: {
            form: '#__form_add_category',
            inputs: {
                status: 'inp-add-category-status',
                name: 'inp-add-category-name',
                parent: 'inp-add-category-parent',
                priority: 'inp-add-category-priority',
                keywords: 'inp-add-category-keywords',
            },
        },
        editCategory: {
            form: '#__form_edit_category',
            inputs: {
                status: 'inp-edit-category-status',
                name: 'inp-edit-category-name',
                parent: 'inp-edit-category-parent',
                priority: 'inp-edit-category-priority',
                keywords: 'inp-edit-category-keywords',
            },
        },
        addCategoryImage: {
            form: '#__form_add_cat_img',
            inputs: {
                image: 'inp-add-cat-img-img',
                link: 'inp-add-cat-img-link',
            },
        },
        editCategoryImage: {
            form: '#__form_edit_cat_img',
            inputs: {
                image: 'inp-edit-cat-img-img',
                link: 'inp-edit-cat-img-link',
            },
        },
        addStaticPage: {
            form: '#__form_add_static_page',
            inputs: {
                status: 'inp-add-static-page-status',
                title: 'inp-add-static-page-title',
                url: 'inp-add-static-page-url',
                keywords: 'inp-add-static-page-keywords',
                desc: 'inp-add-static-page-desc',
            },
        },
        editStaticPage: {
            form: '#__form_edit_static_page',
            inputs: {
                status: 'inp-edit-static-page-status',
                title: 'inp-edit-static-page-title',
                url: 'inp-edit-static-page-url',
                keywords: 'inp-edit-static-page-keywords',
                desc: 'inp-edit-static-page-desc',
            },
        },
        addCoupon: {
            form: '#__form_add_coupon',
            inputs: {
                status: 'inp-add-coupon-status',
                code: 'inp-add-coupon-code',
                title: 'inp-add-coupon-title',
                price: 'inp-add-coupon-price',
                minPrice: 'inp-add-coupon-min-price',
                maxPrice: 'inp-add-coupon-max-price',
                count: 'inp-add-coupon-count',
                useAfter: 'inp-add-coupon-use-after',
                start: 'inp-add-coupon-start-date',
                end: 'inp-add-coupon-end-date',
            },
        },
        editCoupon: {
            form: '#__form_edit_coupon',
            inputs: {
                status: 'inp-edit-coupon-status',
                code: 'inp-edit-coupon-code',
                title: 'inp-edit-coupon-title',
                price: 'inp-edit-coupon-price',
                minPrice: 'inp-edit-coupon-min-price',
                maxPrice: 'inp-edit-coupon-max-price',
                count: 'inp-edit-coupon-count',
                useAfter: 'inp-edit-coupon-use-after',
                start: 'inp-edit-coupon-start-date',
                end: 'inp-edit-coupon-end-date',
            },
        },
        addSecurityQuestion: {
            form: '#__form_add_sec_question',
            inputs: {
                status: 'inp-add-sec-question-status',
                question: 'inp-add-sec-question-q',
            },
        },
        editSecurityQuestion: {
            form: '#__form_edit_sec_question',
            inputs: {
                status: 'inp-edit-sec-question-status',
                question: 'inp-edit-sec-question-q',
            },
        },
        addPaymentMethod: {
            form: '#__form_add_pay_method',
            inputs: {
                status: 'inp-add-pay-method-status',
                image: 'inp-add-pay-method-img',
                title: 'inp-add-pay-method-title',
                method: 'inp-add-pay-method-method',
                sadadMerchant: 'inp-add-pay-method-sadad-merchant',
                sadadTerminal: 'inp-add-pay-method-sadad-terminal',
                sadadKey: 'inp-add-pay-method-sadad-key',
                behPardakhtTerminal: 'inp-add-pay-method-beh-pardakht-terminal',
                behPardakhtUsername: 'inp-add-pay-method-beh-pardakht-username',
                behPardakhtPassword: 'inp-add-pay-method-beh-pardakht-password',
                idpayApiKey: 'inp-add-pay-method-idpay-api-key',
                mabnaTerminal: 'inp-add-pay-method-mabna-terminal',
                zarinpalMerchant: 'inp-add-pay-method-zarinpal-merchant',
            },
        },
        editPaymentMethod: {
            form: '#__form_edit_pay_method',
            inputs: {
                status: 'inp-edit-pay-method-status',
                image: 'inp-edit-pay-method-img',
                title: 'inp-edit-pay-method-title',
                method: 'inp-edit-pay-method-method',
                sadadMerchant: 'inp-edit-pay-method-sadad-merchant',
                sadadTerminal: 'inp-edit-pay-method-sadad-terminal',
                sadadKey: 'inp-edit-pay-method-sadad-key',
                behPardakhtTerminal: 'inp-edit-pay-method-beh-pardakht-terminal',
                behPardakhtUsername: 'inp-edit-pay-method-beh-pardakht-username',
                behPardakhtPassword: 'inp-edit-pay-method-beh-pardakht-password',
                idpayApiKey: 'inp-edit-pay-method-idpay-api-key',
                mabnaTerminal: 'inp-edit-pay-method-mabna-terminal',
                zarinpalMerchant: 'inp-edit-pay-method-zarinpal-merchant',
            },
        },
        addSendMethod: {
            form: '#__form_add_send_method',
            inputs: {
                status: 'inp-add-send-method-status',
                image: 'inp-add-send-method-img',
                title: 'inp-add-send-method-title',
                desc: 'inp-add-send-method-desc',
            },
        },
        editSendMethod: {
            form: '#__form_edit_send_method',
            inputs: {
                status: 'inp-edit-send-method-status',
                image: 'inp-edit-send-method-img',
                title: 'inp-edit-send-method-title',
                desc: 'inp-edit-send-method-desc',
            },
        },
        addFestival: {
            form: '#__form_add_festival',
            inputs: {
                status: 'inp-add-festival-status',
                title: 'inp-add-festival-title',
                start: 'inp-add-festival-start-date',
                end: 'inp-add-festival-end-date',
            },
        },
        editFestival: {
            form: '#__form_edit_festival',
            inputs: {
                status: 'inp-edit-festival-status',
                title: 'inp-edit-festival-title',
                start: 'inp-edit-festival-start-date',
                end: 'inp-edit-festival-end-date',
            },
        },
        addProductFestival: {
            form: '#__form_add_product_festival',
            inputs: {
                product: 'inp-add-product-festival-product',
                percent: 'inp-add-product-festival-percent',
            },
        },
        modifyProductFestival: {
            form: '#__form_modify_product_festival',
            inputs: {
                category: 'inp-modify-product-festival-category',
                percent: 'inp-modify-product-festival-percent',
            },
        },
        addProduct: {
            form: '#__form_add_product',
            inputs: {
                status: 'inp-add-product-status',
                availability: 'inp-add-product-availability',
                special: 'inp-add-product-special',
                returnable: 'inp-add-product-returnable',
                commenting: 'inp-add-product-commenting',
                image: 'inp-add-product-img',
                title: 'inp-add-product-title',
                simpleProp: 'inp-add-product-simple-properties',
                keywords: 'inp-add-product-keywords',
                brand: 'inp-add-product-brand',
                category: 'inp-add-product-category',
                unit: 'inp-add-product-unit',
                stockCount: 'inp-add-product-stock-count[]',
                maxCartCount: 'inp-add-product-max-count[]',
                color: 'inp-add-product-color[]',
                size: 'inp-add-product-size[]',
                guarantee: 'inp-add-product-guarantee[]',
                weight: 'inp-add-product-weight[]',
                price: 'inp-add-product-price[]',
                discountPrice: 'inp-add-product-discount-price[]',
                discountDate: 'inp-add-product-discount-date[]',
                considerDiscountDate: 'inp-add-product-consider-discount-date[]',
                productAvailability: 'inp-add-product-product-availability[]',
                gallery: 'inp-add-product-gallery-img[]',
                desc: 'inp-add-product-desc',
                properties: 'inp-item-product-properties',
                subProperties: 'inp-item-product-sub-properties',
                alertProduct: 'inp-add-product-alert-product',
                related: 'inp-add-product-related[]',
            },
        },
        editProduct: {
            form: '#__form_edit_product',
            inputs: {
                status: 'inp-edit-product-status',
                availability: 'inp-edit-product-availability',
                special: 'inp-edit-product-special',
                returnable: 'inp-edit-product-returnable',
                commenting: 'inp-edit-product-commenting',
                image: 'inp-edit-product-img',
                title: 'inp-edit-product-title',
                simpleProp: 'inp-edit-product-simple-properties',
                keywords: 'inp-edit-product-keywords',
                brand: 'inp-edit-product-brand',
                category: 'inp-edit-product-category',
                unit: 'inp-edit-product-unit',
                stockCount: 'inp-edit-product-stock-count[]',
                maxCartCount: 'inp-edit-product-max-count[]',
                color: 'inp-edit-product-color[]',
                size: 'inp-edit-product-size[]',
                guarantee: 'inp-edit-product-guarantee[]',
                weight: 'inp-edit-product-weight[]',
                price: 'inp-edit-product-price[]',
                discountPrice: 'inp-edit-product-discount-price[]',
                discountDate: 'inp-edit-product-discount-date[]',
                considerDiscountDate: 'inp-edit-product-consider-discount-date[]',
                productAvailability: 'inp-edit-product-product-availability[]',
                gallery: 'inp-edit-product-gallery-img[]',
                desc: 'inp-edit-product-desc',
                properties: 'inp-item-product-properties',
                subProperties: 'inp-item-product-sub-properties',
                alertProduct: 'inp-edit-product-alert-product',
                related: 'inp-edit-product-related[]',
            },
        },
        answerComment: {
            inputs: {
                desc: 'inp-ans-comment-desc',
            },
        },
        addSteppedPrice: {
            form: '#__form_add_stepped',
            inputs: {
                min: 'inp-add-stepped-min-count',
                max: 'inp-add-stepped-max-count',
                price: 'inp-add-stepped-price',
                discount: 'inp-add-stepped-discounted-price',
            },
        },
        editSteppedPrice: {
            form: '#__form_edit_stepped',
            inputs: {
                min: 'inp-edit-stepped-min-count',
                max: 'inp-edit-stepped-max-count',
                price: 'inp-edit-stepped-price',
                discount: 'inp-edit-stepped-discounted-price',
            },
        },
        addDepositType: {
            form: '#__form_add_deposit_type',
            inputs: {
                title: 'inp-add-deposit-type-title',
                desc: 'inp-add-deposit-type-desc',
            },
        },
        editDepositType: {
            form: '#__form_edit_deposit_type',
            inputs: {
                title: 'inp-edit-deposit-type-title',
                desc: 'inp-edit-deposit-type-desc',
            },
        },
        chargeWallet: {
            form: '#__form_charge_wallet',
            inputs: {
                price: 'inp-charge-wallet-price',
                desc: 'inp-charge-wallet-desc',
            },
        },
        settingMain: {
            form: '#__form_setting_main',
            inputs: {
                logo: 'inp-setting-logo-img',
                logoWhite: 'inp-setting-logo-light-img',
                fav: 'inp-setting-fav-img',
                logoFooter: 'inp-setting-logo-footer',
                logoFooterWhite: 'inp-setting-logo-light-footer',
                title: 'inp-setting-title',
                desc: 'inp-setting-desc',
                tags: 'inp-setting-tags',
            },
        },
        settingBuy: {
            form: '#__form_setting_buy',
            inputs: {
                province: 'inp-setting-store-province',
                city: 'inp-setting-store-city',
                currCityPostPrice: 'inp-setting-current-city-post-price',
                minFreePrice: 'inp-setting-min-free-price',
            },
        },
        settingSMS: {
            form: '#__form_setting_sms',
            inputs: {
                activation: 'inp-setting-sms-activation',
                recoverPass: 'inp-setting-sms-recover-pass',
                buy: 'inp-setting-sms-buy',
                orderStatus: 'inp-setting-sms-order-status',
                walletCharge: 'inp-setting-sms-wallet-charge',
            },
        },
        settingContact: {
            form: '#__form_setting_contact',
            inputs: {
                mainPhone: 'inp-setting-main-phone',
                address: 'inp-setting-address',
                phones: 'inp-setting-phones',
                featuresTitle: 'inp-setting-features-title[]',
                featuresSubTitle: 'inp-setting-features-sub-title[]',
            },
        },
        settingOther: {
            form: '#__form_setting_other',
            inputs: {
                storeProvince: 'inp-setting-store-province',
                productPagination: 'inp-setting-product-pagination',
                blogPagination: 'inp-setting-blog-pagination',
            },
        },
        settingIndexPage: {
            form: '#__form_setting_index_page',
            inputs: {
                title: 'inp-setting-tabbed-slider-title',
                name: 'inp-setting-tabbed-slider-name[]',
                type: 'inp-setting-tabbed-slider-type[]',
                limit: 'inp-setting-tabbed-slider-limit[]',
                category: 'inp-setting-tabbed-slider-category[]',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            addAddress: {
                province: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /^\d{1,10}$/,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کد پستی را خالی نگذارید.',
                        format: 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            editAddress: {
                province: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /^\d{1,10}$/,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد کد پستی را خالی نگذارید.',
                        format: 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            addUnit: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد عنوان واحد را خالی نگذارید.',
                        maxlength: 'عنوان باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                sign: {
                    length: {
                        maximum: 250,
                        message: '^' + 'علامت باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            addBlog: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد تصویر را خالی نگذارید.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد عنوان را خالی نگذارید.',
                    },
                },
                abstract: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد توضیح مختصر را خالی نگذارید.',
                    },
                },
                desc: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد توضیحات را خالی نگذارید.',
                    },
                },
            },
            editBlog: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد تصویر را خالی نگذارید.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد عنوان را خالی نگذارید.',
                    },
                },
                abstract: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد توضیح مختصر را خالی نگذارید.',
                    },
                },
                desc: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد توضیحات را خالی نگذارید.',
                    },
                },
            },
            addFaq: {
                question: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد سؤال را خالی نگذارید.',
                    },
                },
                answer: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد پاسخ را خالی نگذارید.',
                    },
                },
                tags: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد برچسب را خالی نگذارید.',
                    },
                },
            },
            editFaq: {
                question: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد سؤال را خالی نگذارید.',
                    },
                },
                answer: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد پاسخ را خالی نگذارید.',
                    },
                },
                tags: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد برچسب را خالی نگذارید.',
                    },
                },
            },
            addColor: {
                name: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد نام را خالی نگذارید.',
                        maxlength: 'نام رنگ باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            editColor: {
                name: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد نام را خالی نگذارید.',
                        maxlength: 'نام رنگ باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            addSlide: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر اسلاید را انتخاب کنید.',
                    },
                },
                title: {
                    rules: {
                        maxlength: 250,
                    },
                    messages: {
                        maxlength: 'عنوان باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                subTitle: {
                    rules: {
                        maxlength: 250,
                    },
                    messages: {
                        maxlength: 'زیر عنوان باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            editSlide: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر اسلاید را انتخاب کنید.',
                    },
                },
                title: {
                    rules: {
                        maxlength: 250,
                    },
                    messages: {
                        maxlength: 'عنوان باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                subTitle: {
                    rules: {
                        maxlength: 250,
                    },
                    messages: {
                        maxlength: 'زیر عنوان باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            addBlogCategory: {
                name: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'نام دسته‌بندی را وارد کنید.',
                    },
                },
            },
            editBlogCategory: {
                name: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'نام دسته‌بندی را وارد کنید.',
                    },
                },
            },
            addBrand: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر برند را انتخاب کنید.',
                    },
                },
            },
            editBrand: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر برند را انتخاب کنید.',
                    },
                },
            },
            addInstagramImage: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر را انتخاب کنید.',
                    },
                },
            },
            editInstagramImage: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر را انتخاب کنید.',
                    },
                },
            },
            addBadge: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد عنوان را خالی نگذارید.',
                        maxlength: 'وضعیت باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            editBadge: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد عنوان را خالی نگذارید.',
                        maxlength: 'وضعیت باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            addCategory: {
                name: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 100,
                    },
                    messages: {
                        requiredNotEmpty: 'نام دسته‌بندی را خالی نگذارید.',
                        maxlength: 'نام دسته‌بندی باید حداکثر ۱۰۰ کاراکتر باشد.',
                    },
                },
                parent: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'والد دسته‌بندی را خالی نگذارید.',
                    },
                },
                priority: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد اولویت را خالی نگذارید.',
                    },
                },
            },
            editCategory: {
                name: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 100,
                    },
                    messages: {
                        requiredNotEmpty: 'نام دسته‌بندی را خالی نگذارید.',
                        maxlength: 'نام دسته‌بندی باید حداکثر ۱۰۰ کاراکتر باشد.',
                    },
                },
                parent: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'والد دسته‌بندی را خالی نگذارید.',
                    },
                },
                priority: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'فیلد اولویت را خالی نگذارید.',
                    },
                },
            },
            addCategoryImage: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر را انتخاب کنید.',
                    },
                },
                category: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'دسته‌بندی را انتخاب کنید.',
                    },
                },
            },
            editCategoryImage: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر را انتخاب کنید.',
                    },
                },
                category: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'دسته‌بندی را انتخاب کنید.',
                    },
                },
            },
            addStaticPage: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 300,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان را وارد کنید.',
                        maxlength: 'عنوان ضفحه باید حداکثر ۳۰۰ کاراکتر باشد.',
                    },
                },
                url: {
                    rules: {
                        format: /[a-zA-Z0-9-_\/]+/,
                    },
                    messages: {
                        format: 'آدرس صفحه باید از حروف و اعداد انگلیسی، خط تیره، آندرلاین و اسلش تشکیل شده باشد.',
                    },
                },
                desc: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'توضیحات را وارد کنید.',
                    },
                },
            },
            editStaticPage: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 300,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان را وارد کنید.',
                        maxlength: 'عنوان ضفحه باید حداکثر ۳۰۰ کاراکتر باشد.',
                    },
                },
                url: {
                    rules: {
                        format: /[a-zA-Z0-9-_\/]+/,
                    },
                    messages: {
                        format: 'آدرس صفحه باید از حروف و اعداد انگلیسی، خط تیره، آندرلاین و اسلش تشکیل شده باشد.',
                    },
                },
                desc: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'توضیحات را وارد کنید.',
                    },
                },
            },
            addCoupon: {
                code: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 20,
                        format: /[0-9a-zA-Z-_]+/,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان کوپن را وارد کنید.',
                        maxlength: 'کد کوپن باید حداکثر ۲۰ کاراکتر باشد.',
                        format: 'کد کوپن باید از حروف انگلیسی، اعداد، خط تیره و آندرلاین تشکیل شده باشد.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان کوپن را وارد کنید.',
                        maxlength: 'عنوان کوپن باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                price: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان کوپن را وارد کنید.',
                        format: 'قیمت باید از نوع عددی باشد.',
                    },
                },
                minPrice: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'کمترین قیمت اعمال موپن قیمت باید از نوع عددی باشد.',
                    },
                },
                maxPrice: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'بیشترین قیمت اعمال موپن قیمت باید از نوع عددی باشد.',
                    },
                },
                count: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'تعداد کوپن را وارد کنید.',
                        format: 'تعداد باید از نوع عددی باشد.',
                    },
                },
                useAfter: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'استفاده بعد از تعداد روز باید از نوع عددی باشد.',
                    },
                },
                start: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'تاریخ شروع نامعتبر است.',
                    },
                },
                end: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'تاریخ پایان نامعتبر است.',
                    },
                },
            },
            editCoupon: {
                code: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 20,
                        format: /[0-9a-zA-Z-_]+/,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان کوپن را وارد کنید.',
                        maxlength: 'کد کوپن باید حداکثر ۲۰ کاراکتر باشد.',
                        format: 'کد کوپن باید از حروف انگلیسی، اعداد، خط تیره و آندرلاین تشکیل شده باشد.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان کوپن را وارد کنید.',
                        maxlength: 'عنوان کوپن باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                price: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان کوپن را وارد کنید.',
                        format: 'قیمت باید از نوع عددی باشد.',
                    },
                },
                minPrice: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'کمترین قیمت اعمال موپن قیمت باید از نوع عددی باشد.',
                    },
                },
                maxPrice: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'بیشترین قیمت اعمال موپن قیمت باید از نوع عددی باشد.',
                    },
                },
                count: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'تعداد کوپن را وارد کنید.',
                        format: 'تعداد باید از نوع عددی باشد.',
                    },
                },
                useAfter: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'استفاده بعد از تعداد روز باید از نوع عددی باشد.',
                    },
                },
                start: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'تاریخ شروع نامعتبر است.',
                    },
                },
                end: {
                    rules: {
                        format: /\d+/,
                    },
                    messages: {
                        format: 'تاریخ پایان نامعتبر است.',
                    },
                },
            },
            addSecurityQuestion: {
                question: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'متن سؤال را وارد کنید.',
                    },
                },
            },
            editSecurityQuestion: {
                question: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'متن سؤال را وارد کنید.',
                    },
                },
            },
            addPaymentMethod: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر روش پرداخت را انتخاب کنید.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان روش پرداخت را وارد کنید.',
                        maxlength: 'عنوان روش پرداخت باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                method: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'نوع روش پرداخت را وارد کنید.',
                    },
                },
            },
            editPaymentMethod: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر روش پرداخت را انتخاب کنید.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان روش پرداخت را وارد کنید.',
                        maxlength: 'عنوان روش پرداخت باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                method: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'نوع روش پرداخت را وارد کنید.',
                    },
                },
            },
            addSendMethod: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر روش ارسال را انتخاب کنید.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان روش ارسال را وارد کنید.',
                        maxlength: 'عنوان روش ارسال باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                desc: {
                    rules: {
                        maxlength: 250,
                    },
                    messages: {
                        maxlength: 'توضیحات روش ارسال باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            editSendMethod: {
                image: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر روش ارسال را انتخاب کنید.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان روش ارسال را وارد کنید.',
                        maxlength: 'عنوان روش ارسال باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                desc: {
                    rules: {
                        maxlength: 250,
                    },
                    messages: {
                        maxlength: 'توضیحات روش ارسال باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            addFestival: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان جشنواره را وارد کنید.',
                        maxlength: 'عنوان جشنواره باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                start: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'تاریخ شروع را انتخاب کنید.',
                        format: 'تاریخ شروع نامعتبر است.',
                    },
                },
                end: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'تاریخ پایان را انتخاب کنید.',
                        format: 'تاریخ پایان نامعتبر است.',
                    },
                },
            },
            editFestival: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان جشنواره را وارد کنید.',
                        maxlength: 'عنوان جشنواره باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                start: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'تاریخ شروع را انتخاب کنید.',
                        format: 'تاریخ شروع نامعتبر است.',
                    },
                },
                end: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /\d+/,
                    },
                    messages: {
                        requiredNotEmpty: 'تاریخ پایان را انتخاب کنید.',
                        format: 'تاریخ پایان نامعتبر است.',
                    },
                },
            },
            addProductFestival: {
                product: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'محصول را انتخاب کنید.',
                    },
                },
            },
            modifyProductFestival: {
                category: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'دسته‌بندی را انتخاب کنید.',
                    },
                },
            },
            addSteppedPrice: {
                min: {
                    rules: {
                        format: /[0-9]*/,
                    },
                    messages: {
                        format: 'حداقل تعداد در سبد خرید باید از نوع عددی باشد.',
                    },
                },
                max: {
                    rules: {
                        format: /[0-9]*/,
                    },
                    messages: {
                        format: 'حداکثر تعداد در سبد خرید باید از نوع عددی باشد.',
                    },
                },
                price: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'قیمت را وارد کنید.',
                    },
                },
                discount: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'قیمت با تخفیف را وارد کنید.',
                    },
                },
            },
            editSteppedPrice: {
                min: {
                    rules: {
                        format: /[0-9]*/,
                    },
                    messages: {
                        format: 'حداقل تعداد در سبد خرید باید از نوع عددی باشد.',
                    },
                },
                max: {
                    rules: {
                        format: /[0-9]*/,
                    },
                    messages: {
                        format: 'حداکثر تعداد در سبد خرید باید از نوع عددی باشد.',
                    },
                },
                price: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'قیمت را وارد کنید.',
                    },
                },
                discount: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'قیمت با تخفیف را وارد کنید.',
                    },
                },
            },
            addDepositType: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان نوع تراکنش را وارد کنید.',
                        maxlength: 'عنوان نوع تراکنش باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                desc: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'توضیح نوع تراکنش را وارد کنید.',
                        maxlength: 'توضیح نوع تراکنش باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            editDepositType: {
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان نوع تراکنش را وارد کنید.',
                        maxlength: 'عنوان نوع تراکنش باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
                desc: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 250,
                    },
                    messages: {
                        requiredNotEmpty: 'توضیح نوع تراکنش را وارد کنید.',
                        maxlength: 'توضیح نوع تراکنش باید حداکثر ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            settingMain: {
                logo: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر لوگو را انتخاب کنید.',
                    },
                },
                logoWhite: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر لوگوی سفید را انتخاب کنید.',
                    },
                },
                fav: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر فاو آیکون را انتخاب کنید.',
                    },
                },
                logoFooter: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر لوگوی پاورقی را انتخاب کنید.',
                    },
                },
                logoFooterWhite: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'تصویر سفید لوگوی پاورقی را انتخاب کنید.',
                    },
                },
                title: {
                    rules: {
                        requiredNotEmpty: true,
                        maxlength: 50,
                    },
                    messages: {
                        requiredNotEmpty: 'عنوان سایت را وارد کنید.',
                        maxlength: 'عنوان سایت باید حداکثر ۵۰ کاراکتر باشد.',
                    },
                },
            },
            settingBuy: {
                province: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'انتخاب استان محل فروشگاه اجباری است.',
                    },
                },
                city: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'انتخاب شهر محل فروشگاه اجباری است.',
                    },
                },
            },
            settingSMS: {
                activation: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'متن پیامک فعالسازی حساب اجباری است.',
                    },
                },
                recoverPass: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'متن پیامک بازیابی کلمه عبور اجباری است.',
                    },
                },
                buy: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'متن پیامک خرید کالا اجباری است.',
                    },
                },
                orderStatus: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'متن پیامک تغییر وضعیت سفارش اجباری است.',
                    },
                },
                walletCharge: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'متن پیامک شارژ حساب کاربری اجباری است.',
                    },
                },
            },
            settingOther: {
                storeProvince: {
                    rules: {
                        requiredNotEmpty: true,
                    },
                    messages: {
                        requiredNotEmpty: 'استان محل فروشگاه اجباری است.',
                    },
                },
                productPagination: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /[0-9]*/,
                    },
                    messages: {
                        requiredNotEmpty: 'تعداد کالا در هر صفحه اجباری است.',
                        format: 'تعداد کالا در هر صفحه باید از نوع عددی باشد.',
                    },
                },
                blogPagination: {
                    rules: {
                        requiredNotEmpty: true,
                        format: /[0-9]*/,
                    },
                    messages: {
                        requiredNotEmpty: 'تعداد بلاگ در هر صفحه اجباری است.',
                        format: 'تعداد بلاگ در هر صفحه باید از نوع عددی باشد.',
                    },
                },
            },
        },
    });

    var core, variables;

    core = window.TheCore;
    variables = window.MyGlobalVariables;

    /**
     * Make Admin class global to window
     * @type {TheAdmin}
     */
    window.TheAdmin = (function (_super, c) {
        c.extend(Admin, _super);

        function Admin() {
            _super.call(this);
        }

        $.extend(Admin.prototype, {
            showLoader: function () {
                var id = core.idGenerator('loader');
                $('body').append(
                    $('<div class="position-fixed w-100 h-100" style="z-index: 1100; left: 0; top: 0; right: 0; bottom: 0;" id="' + id + '" />')
                        .append($('<div class="position-absolute bg-dark-alpha" style="left: 0; top: 0; right: 0; bottom: 0; z-index: 1;" />'))
                        .append($('<div class="theme_corners text-center" style="z-index: 2" />').append($('<div class="pace_activity" />')))
                );
                return id;
            },
            hideLoader: function (id) {
                if (id) {
                    id = $('#' + id);
                    if (id.length) {
                        id.fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                }
            },
        });

        return Admin;
    })(window.TheShopBase, core);

    $(function () {
        var
            admin,
            constraints,
            //-----
            loaderId,
            createLoader = true,
            //-----
            userIdInp,
            userId = null,
            festivalIdInp,
            festivalId = null,
            //-----
            currentTable = null,
            currentCategoryId = null,
            currentModal = null,
            editAddrId = null,
            editUnitId = null,
            editFAQId = null,
            editSlideId = null,
            editInstagramImageId = null,
            editBadgeId = null,
            addCategoryImageId = null,
            editCategoryImageId = null,
            editSecurityQuestionId = null,
            editDepositTypeId = null,
            //-----
            switcheryElements = [],
            //-----
            tableFabMenu,
            checkboxes;

        admin = new window.TheAdmin();

        //-----
        constraints = {
            addAddress: {
                name: variables.validation.common.name,
                mobile: variables.validation.common.mobile,
                province: variables.validation.constraints.addAddress.province,
                city: variables.validation.constraints.addAddress.city,
                postalCode: variables.validation.constraints.addAddress.postalCode,
                address: variables.validation.constraints.addAddress.address,
            },
            editAddress: {
                name: variables.validation.common.name,
                mobile: variables.validation.common.mobile,
                province: variables.validation.constraints.editAddress.province,
                city: variables.validation.constraints.editAddress.city,
                postalCode: variables.validation.constraints.editAddress.postalCode,
                address: variables.validation.constraints.editAddress.address,
            },
            addUnit: {
                title: variables.validation.constraints.addUnit.title,
                sign: variables.validation.constraints.addUnit.sign,
            },
            addBlog: {
                image: variables.validation.constraints.addBlog.image,
                title: variables.validation.constraints.addBlog.title,
                abstract: variables.validation.constraints.addBlog.abstract,
                desc: variables.validation.constraints.addBlog.desc,
            },
            editBlog: {
                image: variables.validation.constraints.editBlog.image,
                title: variables.validation.constraints.editBlog.title,
                abstract: variables.validation.constraints.editBlog.abstract,
                desc: variables.validation.constraints.editBlog.desc,
            },
            addFaq: {
                question: variables.validation.constraints.addFaq.question,
                answer: variables.validation.constraints.addFaq.answer,
                tags: variables.validation.constraints.addFaq.tags,
            },
            editFaq: {
                question: variables.validation.constraints.addFaq.question,
                answer: variables.validation.constraints.addFaq.answer,
                tags: variables.validation.constraints.addFaq.tags,
            },
            addColor: {
                name: variables.validation.constraints.addColor.name,
                color: variables.validation.constraints.addColor.color,
            },
            editColor: {
                name: variables.validation.constraints.editColor.name,
                color: variables.validation.constraints.editColor.color,
            },
            addSlide: {
                image: variables.validation.constraints.addSlide.image,
                title: variables.validation.constraints.addSlide.title,
                subTitle: variables.validation.constraints.addSlide.subTitle,
                link: variables.validation.common.link,
            },
            editSlide: {
                image: variables.validation.constraints.editSlide.image,
                title: variables.validation.constraints.editSlide.title,
                subTitle: variables.validation.constraints.editSlide.subTitle,
                link: variables.validation.common.link,
            },
            addBlogCategory: {
                name: variables.validation.constraints.addBlogCategory.name,
            },
            editBlogCategory: {
                name: variables.validation.constraints.editBlogCategory.name,
            },
            addNewsletter: {
                mobile: variables.validation.common.mobile,
            },
            addBrand: {
                image: variables.validation.constraints.addBrand.image,
                title: variables.validation.common.name,
                enTitle: variables.validation.common.enName,
            },
            editBrand: {
                image: variables.validation.constraints.editBrand.image,
                title: variables.validation.common.name,
                enTitle: variables.validation.common.enName,
            },
            addInstagramImage: {
                image: variables.validation.constraints.addInstagramImage.image,
                link: variables.validation.common.link,
            },
            editInstagramImage: {
                image: variables.validation.constraints.editInstagramImage.image,
                link: variables.validation.common.link,
            },
            addBadge: {
                title: variables.validation.constraints.addBadge.title,
                color: variables.validation.constraints.addBadge.color,
            },
            editBadge: {
                title: variables.validation.constraints.editBadge.title,
                color: variables.validation.constraints.editBadge.color,
            },
            addCategory: {
                name: variables.validation.constraints.addCategory.name,
                parent: variables.validation.constraints.addCategory.parent,
                priority: variables.validation.constraints.addCategory.priority,
            },
            editCategory: {
                name: variables.validation.constraints.editCategory.name,
                parent: variables.validation.constraints.editCategory.parent,
                priority: variables.validation.constraints.editCategory.priority,
            },
            addCategoryImage: {
                image: variables.validation.constraints.addCategoryImage.image,
                category: variables.validation.constraints.addCategoryImage.category,
            },
            editCategoryImage: {
                image: variables.validation.constraints.editCategoryImage.image,
                category: variables.validation.constraints.editCategoryImage.category,
            },
            addStaticPage: {
                title: variables.validation.constraints.addStaticPage.title,
                url: variables.validation.constraints.addStaticPage.url,
                desc: variables.validation.constraints.addStaticPage.desc,
            },
            editStaticPage: {
                title: variables.validation.constraints.editStaticPage.title,
                url: variables.validation.constraints.editStaticPage.url,
                desc: variables.validation.constraints.editStaticPage.desc,
            },
            addCoupon: {
                code: variables.validation.constraints.addCoupon.code,
                title: variables.validation.constraints.addCoupon.title,
                price: variables.validation.constraints.addCoupon.price,
                minPrice: variables.validation.constraints.addCoupon.minPrice,
                maxPrice: variables.validation.constraints.addCoupon.maxPrice,
                count: variables.validation.constraints.addCoupon.count,
                useAfter: variables.validation.constraints.addCoupon.useAfter,
                start: variables.validation.constraints.addCoupon.start,
                end: variables.validation.constraints.addCoupon.end,
            },
            editCoupon: {
                code: variables.validation.constraints.editCoupon.code,
                title: variables.validation.constraints.editCoupon.title,
                price: variables.validation.constraints.editCoupon.price,
                minPrice: variables.validation.constraints.editCoupon.minPrice,
                maxPrice: variables.validation.constraints.editCoupon.maxPrice,
                count: variables.validation.constraints.editCoupon.count,
                useAfter: variables.validation.constraints.editCoupon.useAfter,
                start: variables.validation.constraints.editCoupon.start,
                end: variables.validation.constraints.editCoupon.end,
            },
            addSecurityQuestion: {
                question: variables.validation.constraints.addSecurityQuestion.question,
            },
            editSecurityQuestion: {
                question: variables.validation.constraints.editSecurityQuestion.question,
            },
            addPaymentMethod: {
                image: variables.validation.constraints.addPaymentMethod.image,
                title: variables.validation.constraints.addPaymentMethod.title,
                method: variables.validation.constraints.addPaymentMethod.method,
            },
            editPaymentMethod: {
                image: variables.validation.constraints.editPaymentMethod.image,
                title: variables.validation.constraints.editPaymentMethod.title,
                method: variables.validation.constraints.editPaymentMethod.method,
            },
            addSendMethod: {
                image: variables.validation.constraints.addSendMethod.image,
                title: variables.validation.constraints.addSendMethod.title,
                desc: variables.validation.constraints.addSendMethod.desc,
            },
            editSendMethod: {
                image: variables.validation.constraints.editSendMethod.image,
                title: variables.validation.constraints.editSendMethod.title,
                desc: variables.validation.constraints.editSendMethod.desc,
            },
            addFestival: {
                title: variables.validation.constraints.addFestival.title,
                start: variables.validation.constraints.addFestival.start,
                end: variables.validation.constraints.addFestival.end,
            },
            editFestival: {
                title: variables.validation.constraints.editFestival.title,
                start: variables.validation.constraints.editFestival.start,
                end: variables.validation.constraints.editFestival.end,
            },
            addProductFestival: {
                product: variables.validation.constraints.addProductFestival.product,
                percent: variables.validation.common.percent,
            },
            modifyProductFestival: {
                category: variables.validation.constraints.modifyProductFestival.category,
                percent: variables.validation.common.percent,
            },
            addSteppedPrice: {
                min: variables.validation.constraints.addSteppedPrice.min,
                max: variables.validation.constraints.addSteppedPrice.max,
                price: variables.validation.constraints.addSteppedPrice.price,
                discount: variables.validation.constraints.addSteppedPrice.discount,
            },
            editSteppedPrice: {
                min: variables.validation.constraints.editSteppedPrice.min,
                max: variables.validation.constraints.editSteppedPrice.max,
                price: variables.validation.constraints.editSteppedPrice.price,
                discount: variables.validation.constraints.editSteppedPrice.discount,
            },
            addDepositType: {
                title: variables.validation.constraints.addDepositType.title,
                desc: variables.validation.constraints.addDepositType.desc,
            },
            editDepositType: {
                title: variables.validation.constraints.editDepositType.title,
                desc: variables.validation.constraints.editDepositType.desc,
            },
            settingMain: {
                logo: variables.validation.constraints.settingMain.logo,
                logoWhite: variables.validation.constraints.settingMain.logoWhite,
                logoFooter: variables.validation.constraints.settingMain.logoFooter,
                logoFooterWhite: variables.validation.constraints.settingMain.logoFooterWhite,
                fav: variables.validation.constraints.settingMain.fav,
                title: variables.validation.constraints.settingMain.title,
            },
            settingBuy: {
                province: variables.validation.constraints.settingBuy.province,
                city: variables.validation.constraints.settingBuy.city,
            },
            settingSMS: {
                activation: variables.validation.constraints.settingSMS.activation,
                recoverPass: variables.validation.constraints.settingSMS.recoverPass,
                buy: variables.validation.constraints.settingSMS.buy,
                orderStatus: variables.validation.constraints.settingSMS.orderStatus,
                walletCharge: variables.validation.constraints.settingSMS.walletCharge,
            },
            settingOther: {
                storeProvince: variables.validation.constraints.settingOther.storeProvince,
                productPagination: variables.validation.constraints.settingOther.productPagination,
                blogPagination: variables.validation.constraints.settingOther.blogPagination,
            },
        };

        //-----------------------------------------------------------------
        // copy to clipboard functionality
        /**
         * @see https://stackoverflow.com/a/30810322/12154893
         * @param text
         */
        function copyTextToClipboard(text) {
            var textArea = document.createElement("textarea");

            //
            // *** This styling is an extra step which is likely not required. ***
            //
            // Why is it here? To ensure:
            // 1. the element is able to have focus and selection.
            // 2. if the element was to flash render it has minimal visual impact.
            // 3. less flakyness with selection and copying which **might** occur if
            //    the textarea element is not visible.
            //
            // The likelihood is the element won't even render, not even a
            // flash, so some of these are just precautions. However in
            // Internet Explorer the element is visible whilst the popup
            // box asking the user for permission for the web page to
            // copy to the clipboard.
            //

            // Place in the top-left corner of screen regardless of scroll position.
            textArea.style.position = 'fixed';
            textArea.style.top = 0;
            textArea.style.left = 0;

            // Ensure it has a small width and height. Setting to 1px / 1em
            // doesn't work as this gives a negative w/h on some browsers.
            textArea.style.width = '2em';
            textArea.style.height = '2em';

            // We don't need padding, reducing the size if it does flash render.
            textArea.style.padding = 0;

            // Clean up any borders.
            textArea.style.border = 'none';
            textArea.style.outline = 'none';
            textArea.style.boxShadow = 'none';

            // Avoid flash of the white box if rendered for any reason.
            textArea.style.background = 'transparent';

            textArea.value = text;

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                var successful = document.execCommand('copy');
                if (!successful) {
                    console.warning('Oops, unable to copy');
                }
            } catch (err) {
                console.warning('Oops, unable to copy');
            }

            document.body.removeChild(textArea);
        }

        //-----------------------------------------------------------------

        $('.btn-show-loading').on('click' + variables.namespace, function () {
            loaderId = admin.showLoader();
        });

        $('.copy-to-clipboard').on('click' + variables.namespace, function () {
            var target;
            target = $($(this).attr('data-clipboard-target'));
            if (target.length) {
                copyTextToClipboard(target.last().val());
                admin.toasts.toast('متن در کلیپ بورد کپی شد.', {
                    type: variables.toasts.types.success,
                });
            }
        });

        $('.date_cleaner').each(function () {
            var mainElement, element;
            mainElement = $(this).attr('data-date-clean-element');
            mainElement = $(`[${mainElement}]`);

            $(this).on('click' + variables.namespace, function () {
                if ($(mainElement).length) {
                    element = $(mainElement).attr('data-alt-field');
                    if ($(element).length) {
                        $(mainElement).val('');
                        $(element).val('');
                    }
                }
            });
        });

        function initSearchString(table, search, isAjax) {
            table.api().search(search).draw();
            if (isAjax) {
                setTimeout(function () {
                    $(table).DataTable().ajax.reload();
                }, 0);
            }
        }

        function createDatatable(selector) {
            if ($().DataTable) {
                // Setting datatable defaults
                $.extend($.fn.dataTable.defaults, {
                    destroy: true,
                    autoWidth: false,
                    dom: '<"datatable-header"fl><"datatable-scroll-lg"t><"datatable-footer"ip>',
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    pageLength: 10,
                    language: {
                        processing: "در حال بارگذاری...",
                        search: '<span>جست‌و‌جو:</span> _INPUT_',
                        searchPlaceholder: 'کلمه مورد نظر را تایپ کنید ...',
                        lengthMenu: '<span>نمایش:</span> _MENU_',
                        paginate: {
                            'first': 'صفحه اول',
                            'last': 'صفحه آخر',
                            'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                            'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                        },
                        emptyTable: 'موردی یافت نشد.',
                        zeroRecords: 'مورد جستجو شده وجود ندارد.',
                        info: 'نمایش' + '<span class="text-primary ml-1 mr-1">_START_</span>' + 'تا' +
                            '<span class="text-primary ml-1 mr-1">_END_</span>' + 'از' + 'مجموع' + '<span class="text-primary ml-1 mr-1">_TOTAL_</span>' + 'رکورد',
                        infoEmpty: 'نمایش' + '<span class="text-primary ml-1 mr-1">0</span>' + 'تا' +
                            '<span class="text-primary ml-1 mr-1">0</span>' + 'از' + 'مجموع' + '<span class="text-primary ml-1 mr-1">0</span>' + 'رکورد',
                        infoFiltered: '(' + 'فیلتر شده از مجموع' + ' _MAX_ ' + 'رکورد' + ')',
                    }
                });

                /**
                 * Pipelining function for DataTables. To be used to the `ajax` option of DataTables
                 *
                 * @see https://datatables.net/examples/server_side/pipeline.html
                 * @param [opts]
                 * @returns {Function}
                 */
                $.fn.dataTable.pipeline = function (opts) {
                    // Configuration options
                    var conf = $.extend({
                        pages: 5,     // number of pages to cache
                        url: '',      // script url
                        data: null,   // function or object with parameters to send to the server
                                      // matching how `ajax.data` works in DataTables
                        method: 'GET' // Ajax HTTP method
                    }, opts);

                    // Private variables for storing the cache
                    var
                        cacheLower = -1,
                        cacheUpper = null,
                        cacheLastRequest = null,
                        cacheLastJson = null;

                    return function (request, drawCallback, settings) {
                        var
                            ajax = false,
                            requestStart = request.start,
                            drawStart = request.start,
                            requestLength = request.length,
                            requestEnd = requestStart + requestLength;

                        if (settings.clearCache) {
                            // API requested that the cache be cleared
                            ajax = true;
                            settings.clearCache = false;
                        } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                            // outside cached data - need to make a request
                            ajax = true;
                        } else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                            JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                            JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
                        ) {
                            // properties changed (ordering, columns, searching)
                            ajax = true;
                        }

                        // Store the request for checking next time around
                        cacheLastRequest = $.extend(true, {}, request);

                        if (ajax) {
                            // Need data from the server
                            if (requestStart < cacheLower) {
                                requestStart = requestStart - (requestLength * (conf.pages - 1));

                                if (requestStart < 0) {
                                    requestStart = 0;
                                }
                            }

                            cacheLower = requestStart;
                            cacheUpper = requestStart + (requestLength * conf.pages);

                            request.start = requestStart;
                            request.length = requestLength * conf.pages;

                            // Provide the same `data` options as DataTables.
                            if (typeof conf.data === 'function') {
                                // As a function it is executed with the data object as an arg
                                // for manipulation. If an object is returned, it is used as the
                                // data object to submit
                                var d = conf.data(request);
                                if (d) {
                                    $.extend(request, d);
                                }
                            } else if ($.isPlainObject(conf.data)) {
                                // As an object, the data given extends the default
                                $.extend(request, conf.data);
                            }

                            return $.ajax({
                                "type": conf.method,
                                "url": conf.url,
                                "data": request,
                                "dataType": "json",
                                "cache": false,
                                "success": function (json) {
                                    cacheLastJson = $.extend(true, {}, json);

                                    if (json.data) {
                                        if (cacheLower != drawStart) {
                                            json.data.splice(0, drawStart - cacheLower);
                                        }
                                        if (requestLength >= -1) {
                                            json.data.splice(requestLength, json.data.length);
                                        }
                                    } else {
                                        json.data = [];
                                        json.recordsFiltered = 0;
                                        json.recordsTotal = 0;
                                    }

                                    drawCallback(json);
                                },
                                // for debugging
                                "error": function (err) {
                                    console.log(err);
                                },
                            });
                        } else {
                            var json = $.extend(true, {}, cacheLastJson);
                            json.draw = request.draw; // Update the echo for each response
                            if (json.data) {
                                json.data.splice(0, requestStart - cacheLower);
                                json.data.splice(requestLength, json.data.length);
                            } else {
                                json.data = [];
                                json.recordsFiltered = 0;
                                json.recordsTotal = 0;
                            }

                            drawCallback(json);
                        }
                    }
                };

                // Register an API method that will empty the pipelined data, forcing an Ajax
                // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
                $.fn.dataTable.Api.register('clearPipeline()', function () {
                    return this.iterator('table', function (settings) {
                        settings.clearCache = true;
                    });
                });

                selector = selector ? $(selector) : $('.datatable-highlight');
                $.each(selector, function () {
                    var $this, table, url, search;
                    $this = $(this);

                    url = $this.attr('data-ajax-url');
                    search = $this.attr('data-search') || '';
                    if (url) {
                        table = $this.DataTable({
                            stateSave: true,
                            processing: true,
                            serverSide: true,
                            ajax: $.fn.dataTable.pipeline({
                                url: url,
                                method: 'POST',
                                pages: 5, // number of pages to cache
                            }),
                            deferRender: true,
                            initComplete: function () {
                                initSearchString(this, search, true);
                                datatableInitCompleteActions($this);
                            },
                        });
                    } else {
                        table = $this.DataTable({
                            stateSave: true,
                            initComplete: function () {
                                initSearchString(this, search, false);
                                datatableInitCompleteActions($this);
                            },
                        });
                    }

                    // reInitialize
                    table.on('draw', function () {
                        datatableInitCompleteActions($this, table);
                    });

                    // Highlighting rows and columns on mouseover
                    var lastIdx = null;
                    $('.datatable-highlight tbody').off('mouseover').on('mouseover', 'td', function () {
                        if (table.cell(this).index()) {
                            var colIdx = table.cell(this).index().column;

                            if (colIdx !== lastIdx) {
                                $(table.cells().nodes()).removeClass('active');
                                $(table.column(colIdx).nodes()).addClass('active');
                            }
                        }
                    }).off('mouseleave').on('mouseleave', function () {
                        $(table.cells().nodes()).removeClass('active');
                    });
                });
            }
        }

        function removeClonedElementEvent() {
            $('.__clone_remover_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function (e) {
                    e.stopPropagation();

                    $(this).parent().fadeOut(300, function () {
                        $(this).remove();
                    });
                });
        }

        function clearImageElementEvent() {
            $('.__image_cleaner_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function (e) {
                    e.stopPropagation();

                    removeImageFromPlaceholder($(this));
                });
        }

        removeClonedElementEvent();
        clearImageElementEvent();

        function afterCloneElement(copy) {
            if ($.efmInitPickerEvent) {
                $.efmInitPickerEvent();
            }

            removeClonedElementEvent();
            clearImageElementEvent();
            initializeAllPlugins();

            copy = $(copy);
            if (copy.length) {
                var switchery = copy.find('.form-check-input-switchery');
                if (switchery.length) {
                    switchery.removeAttr('data-switchery').parent().find('.switchery').remove();
                    new Switchery(switchery.get(0));
                }
            }
        }

        userIdInp = $('input[type="hidden"][data-user-id]');
        if (userIdInp.length) {
            userId = userIdInp.val();
        }

        festivalIdInp = $('input[type="hidden"][data-festival-id]');
        if (festivalIdInp.length) {
            festivalId = festivalIdInp.val();
        }

        $('.__send_data_through_request').each(function () {
            var $this, url, status;

            $this = $(this);
            url = $this.attr('data-internal-request-url');
            status = $this.attr('data-internal-request-status');

            if (url && status) {
                var data = new FormData();
                data.append('status', status);
                admin.toasts.confirm(null, function () {
                    admin.request(url, 'post', function () {
                        var _ = this;
                        if (_.type === variables.api.types.warning) {
                            admin.toasts.toast(_.data);
                        } else {
                            admin.toasts.toast(_.data, {
                                type: variables.toasts.types.success,
                            });
                        }
                    }, {
                        data: data,
                    }, true);
                });
            }
        });

        $('.__duplicator_btn').each(function (i, element) {
            $(element).on('click' + variables.namespace, function (e) {
                e.preventDefault();

                var
                    container,
                    sample,
                    clearableElements,
                    removableElements,
                    removableClasses,
                    removableClassesForAll,
                    dynamicIdElements,
                    dynamicIdAltElements,
                    copy;

                container = $(this).attr('data-container-element');
                sample = $(this).attr('data-sample-element');
                clearableElements = $(this).attr('data-clearable-elements');
                if (clearableElements && JSON.parse(clearableElements)) {
                    clearableElements = JSON.parse(clearableElements);
                }
                removableElements = $(this).attr('data-removable-elements');
                if (removableElements && JSON.parse(removableElements)) {
                    removableElements = JSON.parse(removableElements);
                }
                removableClasses = $(this).attr('data-removable-classes');
                if (removableClasses && JSON.parse(removableClasses)) {
                    removableClasses = JSON.parse(removableClasses);
                }
                removableClassesForAll = $(this).attr('data-removable-classes-for-all');
                if (removableClassesForAll && JSON.parse(removableClassesForAll)) {
                    removableClassesForAll = JSON.parse(removableClassesForAll);
                }
                dynamicIdElements = $(this).attr('data-dynamic-id-elements');
                if (dynamicIdElements && JSON.parse(dynamicIdElements)) {
                    dynamicIdElements = JSON.parse(dynamicIdElements);
                }
                dynamicIdAltElements = $(this).attr('data-alt-field');
                if (dynamicIdAltElements && JSON.parse(dynamicIdAltElements)) {
                    dynamicIdAltElements = JSON.parse(dynamicIdAltElements);
                }

                if (container && sample) {
                    container = $(container);
                    sample = $(sample);
                    if (container.length && sample.length) {
                        copy = sample.clone(!!$(this).attr('data-deep-copy'));
                        copy.removeAttr('id');

                        // clear element value inside cloned element
                        if (clearableElements) {
                            var i, len, el;
                            len = clearableElements.length;
                            for (i = 0; i < len; ++i) {
                                el = copy.find('[name="' + clearableElements[i] + '"]');
                                if (el.length) {
                                    el.each(function (i, elem) {
                                        if ($(elem).is(':radio')) {
                                            el.attr('checked', false).prop('checked', false);
                                            el.first().attr('checked', true).prop('checked', true);
                                        } else if (($(elem).is('input') || $(elem).is('textarea')) && !$(elem).is(':checkbox')) {
                                            $(elem).val('');
                                        } else if ($(elem).is('select')) {
                                            $(elem)
                                                .prop('selectedIndex', 0);
                                        }
                                    });
                                }
                            }
                        }

                        // remove removable elements
                        if (removableElements) {
                            len = removableElements.length;
                            for (i = 0; i < len; ++i) {
                                if ($(removableElements[i]).length) {
                                    copy.find(removableElements[i]).remove();
                                }
                            }
                        }

                        // remove removable classes
                        if (removableClasses) {
                            len = removableClasses.length;
                            for (i = 0; i < len; ++i) {
                                copy.removeClass(removableClasses[i]);
                            }
                        }

                        // remove removable classes for all elements in cloned element
                        if (removableClassesForAll) {
                            len = removableClassesForAll.length;
                            for (i = 0; i < len; ++i) {
                                copy.find('.' + removableClassesForAll[i]).removeClass(removableClassesForAll[i]);
                            }
                        }

                        var rndId, cpyEl;
                        // make dynamic id for some elements
                        if (dynamicIdElements) {
                            len = dynamicIdElements.length;
                            for (i = 0; i < len; ++i) {
                                cpyEl = copy.find('[name="' + dynamicIdElements[i] + '"]');
                                rndId = core.idGenerator('custom');
                                cpyEl.attr('id', rndId);
                            }
                        }

                        // make dynamic id for some element's alt field
                        if (dynamicIdAltElements) {
                            len = dynamicIdAltElements.length;
                            var altElem;
                            for (i = 0; i < len; ++i) {
                                cpyEl = copy.find('[name="' + dynamicIdAltElements[i] + '"]');
                                altElem = $(cpyEl.attr('data-alt-field'));
                                if (altElem.length) {
                                    rndId = core.idGenerator('custom');
                                    cpyEl.attr('data-alt-field', rndId);
                                    altElem.attr('id', rndId);
                                }
                            }
                        }

                        // append remove button if needed
                        if ($(this).attr('data-add-remove')) {
                            copy.append(
                                $('<div class="__clone_remover_btn btn btn-danger" />')
                                    .append($('<i class="icon-trash" aria-hidden="true" />'))
                            );
                        }

                        copy.find('.select2').remove();
                        copy.find('.bootstrap-tagsinput').remove();

                        container.append(copy);
                        afterCloneElement(copy);
                    }
                }
            });
        });

        /**
         * Instantiate switchery plugin with prevent multiple instantiation
         */
        function instantiateSwitchery() {
            if (typeof Switchery !== 'undefined') {
                // Initialize multiple switches
                var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
                elems.forEach(function (html) {
                    if (!$(html).attr('data-switchery')) {
                        switcheryElements[html] = new Switchery(html);
                    }
                });
            }
        }

        function initializeAllPlugins() {
            $('.__item_multi_status_changer_btn').each(function () {
                var
                    $this,
                    start,
                    min,
                    max,
                    labels,
                    placeholder;

                $this = $(this);
                start = $this.attr('data-slider-start-val');
                min = $this.attr('data-slider-min-val');
                max = $this.attr('data-slider-max-val');
                placeholder = $this.attr('data-slider-placeholder-element');
                labels = JSON.parse($this.attr('data-slider-labels'));

                noUiSlider.create(this, {
                    start: [start],
                    step: 1,
                    range: {
                        'min': [min],
                        'max': [max]
                    },
                    direction: $('html').attr('dir') == 'rtl' ? 'rtl' : 'ltr'
                });

                // Display values
                placeholder = $(placeholder);
                this.noUiSlider.on('update', function (values, handle) {
                    if (placeholder.length && labels[values[handle]]) {
                        placeholder.val(labels[values[handle]]);
                    }
                });
            });

            if ($().spectrum) {
                var cps = $(".colorpicker-show-input");
                cps.each(function () {
                    var $this = $(this);
                    var parent = $this.closest('.modal');
                    var obj = {
                        color: $this.attr('data-color') ? $this.attr('data-color') : "#2196f3",
                        cancelText: "لغو",
                        chooseText: "انتخاب",
                        preferredFormat: "hex",
                        showInput: true,
                        palette: [],
                        showPalette: true,
                        maxSelectionSize: 5,
                        clickoutFiresChange: false,
                        showInitial: true,
                    };

                    if (parent.length) {
                        obj['appendTo'] = parent;
                    }

                    $this.spectrum(obj)
                });
            }

            // uniform initialize
            if ($().uniform) {
                $.uniform.restore(".form-check-input-styled");
                $.uniform.restore(".form-input-styled");

                // remove all of it first
                do {
                    $('.form-check-input-styled').unwrap('span').unwrap('div');
                }
                while ($('.form-check-input-styled').closest('.uniform-checker').length > 0);
                do {
                    $('.form-input-styled').unwrap('span').unwrap('div');
                } while ($('.form-input-styled').closest('.uniform-checker').length > 0);

                // Default initialization
                $('.form-check-input-styled').uniform();

                $('.form-input-styled').uniform({
                    fileButtonClass: 'action btn bg-pink-400',
                    fileButtonHtml: 'انتخاب فایل',
                    filesButtonHtml: 'انتخاب فایل',
                    fileDefaultHtml: 'هیچ فایلی انتخاب نشده است',
                    resetDefaultHtml: 'بازنشانی',
                });
            }

            if ($.fn.persianDatepicker) {
                $('.myDatepickerWithEn').each(function () {
                    var $this = $(this);
                    $this.persianDatepicker({
                        "inline": false,
                        "format": !!$this.attr('data-format') ? $this.attr('data-format') : 'L',
                        "viewMode": "day",
                        "initialValue": true,
                        "minDate": 0,
                        "maxDate": 0,
                        "autoClose": false,
                        "position": "auto",
                        "onlyTimePicker": false,
                        "onlySelectOnDate": false,
                        "calendarType": "persian",
                        "altFormat": 'X',
                        "altField": $this.attr('data-alt-field') ? $this.attr('data-alt-field') : '',
                        "inputDelay": 800,
                        "observer": true,
                        "calendar": {
                            "persian": {
                                "locale": "fa",
                                "showHint": true,
                                "leapYearMode": "algorithmic"
                            },
                            "gregorian": {
                                "locale": "en",
                                "showHint": true
                            }
                        },
                        "navigator": {
                            "enabled": true,
                            "scroll": {
                                "enabled": true
                            },
                            "text": {
                                "btnNextText": "<",
                                "btnPrevText": ">"
                            }
                        },
                        "toolbox": {
                            "enabled": true,
                            "calendarSwitch": {
                                "enabled": true,
                                "format": "MMMM"
                            },
                            "todayButton": {
                                "enabled": true,
                                "text": {
                                    "fa": "امروز",
                                    "en": "Today"
                                }
                            },
                            "submitButton": {
                                "enabled": true,
                                "text": {
                                    "fa": "تایید",
                                    "en": "Submit"
                                }
                            },
                            "text": {
                                "btnToday": "امروز"
                            }
                        },
                        "timePicker": {
                            "enabled": !!$this.attr('data-time'),
                            "step": 1,
                            "hour": {
                                "enabled": true,
                                "step": null
                            },
                            "minute": {
                                "enabled": true,
                                "step": null
                            },
                            "second": {
                                "enabled": true,
                                "step": null
                            },
                            "meridian": {
                                "enabled": true
                            }
                        },
                        "dayPicker": {
                            "enabled": true,
                            "titleFormat": "YYYY MMMM"
                        },
                        "monthPicker": {
                            "enabled": true,
                            "titleFormat": "YYYY"
                        },
                        "yearPicker": {
                            "enabled": true,
                            "titleFormat": "YYYY"
                        },
                        "responsive": true
                    });
                });

                var altDP = $('.myAltDatepicker');
                $(altDP).each(function () {
                    var $this = $(this);
                    $this.persianDatepicker({
                        "inline": false,
                        "format": !!$this.attr('data-format') ? $this.attr('data-format') : 'L',
                        "viewMode": "day",
                        "initialValue": true,
                        "minDate": 0,
                        "maxDate": 0,
                        "autoClose": false,
                        "position": "auto",
                        "onlyTimePicker": false,
                        "onlySelectOnDate": false,
                        "calendarType": "persian",
                        "altFormat": 'X',
                        "altField": $this.attr('data-alt-field'),
                        "inputDelay": 800,
                        "observer": true,
                        "calendar": {
                            "persian": {
                                "locale": "fa",
                                "showHint": true,
                                "leapYearMode": "algorithmic"
                            },
                            "gregorian": {
                                "locale": "en",
                                "showHint": true
                            }
                        },
                        "navigator": {
                            "enabled": true,
                            "scroll": {
                                "enabled": true
                            },
                            "text": {
                                "btnNextText": "<",
                                "btnPrevText": ">"
                            }
                        },
                        "toolbox": {
                            "enabled": true,
                            "calendarSwitch": {
                                "enabled": false,
                                "format": "MMMM"
                            },
                            "todayButton": {
                                "enabled": true,
                                "text": {
                                    "fa": "امروز",
                                    "en": "Today"
                                }
                            },
                            "submitButton": {
                                "enabled": true,
                                "text": {
                                    "fa": "تایید",
                                    "en": "Submit"
                                }
                            },
                            "text": {
                                "btnToday": "امروز"
                            }
                        },
                        "timePicker": {
                            "enabled": !!$this.attr('data-time'),
                            "step": 1,
                            "hour": {
                                "enabled": true,
                                "step": null
                            },
                            "minute": {
                                "enabled": true,
                                "step": null
                            },
                            "second": {
                                "enabled": false,
                                "step": null
                            },
                            "meridian": {
                                "enabled": false
                            }
                        },
                        "dayPicker": {
                            "enabled": true,
                            "titleFormat": "YYYY MMMM"
                        },
                        "monthPicker": {
                            "enabled": true,
                            "titleFormat": "YYYY"
                        },
                        "yearPicker": {
                            "enabled": true,
                            "titleFormat": "YYYY"
                        },
                        "responsive": true
                    });
                });

                var rtAll, rfAll;

                rtAll = $(".range-to");
                rfAll = $(".range-from");

                rfAll.each(function (i) {
                    var to, from;
                    var rt, rf;

                    rt = rtAll.eq(i);
                    rf = $(this);

                    to = rt.persianDatepicker({
                        inline: false,
                        altField: rt.attr('data-alt-field'),
                        format: !!rt.attr('data-format') ? rt.attr('data-format') : 'L',
                        initialValue: true,
                        onSelect: function (unix) {
                            to.touched = true;
                            if (from && from.options && from.options.maxDate != unix) {
                                var cachedValue = from.getState().selected.unixDate;
                                from.options = {maxDate: unix};
                                if (from.touched) {
                                    from.setDate(cachedValue);
                                }
                            }
                        },
                        "minDate": 0,
                        "maxDate": 0,
                        "autoClose": true,
                        "position": "auto",
                        "onlyTimePicker": false,
                        "onlySelectOnDate": false,
                        "calendarType": "persian",
                        "altFormat": 'X',
                        "inputDelay": 800,
                        "observer": true,
                        "calendar": {
                            "persian": {
                                "locale": "fa",
                                "showHint": true,
                                "leapYearMode": "algorithmic"
                            },
                            "gregorian": {
                                "locale": "en",
                                "showHint": true
                            }
                        },
                        "timePicker": {
                            "enabled": !!rt.attr('data-time'),
                            "step": 1,
                            "hour": {
                                "enabled": true,
                                "step": null
                            },
                            "minute": {
                                "enabled": true,
                                "step": null
                            },
                            "second": {
                                "enabled": false,
                                "step": null
                            },
                            "meridian": {
                                "enabled": false
                            }
                        }
                    });
                    from = rf.persianDatepicker({
                        inline: false,
                        altField: rf.attr('data-alt-field'),
                        format: !!rf.attr('data-format') ? rf.attr('data-format') : 'L',
                        initialValue: true,
                        onSelect: function (unix) {
                            from.touched = true;
                            if (to && to.options && to.options.minDate != unix) {
                                var cachedValue = to.getState().selected.unixDate;
                                to.options = {minDate: unix};
                                if (to.touched) {
                                    to.setDate(cachedValue);
                                }
                            }
                        },
                        "minDate": 0,
                        "maxDate": 0,
                        "autoClose": true,
                        "position": "auto",
                        "onlyTimePicker": false,
                        "onlySelectOnDate": false,
                        "calendarType": "persian",
                        "altFormat": 'X',
                        "inputDelay": 800,
                        "observer": true,
                        "calendar": {
                            "persian": {
                                "locale": "fa",
                                "showHint": true,
                                "leapYearMode": "algorithmic"
                            },
                            "gregorian": {
                                "locale": "en",
                                "showHint": true
                            }
                        },
                        "timePicker": {
                            "enabled": !!rf.attr('data-time'),
                            "step": 1,
                            "hour": {
                                "enabled": true,
                                "step": null
                            },
                            "minute": {
                                "enabled": true,
                                "step": null
                            },
                            "second": {
                                "enabled": false,
                                "step": null
                            },
                            "meridian": {
                                "enabled": false
                            }
                        }
                    });
                });


                $('.myTimepicker').persianDatepicker({
                    "inline": false,
                    "format": "H:m:s",
                    "initialValue": true,
                    "autoClose": false,
                    "position": "auto",
                    "onlyTimePicker": true,
                    "onlySelectOnDate": false,
                    "calendarType": "persian",
                    "inputDelay": 800,
                    "observer": true,
                    "toolbox": {
                        "enabled": false
                    },
                    "timePicker": {
                        "enabled": false,
                        "step": "1",
                        "hour": {
                            "enabled": true,
                            "step": null
                        },
                        "minute": {
                            "enabled": true,
                            "step": null
                        },
                        "second": {
                            "enabled": true,
                            "step": null
                        },
                        "meridian": {
                            "enabled": false
                        }
                    },
                    "responsive": true
                });
            }

            // this must be after datatable
            if ($().select2) {
                var $this;

                // Basic example
                $('.form-control-select2').each(function () {
                    var obj, parent;
                    $this = $(this);
                    obj = {
                        minimumResultsForSearch: Infinity,
                    };
                    parent = $this.closest('.modal');
                    if (parent.length) {
                        obj['dropdownParent'] = parent;
                    }
                    if ($this.data('select2')) $this.select2("destroy");
                    $this.select2(obj);
                });

                // With search
                $('.form-control-select2-searchable').each(function () {
                    var obj, parent;
                    $this = $(this);
                    obj = {};
                    parent = $this.closest('.modal');
                    if (parent.length) {
                        obj['dropdownParent'] = parent;
                    }
                    if ($this.data('select2')) $this.select2("destroy");
                    $this.select2(obj);
                });

                //
                // Select with icons or colors
                //

                // Format icon
                var iconFormat = function (icon) {
                    var originalOption = icon.element;
                    if (!icon.id) {
                        return icon.text;
                    }
                    var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

                    return $icon;
                };

                // Format color
                var colorFormat = function (color) {
                    var originalOption = color.element;
                    if (!color.id) {
                        return color.text;
                    }
                    var $icon = color.text;
                    if ($(color.element).data('color')) {
                        $icon = "<div class='d-flex align-items-center'>" +
                            "<span class='d-inline-block mr-2 ml-1 rounded shadow-2'" +
                            " style='padding: 8px; background-color: " + $(color.element).data('color') +
                            "'></span>" + color.text +
                            "</div>";
                    }

                    return $icon;
                };

                // Initialize with options
                $('.form-control-select2-icons').each(function () {
                    var obj, parent;
                    $this = $(this);
                    obj = {
                        templateResult: iconFormat,
                        templateSelection: iconFormat,
                        escapeMarkup: function (m) {
                            return m;
                        }
                    };
                    parent = $this.closest('.modal');
                    if (parent.length) {
                        obj['dropdownParent'] = parent;
                    }
                    if ($this.data('select2')) $this.select2("destroy");
                    $this.select2(obj);
                });

                // Initialize with options
                $('.form-control-select2-colors').each(function () {
                    var obj, parent;
                    $this = $(this);
                    obj = {
                        templateResult: colorFormat,
                        templateSelection: colorFormat,
                        escapeMarkup: function (m) {
                            return m;
                        }
                    };
                    parent = $this.closest('.modal');
                    if (parent.length) {
                        obj['dropdownParent'] = parent;
                    }
                    if ($this.data('select2')) $this.select2("destroy");
                    $this.select2(obj);
                });

                // Initialize
                $('.dataTables_length select').each(function () {
                    var obj, parent;
                    $this = $(this);
                    obj = {
                        minimumResultsForSearch: Infinity,
                        dropdownAutoWidth: true,
                        width: 'auto'
                    };
                    parent = $this.closest('.modal');
                    if (parent.length) {
                        obj['dropdownParent'] = parent;
                    }
                    if ($this.data('select2')) $this.select2("destroy");
                    $this.select2(obj);
                });
            }

            if ($().tagsinput) {
                $('.tags-input').each(function () {
                    var obj, maxTags, $this;
                    $this = $(this);
                    obj = {};

                    // check for max tags
                    maxTags = $this.attr('data-max-tags');
                    maxTags = maxTags && !isNaN(parseInt(maxTags, 10)) ? parseInt(maxTags, 10) : null;
                    if (maxTags) {
                        obj['maxTags'] = maxTags;
                    }

                    if ($this.data('tagsinput')) $this.tagsinput("destroy");
                    setTimeout(function () {
                        $this.tagsinput(obj);
                    }, 1);
                });
            }

            // Lazy loader (pictures, videos, etc.)
            if ($.fn.lazy) {
                $('.lazy').lazy({
                    effect: "fadeIn",
                    effectTime: 800,
                    threshold: 50,
                    // callback
                    afterLoad: function (element) {
                        $(element).css({'background': 'none'});
                    }
                });
            }

            if ($().maxlength) {
                // Basic example
                $('.maxlength').maxlength();

                $('.maxlength-placeholder').maxlength({
                    alwaysShow: true,
                    warningClass: 'text-success form-text',
                    limitReachedClass: 'text-danger form-text',
                    separator: ' از ',
                    preText: 'شما دارای ',
                    postText: ' کاراکتر هستید',
                    validate: true,
                });
            }
        }

        //
        // initialize remote select
        //

        // Format displayed data
        function formatData(data) {
            if (data.loading) return data.text;

            var markup = '<div class="select2-result-repository clearfix">';

            if (data.image) {
                markup += '<div class="select2-result-repository__avatar"><img src="' + data.image + '" alt="' + data.text + '" /></div>';
            }

            markup += '<div class="select2-result-repository__meta">';
            markup += '<div class="select2-result-repository__title">' + data.text + '</div>';
            markup += '</div></div>';

            return markup;
        }

        // Format selection
        function formatDataSelection(data) {
            return data.text;
        }

        // Initialize
        $('.select-remote-data').each(function () {
            var $this, placeholder, url, limit;

            $this = $(this);
            placeholder = $this.attr('data-remote-placeholder');
            url = $this.attr('data-remote-url');
            limit = $this.attr('data-remote-limit');
            limit = !isNaN(parseInt(limit, 10)) ? parseInt(limit, 10) : 15;

            if (url) {
                $this.select2({
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page || 1,
                                length: limit,
                            };
                        },
                        processResults: function (data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            params.page = params.page || 1;

                            return {
                                results: data.results || {},
                                pagination: {
                                    more: (params.page * limit) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    }, // let our custom formatter work
                    placeholder: placeholder || 'حداقل یک کاراکتر وارد کنید',
                    minimumInputLength: 1,
                    templateResult: formatData, // omitted for brevity, see the source of this page
                    templateSelection: formatDataSelection, // omitted for brevity, see the source of this page
                    language: {
                        errorLoading: function () {
                            return "خطا در بارگذاری آیتم‌ها."
                        },
                        inputTooShort: function (e) {
                            var t = e.minimum - e.input.length,
                                n = "لطفا حداقل " + t + " یا تعداد بیشتری کاراکتر برای انجام جستجو وارد نمایید";
                            return n
                        },
                        loadingMore: function () {
                            return "بارگذاری بیشتر…"
                        },
                        noResults: function () {
                            return 'هیچ موردی پیدا نشد!';
                        },
                        searching: function () {
                            return 'در حال جستجو…';
                        },
                    },
                });
            }
        });

        function switcheryStatusChange(status, pub) {
            if (core.isChecked(pub)) {
                if (!status.is(':checked')) {
                    status
                        .parent()
                        .find('.switchery')
                        .click();
                }
                status
                    .attr('checked', 'checked')
                    .prop('checked', 'checked');
            } else {
                if (status.is(':checked')) {
                    status
                        .parent()
                        .find('.switchery')
                        .click();
                }
                status
                    .removeAttr('checked')
                    .prop('checked', false);
            }
        }

        /**
         * @param el
         * @param image
         */
        function addImageToPlaceholder(el, image) {
            image = core.trim(image, '/');
            $(el)
                .closest('.__file_picker_handler')
                .addClass('has-image')
                .append($('<img class="img-placeholder-image" src="' + variables.url.image + '/' + image + '" alt="the image">'))
        }

        /**
         * @param el
         */
        function removeImageFromPlaceholder(el) {
            $(el)
                .closest('.__file_picker_handler')
                .removeClass('has-image')
                .find('.img-placeholder-image')
                .remove();
        }

        /**
         * Delete anything from a specific url
         *
         * @param btn
         * @param [table]
         */
        function deleteOperation(btn, table) {
            var url, id;
            url = $(btn).attr('data-remove-url');
            id = $(btn).attr('data-remove-id');

            if (url && id) {
                admin.deleteItem(url + id, function () {
                    if (table) {
                        $(table).DataTable().ajax.reload();
                        createDatatable(table);
                    }
                }, {}, true);
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function changeOperation(btn, table) {
            var url, id;
            btn = $(btn);
            url = btn.attr('data-change-status-url');
            id = btn.attr('data-change-status-id');
            var v = btn.is(':checked') ? 1 : 0;

            if (url && id) {
                var data = new FormData();
                data.append('status', v);

                admin.toasts.confirm(null, function () {
                    admin.request(url + id, 'post', function () {
                        var _ = this;
                        if (_.type === variables.api.types.warning) {
                            admin.toasts.toast(_.data);
                        } else {
                            admin.toasts.toast(_.data, {
                                type: variables.toasts.types.success,
                            });
                        }
                    }, {
                        data: data,
                    }, true);
                }, null, function () {
                    if (v) {
                        btn.attr('checked', false)
                            .prop('checked', false);
                    } else {
                        btn.attr('checked', 'checked')
                            .prop('checked', 'checked');
                    }
                });
            }
        }

        /**
         * @param handlebar
         * @param value
         * @param [table]
         */
        function changeMultiOperation(handlebar, value, table) {
            var url, val;

            url = $this.attr('data-slider-url');
            val = parseInt(value, 10);

            if (url && !isNaN(val)) {
                var data = new FormData();
                data.append('status', val);

                admin.toasts.confirm(null, function () {
                    // $(handlebar.target),
                    admin.request(url, 'post', function () {
                        var _ = this;
                        if (_.type === variables.api.types.warning) {
                            admin.toasts.toast(_.data);
                        } else {
                            admin.toasts.toast(_.data, {
                                type: variables.toasts.types.success,
                            });
                        }
                    }, {
                        data: data,
                    }, true);
                });
            }
        }

        // change status button click event
        $('.__item_status_changer_btn')
            .off('click')
            .on('click', function () {
                changeOperation($(this));
            });

        /**
         *
         * @param btn
         * @param [table]
         */
        function editAddressBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_address_edit');
            // clear element after each call
            $(variables.elements.editAddress.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.address.get + '/' + userId + '/' + id, 'get', function () {
                    var _ = this;
                    var provincesSelect = $('select[name="' + variables.elements.editAddress.inputs.province + '"]'),
                        citiesSelect = $(provincesSelect.attr('data-city-select-target'));
                    if (core.objSize(_.data) && provincesSelect.length && citiesSelect.length) {
                        currentTable = table;
                        editAddrId = id;
                        //-----
                        admin.loadProvinces(provincesSelect.attr('data-current-province', _.data['province_id']));
                        admin.loadCities(citiesSelect.attr('data-current-city', _.data['city_id']));
                        currentModal.find('[name="' + variables.elements.editAddress.inputs.province + '"]').val(_.data['full_name']);
                        currentModal.find('[name="' + variables.elements.editAddress.inputs.mobile + '"]').val(_.data['mobile']);
                        currentModal.find('[name="' + variables.elements.editAddress.inputs.postalCode + '"]').val(_.data['postal_code']);
                        currentModal.find('[name="' + variables.elements.editAddress.inputs.address + '"]').val(_.data['address']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editUnitBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_edit_unit');
            // clear element after each call
            $(variables.elements.editUnit.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.unit.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        editUnitId = id;
                        //-----
                        currentModal.find('[name="' + variables.elements.editUnit.inputs.title + '"]').val(_.data['title']);
                        currentModal.find('[name="' + variables.elements.editUnit.inputs.sign + '"]').val(_.data['sign']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editFAQBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_edit_faq');
            // clear element after each call
            $(variables.elements.editFaq.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.faq.get + '/' + id, 'get', function () {
                    var
                        _ = this,
                        status = currentModal.find('[name="' + variables.elements.editFaq.inputs.status + '"]');
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        editFAQId = id;
                        //-----
                        currentModal.find('[name="' + variables.elements.editFaq.inputs.question + '"]').val(_.data['question']);
                        currentModal.find('[name="' + variables.elements.editFaq.inputs.answer + '"]').html(_.data['answer']);
                        currentModal.find('[name="' + variables.elements.editFaq.inputs.tags + '"]').val(_.data['tags']);
                        switcheryStatusChange(status, _.data['publish']);
                        initializeAllPlugins();
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editSlideBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_edit_slide');
            // clear element after each call
            $(variables.elements.editSlide.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.slider.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        editSlideId = id;
                        //-----
                        var imgEl = currentModal.find('[name="' + variables.elements.editSlide.inputs.image + '"]');
                        imgEl.val(_.data['image']);
                        addImageToPlaceholder(imgEl, _.data['image']);
                        currentModal.find('[name="' + variables.elements.editSlide.inputs.title + '"]').val(_.data['title']);
                        currentModal.find('[name="' + variables.elements.editSlide.inputs.subTitle + '"]').val(_.data['note']);
                        currentModal.find('[name="' + variables.elements.editSlide.inputs.link + '"]').val(_.data['link']);
                        currentModal.find('[name="' + variables.elements.editSlide.inputs.priority + '"]').val(_.data['priority']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editInstagramImageBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_edit_ins_image');
            // clear element after each call
            $(variables.elements.editInstagramImage.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.instagram.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        editInstagramImageId = id;
                        //-----
                        var imgEl = currentModal.find('[name="' + variables.elements.editInstagramImage.inputs.image + '"]');
                        imgEl.val(_.data['image']);
                        addImageToPlaceholder(imgEl, _.data['image']);
                        currentModal.find('[name="' + variables.elements.editInstagramImage.inputs.link + '"]').val(_.data['link']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editBadgeBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_edit_badges');
            // clear element after each call
            $(variables.elements.editBadge.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.badge.get + '/' + id, 'get', function () {
                    var
                        _ = this,
                        canReturnOrder = currentModal.find('[name="' + variables.elements.editBadge.inputs.canReturnOrder + '"]');
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        editBadgeId = id;
                        //-----
                        currentModal.find('[name="' + variables.elements.editBadge.inputs.title + '"]').val(_.data['title']);
                        currentModal
                            .find('[name="' + variables.elements.editBadge.inputs.color + '"]')
                            .val(_.data['color'])
                            .spectrum("set", _.data['color']);
                        switcheryStatusChange(canReturnOrder, _.data['can_return_order']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function addCategoryImageBtnClick(btn, table) {
            var cId, addModal;
            cId = $(btn).attr('data-add-category-id');
            addModal = $('#modal_form_add_cat_img');
            // clear element after each call
            $(variables.elements.addCategoryImage.form).get(0).reset();
            if (cId && addModal.length) {
                addCategoryImageId = cId;
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editCategoryImageBtnClick(btn, table) {
            var id, cId;
            id = $(btn).attr('data-edit-id');
            cId = $(btn).attr('data-edit-category-id');
            currentModal = $('#modal_form_edit_cat_img');
            // clear element after each call
            $(variables.elements.editCategoryImage.form).get(0).reset();
            if (id && cId && currentModal.length) {
                admin.request(variables.url.categoryImage.get + '/' + cId + '/' + id, 'get', function () {
                    var _ = this;
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        currentCategoryId = id;
                        editCategoryImageId = cId;
                        //-----
                        var imgEl = currentModal.find('[name="' + variables.elements.editCategoryImage.inputs.image + '"]');
                        imgEl.val(_.data['image']);
                        addImageToPlaceholder(imgEl, _.data['image']);
                        currentModal.find('[name="' + variables.elements.editCategoryImage.inputs.category + '"]').val(_.data['link']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editSecurityQuestionBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_edit_sec_question');
            // clear element after each call
            $(variables.elements.editSecurityQuestion.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.securityQuestion.get + '/' + id, 'get', function () {
                    var
                        _ = this,
                        status = currentModal.find('[name="' + variables.elements.editSecurityQuestion.inputs.status + '"]');
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        editSecurityQuestionId = id;
                        //-----
                        currentModal.find('[name="' + variables.elements.editSecurityQuestion.inputs.question + '"]').val(_.data['question']);
                        switcheryStatusChange(status, _.data['publish']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editDepositTypeBtnClick(btn, table) {
            var id;
            id = $(btn).attr('data-edit-id');
            currentModal = $('#modal_form_edit_type');
            // clear element after each call
            $(variables.elements.editDepositType.form).get(0).reset();
            if (id && currentModal.length) {
                admin.request(variables.url.depositType.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (core.objSize(_.data)) {
                        currentTable = table;
                        editDepositTypeId = id;
                        //-----
                        currentModal.find('[name="' + variables.elements.editDepositType.inputs.title + '"]').val(_.data['title']);
                        currentModal.find('[name="' + variables.elements.editDepositType.inputs.desc + '"]').val(_.data['desc']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function loadOrderInfo(btn, table) {
            var id;
            id = $(btn).attr('data-ajax-order-info');
            currentModal = $('#modal_form_receiver_detail');
            // make all data to error loading
            if (id && currentModal.length) {
                $('#__receiver_info_full_name,#__receiver_info_phone,#__receiver_info_province,#__receiver_info_city,#__receiver_info_postal_code,#__receiver_info_address')
                    .html('در حال بارگذاری');

                admin.request(variables.url.orders.info + '/' + id, 'get', function () {
                    var _ = this;
                    if (core.objSize(_.data)) {
                        currentModal.find('#__receiver_info_full_name')
                            .text(_.data['receiver_name'] ? _.data['receiver_name'] : '<i class="icon-minus2" aria-hidden="true"></i>');
                        currentModal.find('#__receiver_info_phone')
                            .text(_.data['receiver_mobile'] ? _.data['receiver_mobile'] : '<i class="icon-minus2" aria-hidden="true"></i>');
                        currentModal.find('#__receiver_info_province')
                            .text(_.data['province'] ? _.data['province'] : '<i class="icon-minus2" aria-hidden="true"></i>');
                        currentModal.find('#__receiver_info_city')
                            .text(_.data['city'] ? _.data['city'] : '<i class="icon-minus2" aria-hidden="true"></i>');
                        currentModal.find('#__receiver_info_postal_code')
                            .text(_.data['postal_code'] ? _.data['postal_code'] : '<i class="icon-minus2" aria-hidden="true"></i>');
                        currentModal.find('#__receiver_info_address')
                            .text(_.data['address'] ? _.data['address'] : '<i class="icon-minus2" aria-hidden="true"></i>');
                    }
                });
            }
        }

        /**
         * Checkbox actions for datatable multiple selection
         */

        tableFabMenu = $('#tableFabMenu');
        checkboxes = [];

        function addItemToChkArr(chk) {
            var id;
            chk.each(function () {
                id = $(this).attr('data-product-id');
                id = parseInt(id, 10);
                if (id && id !== -1 && $.inArray(id, checkboxes) === -1) {
                    checkboxes.push(id);
                    chk.closest('tr').addClass('selected');
                    showTableFabMenu();
                }
            });
            // update selected items count
            tableFabMenu.find('.selItemsCount').text(checkboxes.length);
        }

        function removeItemFromChkArr(chk) {
            var id;
            chk.each(function () {
                id = $(this).attr('data-product-id');
                id = parseInt(id, 10);
                if (id && id !== -1 && $.inArray(id, checkboxes) !== -1) {
                    checkboxes.splice(checkboxes.indexOf(id), 1);
                    chk.closest('tr').removeClass('selected');
                }
                if (!checkboxes.length) {
                    hideTableFabMenu();
                }
            });
            // update selected items count
            tableFabMenu.find('.selItemsCount').text(checkboxes.length);
        }

        function checkItemChk(chkContainer) {
            chkContainer.find('input[type="checkbox"]').attr('checked', 'checked').prop('checked', 'checked');
            chkContainer.find('span').addClass('checked');
            chkContainer.closest('tr').addClass('selected');

            if (checkboxes.length) {
                showTableFabMenu();
            } else {
                hideTableFabMenu();
            }
        }

        function uncheckItemChk(chkContainer) {
            chkContainer.find('input[type="checkbox"]').removeAttr('checked').prop('checked', 'false');
            chkContainer.find('span').removeClass('checked');
            chkContainer.closest('tr').removeClass('selected');

            if (checkboxes.length) {
                showTableFabMenu();
            } else {
                hideTableFabMenu();
            }
        }

        function showTableFabMenu() {
            if (tableFabMenu.is(':hidden')) {
                tableFabMenu.hide();
            }
            tableFabMenu.removeClass('hide').stop().fadeIn(300);
        }

        function hideTableFabMenu() {
            tableFabMenu.stop().fadeOut(300, function () {
                $(this).hide().addClass('hide');
            });
        }

        hideTableFabMenu();

        /**
         * Actions to take after a datatable initialize/reinitialize
         */
        function datatableInitCompleteActions(table, dtTable) {
            // Initialize select inputs in datatable
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                dropdownAutoWidth: true,
                width: 'auto'
            });

            // reInstantiate switchery plugin
            instantiateSwitchery();

            // reinitialize dropdown
            $('[data-toggle="dropdown"]').dropdown();

            // reinitialize lazy plugin for images
            $('.lazy').lazy({
                effect: "fadeIn",
                effectTime: 800,
                threshold: 0,
                // callback
                afterLoad: function (element) {
                    $(element).css({'background': 'none'});
                }
            });

            // delete button click event
            $('.__item_remover_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    deleteOperation($(this), table);
                });

            // change status button click event
            $('.__item_status_changer_btn')
                .off('change' + variables.namespace)
                .on('change' + variables.namespace, function () {
                    changeOperation($(this), table);
                });

            // change multi status button click event
            $('.__item_multi_status_changer_btn').each(function () {
                this.noUiSlider.on('change', function (values, handle) {
                    changeMultiOperation($(this), values[handle], table);
                });
            });

            // edit address button click event
            $('.__item_address_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editAddressBtnClick($(this), table);
                });

            // edit unit button click event
            $('.__item_unit_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editUnitBtnClick($(this), table);
                });

            // edit faq button click event
            $('.__item_faq_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editFAQBtnClick($(this), table);
                });

            // edit slide button click event
            $('.__item_slider_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editSlideBtnClick($(this), table);
                });

            // edit instagram button click event
            $('.__item_instagram_image_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editInstagramImageBtnClick($(this), table);
                });

            // edit badge button click event
            $('.__item_badge_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editBadgeBtnClick($(this), table);
                });

            // add category image button click event
            $('.__item_cat_img_add_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    addCategoryImageBtnClick($(this), table);
                });

            // edit category image button click event
            $('.__item_cat_img_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editCategoryImageBtnClick($(this), table);
                });

            // edit security question button click event
            $('.__item_sec_q_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editSecurityQuestionBtnClick($(this), table);
                });

            // show order information in a modal
            $('.__item_order_info_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    loadOrderInfo($(this), table);
                });

            // edit deposit type button click event
            $('.__item_deposit_type_editor_btn')
                .off('click' + variables.namespace)
                .on('click' + variables.namespace, function () {
                    editDepositTypeBtnClick($(this), table);
                });

            initializeAllPlugins();

            if (dtTable) {
                dtTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var row = this;
                    $.each(checkboxes, function (idx, val) {
                        if (parseInt(val, 10) === parseInt(data.id, 10)) {
                            checkItemChk($(row.node()).find('.select-item-chk'));
                        }
                    });
                });
            }

            // select all items feature
            $('#SelectAllItems').off('click' + variables.namespace).on('click' + variables.namespace, function () {
                var chk2;
                var chk = $(this).find('input[type="checkbox"]');
                if (chk) {
                    if (chk.is(':checked')) {
                        $('.select-item-chk').each(function () {
                            chk2 = $(this).find('input[type="checkbox"]');
                            if (chk2) {
                                addItemToChkArr(chk2);
                                checkItemChk($(this));
                            }
                        });
                    } else {
                        $('.select-item-chk').each(function () {
                            chk2 = $(this).find('input[type="checkbox"]');
                            if (chk2) {
                                removeItemFromChkArr(chk2);
                                uncheckItemChk($(this));
                            }
                        });
                    }
                }
            });

            // make all items selectable if it has that feature
            $('.select-item-chk').off('click' + variables.namespace).on('click' + variables.namespace, function () {
                var chk = $(this).find('input[type="checkbox"]');
                if (chk) {
                    if (chk.is(':checked')) {
                        addItemToChkArr(chk);
                    } else {
                        removeItemFromChkArr(chk);
                    }
                }
            });
        }

        // Add bottom spacing if reached bottom,
        // to avoid footer overlapping
        // -------------------------
        $(window).on('scroll', function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 40) {
                $('.fab-menu-bottom-left, .fab-menu-bottom-right').addClass('reached-bottom');
            } else {
                $('.fab-menu-bottom-left, .fab-menu-bottom-right').removeClass('reached-bottom');
            }
        });

        // instantiate switchery plugin
        instantiateSwitchery();

        initializeAllPlugins();

        createDatatable();

        //---------------------------------------------------------------
        // REPORT USER SECTION
        //---------------------------------------------------------------
        var
            the_query_builder = null,
            query_builder_reset_btn,
            query_builder_filter_btn,
            //-----
            filterUrl,
            filterClearUrl,
            excelExportUrl;

        //***************************
        //******* User Report *******
        //***************************
        if ($('#builder-basic-user').length) {
            the_query_builder = $('#builder-basic-user');
            query_builder_reset_btn = $('#btn-reset-user');
            query_builder_filter_btn = $('#btn-filter-user');
            filterUrl = variables.url.report.user.filter;
            filterClearUrl = variables.url.report.user.filterClear;
            excelExportUrl = variables.url.report.user.excelExport;
        }

        //***************************
        //***** PRODUCT Report ******
        //***************************
        if ($('#builder-basic-product').length) {
            the_query_builder = $('#builder-basic-product');
            query_builder_reset_btn = $('#btn-reset-product');
            query_builder_filter_btn = $('#btn-filter-product');
            filterUrl = variables.url.report.product.filter;
            filterClearUrl = variables.url.report.product.filterClear;
            excelExportUrl = variables.url.report.product.excelExport;
        }

        //***************************
        //****** Order Report *******
        //***************************
        if ($('#builder-basic-order').length) {
            the_query_builder = $('#builder-basic-order');
            query_builder_reset_btn = $('#btn-reset-order');
            query_builder_filter_btn = $('#btn-filter-order');
            filterUrl = variables.url.report.order.filter;
            filterClearUrl = variables.url.report.order.filterClear;
            excelExportUrl = variables.url.report.order.excelExport;
        }

        //***************************
        //****** Wallet Report ******
        //***************************
        if ($('#builder-basic-wallet').length) {
            the_query_builder = $('#builder-basic-wallet');
            query_builder_reset_btn = $('#btn-reset-wallet');
            query_builder_filter_btn = $('#btn-filter-wallet');
            filterUrl = variables.url.report.wallet.filter;
            filterClearUrl = variables.url.report.wallet.filterClear;
            excelExportUrl = variables.url.report.wallet.excelExport;
        }

        if (
            core.isDefined(the_query_builder) &&
            the_query_builder.length &&
            core.isDefined(window.report_variable_filters)
        ) {
            // create query builder object
            the_query_builder.queryBuilder({
                filters: window.report_variable_filters,
                lang: 'fa-IR',
            });

            query_builder_filter_btn.on('click', function () {
                var result = the_query_builder.queryBuilder('getSQL', 'named');

                if (result && result.sql.length) {
                    // do ajax
                    if (createLoader) {
                        createLoader = false;
                        loaderId = admin.showLoader();
                    }
                    var data = new FormData();
                    data.append('filtered_qb', JSON.stringify({
                        sql: result.sql,
                        params: JSON.stringify(result.params, null, 2),
                    }));
                    admin.request(filterUrl, 'post', function () {
                        admin.hideLoader(loaderId);
                        createDatatable();
                        //-----
                        createLoader = true;
                    }, {
                        data: data,
                    }, true, function () {
                        createLoader = true;
                        admin.hideLoader(loaderId);
                    });
                }
            });

            query_builder_reset_btn.on('click', function () {
                the_query_builder.queryBuilder('reset');

                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(filterClearUrl, 'get', function () {
                    admin.hideLoader(loaderId);
                    createDatatable();
                    //-----
                    createLoader = true;
                }, {}, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            });
        }

        $('#excelExport').on('click', function () {
            window.location = excelExportUrl;
        });

        $('#excelPdfOrder').on('click', function () {
            var id = $(this).attr('data-export-id');
            if (id) {
                window.location = variables.url.report.order.pdfExport + '/' + id;
            }
        });

        //---------------------------------------------------------------
        // When Batch Edit of Product Clicked
        //---------------------------------------------------------------
        var pSubmitUrl, pSubmitUrlPrice;
        var pBatchEditBtn = $('#productBatchEdit');

        pSubmitUrl = pBatchEditBtn.attr('data-submit-url');
        pBatchEditBtn.on('click' + variables.namespace, function (e) {
            e.preventDefault();
            //
            if (checkboxes.length) {
                var frm = $('<form class="tempProductFrm" action="' + pSubmitUrl + '/' + checkboxes.join('/') + '" method="get" />');
                $(this).find('.tempProductFrm').remove();
                $(this).append(frm);
                frm.submit();
            }
        });

        var pBatchEditPriceBtn = $('#productBatchEditPrice');
        pSubmitUrlPrice = pBatchEditPriceBtn.attr('data-submit-url');
        pBatchEditPriceBtn.on('click' + variables.namespace, function (e) {
            e.preventDefault();
            //
            if (checkboxes.length) {
                var frm = $('<form class="tempProductPriceFrm" action="' + pSubmitUrlPrice + '/' + checkboxes.join('/') + '" method="get" />');
                $(this).find('.tempProductPriceFrm').remove();
                $(this).append(frm);
                frm.submit();
            }
        });

        //---------------------------------------------------------------
        // ADD ADDRESS FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addAddress', constraints.addAddress, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.address.add + '/' + userId, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addAddress.form).get(0).reset();
                $(variables.elements.addAddress.form).find('input[type="hidden"]').val('');
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'نام',
        });

        //---------------------------------------------------------------
        // Edit ADDRESS FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editAddress', constraints.editAddress, function (values) {
            if (editAddrId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.address.edit + '/' + userId + '/' + editAddrId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editAddress.form).get(0).reset();
                    $(variables.elements.editAddress.form).find('input[type="hidden"]').val('');
                    // remove current id for province and city and reset current address id
                    $('select[name="' + variables.elements.editAddress.inputs.province + '"]').removeAttr('data-current-province');
                    $('select[name="' + variables.elements.editAddress.inputs.city + '"]').removeAttr('data-current-city');
                    editAddrId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'نام',
        });

        //---------------------------------------------------------------
        // ADD UNIT FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addUnit', constraints.addUnit, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.unit.add, 'POST', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addUnit.form).get(0).reset();
                $(variables.elements.addUnit.form).find('input[type="hidden"]').val('');
                // currentTable is undefined but is must add data-current-table to working form for refresh data after add
                // ... (postponed)
                createDatatable(currentTable);
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit UNIT FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editUnit', constraints.editUnit, function (values) {
            if (editUnitId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.unit.edit + '/' + editUnitId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editUnit.form).get(0).reset();
                    $(variables.elements.editUnit.form).find('input[type="hidden"]').val('');
                    editUnitId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD BLOG FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addBlog', constraints.addBlog, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit BLOG FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editBlog', constraints.editBlog, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD BLOG CATEGORY FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addBlogCategory', constraints.addBlogCategory, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit BLOG CATEGORY FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editBlogCategory', constraints.editBlogCategory, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD FAQ FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addFaq', constraints.addFaq, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.faq.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addFaq.form).get(0).reset();
                $(variables.elements.addFaq.form).find('input[type="hidden"]').val('');
                initializeAllPlugins();
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit FAQ FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editFaq', constraints.editFaq, function (values) {
            if (editFAQId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.faq.edit + '/' + editFAQId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editFaq.form).get(0).reset();
                    $(variables.elements.editFaq.form).find('input[type="hidden"]').val('');
                    initializeAllPlugins();
                    editFAQId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD COLOR FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addColor', constraints.addColor, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit COLOR FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editColor', constraints.editColor, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD SLIDER FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addSlide', constraints.addSlide, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            // Display the key/value pairs
            admin.request(variables.url.slider.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addSlide.form).get(0).reset();
                $(variables.elements.addSlide.form).find('input[type="hidden"]').val('');
                removeImageFromPlaceholder($(variables.elements.addSlide.form).find('[name="' + variables.elements.addSlide.inputs.image + '"]'));
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit SLIDER FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editSlide', constraints.editSlide, function (values) {
            if (editSlideId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.slider.edit + '/' + editSlideId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editSlide.form).get(0).reset();
                    $(variables.elements.editSlide.form).find('input[type="hidden"]').val('');
                    removeImageFromPlaceholder($(variables.elements.editSlide.form).find('[name="' + variables.elements.editSlide.inputs.image + '"]'));
                    editSlideId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD INSTAGRAM IMAGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addInstagramImage', constraints.addInstagramImage, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.instagram.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addInstagramImage.form).get(0).reset();
                $(variables.elements.addInstagramImage.form).find('input[type="hidden"]').val('');
                removeImageFromPlaceholder($(variables.elements.addInstagramImage.form).find('[name="' + variables.elements.addInstagramImage.inputs.image + '"]'));
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit INSTAGRAM IMAGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editInstagramImage', constraints.editInstagramImage, function (values) {
            if (editInstagramImageId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.instagram.edit + '/' + editInstagramImageId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editInstagramImage.form).get(0).reset();
                    $(variables.elements.editInstagramImage.form).find('input[type="hidden"]').val('');
                    removeImageFromPlaceholder($(variables.elements.editInstagramImage.form).find('[name="' + variables.elements.editInstagramImage.inputs.image + '"]'));
                    editInstagramImageId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD NEWSLETTER FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addNewsletter', constraints.addNewsletter, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }

            admin.request(variables.url.newsletter.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addNewsletter.form).get(0).reset();
                $(variables.elements.addNewsletter.form).find('input[type="hidden"]').val('');
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD BRAND FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addBrand', constraints.addBrand, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'عنوان فارسی',
            '{{en-name}}': 'عنوان انگلیسی',
        });

        //---------------------------------------------------------------
        // EDIT BRAND FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editBrand', constraints.editBrand, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'عنوان فارسی',
            '{{en-name}}': 'عنوان انگلیسی',
        });

        //---------------------------------------------------------------
        // ADD ORDER BADGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addBadge', constraints.addBadge, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.badge.add, 'post', function () {
                admin.hideLoader(loaderId);
                var status, prevStat;
                status = $(variables.elements.addBadge.form)
                    .find('[name="' + variables.elements.addBadge.inputs.canReturnOrder + '"]');
                prevStat = status.is(':checked');
                // clear element after success
                $(variables.elements.addBadge.form).get(0).reset();
                $(variables.elements.addBadge.form).find('input[type="hidden"]').val('');
                if (status.is(':checked') && !prevStat) {
                    status
                        .parent()
                        .find('.switchery')
                        .click()
                        .click();
                }
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit ORDER BADGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editBadge', constraints.editBadge, function (values) {
            if (editBadgeId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.badge.edit + '/' + editBadgeId, 'post', function () {
                    admin.hideLoader(loaderId);
                    var status, prevStat;
                    status = $(variables.elements.editBadge.form)
                        .find('[name="' + variables.elements.editBadge.inputs.canReturnOrder + '"]');
                    prevStat = status.is(':checked');
                    // clear element after success
                    $(variables.elements.editBadge.form).get(0).reset();
                    $(variables.elements.editBadge.form).find('input[type="hidden"]').val('');
                    if (status.is(':checked') && !prevStat) {
                        status
                            .parent()
                            .find('.switchery')
                            .click()
                            .click();
                    }
                    editBadgeId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD CATEGORY FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addCategory', constraints.addCategory, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // EDIT CATEGORY FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editCategory', constraints.editCategory, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        }, {
            '{{name}}': 'عنوان فارسی',
            '{{en-name}}': 'عنوان انگلیسی',
        });

        //---------------------------------------------------------------
        // ADD CATEGORY IMAGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addCategoryImage', constraints.addCategoryImage, function (values) {
            if (addCategoryImageId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.categoryImage.add + '/' + addCategoryImageId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.addCategoryImage.form).get(0).reset();
                    $(variables.elements.addCategoryImage.form).find('input[type="hidden"]').val('');
                    addCategoryImageId = null;
                    createDatatable();
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit CATEGORY IMAGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editCategoryImage', constraints.editCategoryImage, function (values) {
            if (editCategoryImageId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.categoryImage.edit + '/' + editCategoryImageId + '/' + currentCategoryId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editCategoryImage.form).get(0).reset();
                    $(variables.elements.editCategoryImage.form).find('input[type="hidden"]').val('');
                    currentCategoryId = null;
                    editCategoryImageId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD STATIC PAGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addStaticPage', constraints.addStaticPage, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit STATIC PAGE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editStaticPage', constraints.editStaticPage, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD COUPON FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addCoupon', constraints.addCoupon, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit COUPON FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editCoupon', constraints.editCoupon, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD SECURITY QUESTION FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addSecurityQuestion', constraints.addSecurityQuestion, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.securityQuestion.add, 'post', function () {
                admin.hideLoader(loaderId);
                var status, prevStat;
                status = $(variables.elements.addSecurityQuestion.form)
                    .find('[name="' + variables.elements.addSecurityQuestion.inputs.status + '"]');
                prevStat = status.is(':checked');
                // clear element after success
                $(variables.elements.addSecurityQuestion.form).get(0).reset();
                $(variables.elements.addSecurityQuestion.form).find('input[type="hidden"]').val('');
                if (status.is(':checked') && !prevStat) {
                    status
                        .parent()
                        .find('.switchery')
                        .click()
                        .click();
                }
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit SECURITY QUESTION FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editSecurityQuestion', constraints.editSecurityQuestion, function (values) {
            if (editSecurityQuestionId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.securityQuestion.edit + '/' + editSecurityQuestionId, 'post', function () {
                    admin.hideLoader(loaderId);
                    var status, prevStat;
                    status = $(variables.elements.editSecurityQuestion.form)
                        .find('[name="' + variables.elements.editSecurityQuestion.inputs.status + '"]');
                    prevStat = status.is(':checked');
                    // clear element after success
                    $(variables.elements.editSecurityQuestion.form).get(0).reset();
                    $(variables.elements.editSecurityQuestion.form).find('input[type="hidden"]').val('');
                    if (status.is(':checked') && !prevStat) {
                        status
                            .parent()
                            .find('.switchery')
                            .click()
                            .click();
                    }
                    editSecurityQuestionId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD PAYMENT METHOD FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addPaymentMethod', constraints.addPaymentMethod, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit PAYMENT METHOD FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editPaymentMethod', constraints.editPaymentMethod, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD SEND METHOD FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addSendMethod', constraints.addSendMethod, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit SEND METHOD FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editSendMethod', constraints.editSendMethod, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD FESTIVAL FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addFestival', constraints.addFestival, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit FESTIVAL FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editFestival', constraints.editFestival, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD PRODUCT TO FESTIVAL FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addProductFestival', constraints.addProductFestival, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.productFestival.addProduct + '/' + festivalId, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addProductFestival.form).get(0).reset();
                $(variables.elements.addProductFestival.form).find('input[type="hidden"]').val('');
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        }, {
            '{percent}': 'درصد تخفیف',
        });

        //---------------------------------------------------------------
        // ADD CATEGORY TO FESTIVAL FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('modifyProductFestival', constraints.modifyProductFestival, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.productFestival.addCategory + '/' + festivalId, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.modifyProductFestival.form).get(0).reset();
                $(variables.elements.modifyProductFestival.form).find('input[type="hidden"]').val('');
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload();
                    createDatatable(currentTable);
                    currentTable = null;
                }
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        }, {
            '{percent}': 'درصد تخفیف',
        });

        //---------------------------------------------------------------
        // REMOVE CATEGORY FROM FESTIVAL FORM
        //---------------------------------------------------------------
        $('#__btn_remove_product_festival').on('click' + variables.namespace, function () {
            var formValues, formErrors, constraints, aliases;
            aliases = {
                '{percent}': 'درصد تخفیف',
            };
            constraints = {
                category: variables.validation.constraints.modifyProductFestival.category,
            };
            formValues = admin.forms.convertFormObjectNumbersToEnglish(window.validate.collectFormValues(variables.elements.modifyProductFestival.form), 'modifyProductFestival');
            formErrors = window.validate(formValues, constraints, {
                prettify: function prettify(string) {
                    return aliases[string] || validate.prettify(string);
                },
            });
            // check form first
            if (!formErrors) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.productFestival.removeCategory + '/' + festivalId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.modifyProductFestival.form).get(0).reset();
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: formValues,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            } else {
                admin.forms.showFormErrors(formErrors);
            }
        });

        //---------------------------------------------------------------
        // ADD STEPPED PRICE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addSteppedPrice', constraints.addSteppedPrice, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit STEPPED PRICE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editSteppedPrice', constraints.editSteppedPrice, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // ADD DEPOSIT TYPE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('addDepositType', constraints.addDepositType, function (values) {
            // do ajax
            if (createLoader) {
                createLoader = false;
                loaderId = admin.showLoader();
            }
            admin.request(variables.url.depositType.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addDepositType.form).get(0).reset();
                $(variables.elements.addDepositType.form).find('input[type="hidden"]').val('');
                createDatatable();
                //-----
                admin.toasts.toast(this.data, {
                    type: variables.toasts.types.success,
                });
                createLoader = true;
            }, {
                data: values,
            }, true, function () {
                createLoader = true;
                admin.hideLoader(loaderId);
            });
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // Edit DEPOSIT TYPE FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('editDepositType', constraints.editDepositType, function (values) {
            if (editDepositTypeId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.depositType.edit + '/' + editDepositTypeId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editDepositType.form).get(0).reset()
                    $(variables.elements.editDepositType.form).find('input[type="hidden"]').val('');
                    editDepositTypeId = null;
                    if (currentModal) {
                        currentModal.modal('hide');
                        currentModal = null;
                    }
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload();
                        createDatatable(currentTable);
                        currentTable = null;
                    }
                    //-----
                    admin.toasts.toast(this.data, {
                        type: variables.toasts.types.success,
                    });
                    createLoader = true;
                }, {
                    data: values,
                }, true, function () {
                    createLoader = true;
                    admin.hideLoader(loaderId);
                });
            }
            return false;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // SETTING MAIN FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('settingMain', constraints.settingMain, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // SETTING BUY FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('settingBuy', constraints.settingBuy, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // SETTING SMS FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('settingSMS', constraints.settingSMS, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });

        //---------------------------------------------------------------
        // SETTING OTHER FORM
        //---------------------------------------------------------------
        admin.forms.submitForm('settingOther', constraints.settingOther, function () {
            return true;
        }, function (errors) {
            admin.forms.showFormErrors(errors);
            return false;
        });
    });
})(jQuery);
