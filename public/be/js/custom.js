/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

(function ($) {
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
            form: '#__form_add_color',
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
            },
        },
        editBadge: {
            form: '#__form_edit_badge',
            inputs: {
                title: 'inp-edit-badge-title',
                color: 'inp-edit-badge-color',
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
    });
    window.MyGlobalVariables.validation = $.extend({}, window.MyGlobalVariables.validation, {
        constraints: {
            addAddress: {
                province: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کد پستی را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^\d{1,10}$/,
                        message: '^' + 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            editAddress: {
                province: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد استان را خالی نگذارید.',
                    },
                },
                city: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد شهر را خالی نگذارید.',
                    },
                },
                postalCode: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد کد پستی را خالی نگذارید.',
                    },
                    format: {
                        pattern: /^\d{1,10}$/,
                        message: '^' + 'کد پستی باید از نوع عددی و دارای حداکثر ۱۰ رقم باشد.',
                    },
                },
                address: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد آدرس را خالی نگذارید.',
                    },
                },
            },
            addUnit: {
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد عنوان واحد را خالی نگذارید.',
                    },
                    length: {
                        maximum: 250,
                        message: '^' + 'عنوان حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                sign: {
                    length: {
                        maximum: 250,
                        message: '^' + 'علامت حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
            },
            addBlog: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد تصویر را خالی نگذارید.',
                    },
                },
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد عنوان را خالی نگذارید.',
                    },
                },
                abstract: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد توضیح مختصر را خالی نگذارید.',
                    },
                },
                desc: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد توضیحات را خالی نگذارید.',
                    },
                },
            },
            editBlog: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد تصویر را خالی نگذارید.',
                    },
                },
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد عنوان را خالی نگذارید.',
                    },
                },
                abstract: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد توضیح مختصر را خالی نگذارید.',
                    },
                },
                desc: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد توضیحات را خالی نگذارید.',
                    },
                },
            },
            addFaq: {
                question: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد سؤال را خالی نگذارید.',
                    },
                },
                answer: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد پاسخ را خالی نگذارید.',
                    },
                },
                tags: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد برچسب را خالی نگذارید.',
                    },
                },
            },
            editFaq: {
                question: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد سؤال را خالی نگذارید.',
                    },
                },
                answer: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد پاسخ را خالی نگذارید.',
                    },
                },
                tags: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد برچسب را خالی نگذارید.',
                    },
                },
            },
            addColor: {
                name: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد نام را خالی نگذارید.',
                    },
                    length: {
                        maximum: 250,
                        message: '^' + 'نام رنگ حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            editColor: {
                name: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد نام را خالی نگذارید.',
                    },
                    length: {
                        maximum: 250,
                        message: '^' + 'نام رنگ حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            addSlide: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر اسلاید را انتخاب کنید.',
                    },
                },
                title: {
                    length: {
                        maximum: 250,
                        message: '^' + 'عنوان حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                subTitle: {
                    length: {
                        maximum: 250,
                        message: '^' + 'زیر عنوان حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                priority: {
                    format: {
                        pattern: /[0-9]+/,
                        message: '^' + 'اولویت باید از نوع عددی باشد.',
                    },
                },
            },
            editSlide: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر اسلاید را انتخاب کنید.',
                    },
                },
                title: {
                    length: {
                        maximum: 250,
                        message: '^' + 'عنوان حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                subTitle: {
                    length: {
                        maximum: 250,
                        message: '^' + 'زیر عنوان حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                priority: {
                    format: {
                        pattern: /[0-9]+/,
                        message: '^' + 'اولویت باید از نوع عددی باشد.',
                    },
                },
            },
            addBlogCategory: {
                name: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'نام دسته‌بندی را وارد کنید.',
                    },
                },
            },
            editBlogCategory: {
                name: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'نام دسته‌بندی را وارد کنید.',
                    },
                },
            },
            addBrand: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر برند را انتخاب کنید.',
                    },
                },
            },
            editBrand: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر برند را انتخاب کنید.',
                    },
                },
            },
            addInstagramImage: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر را انتخاب کنید.',
                    },
                },
            },
            editInstagramImage: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر را انتخاب کنید.',
                    },
                },
            },
            addBadge: {
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد عنوان را خالی نگذارید.',
                    },
                    length: {
                        maximum: 250,
                        message: '^' + 'وضعیت حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            editBadge: {
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد عنوان را خالی نگذارید.',
                    },
                    length: {
                        maximum: 250,
                        message: '^' + 'وضعیت حداکثر باید ۲۵۰ کاراکتر باشد.',
                    },
                },
                color: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'یک رنگ انتخاب کنید.',
                    },
                },
            },
            addCategory: {
                name: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'نام دسته‌بندی را خالی نگذارید.',
                    },
                    length: {
                        maximum: 100,
                        message: '^' + 'نام دسته‌بندی حداکثر باید ۱۰۰ کاراکتر باشد.',
                    },
                },
                parent: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'والد دسته‌بندی را خالی نگذارید.',
                    },
                },
                priority: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد اولویت را خالی نگذارید.',
                    },
                    format: {
                        pattern: /[0-9]+/,
                        message: '^' + 'اولویت باید از نوع عددی باشد.',
                    },
                },
            },
            editCategory: {
                name: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'نام دسته‌بندی را خالی نگذارید.',
                    },
                    length: {
                        maximum: 100,
                        message: '^' + 'نام دسته‌بندی حداکثر باید ۱۰۰ کاراکتر باشد.',
                    },
                },
                parent: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'والد دسته‌بندی را خالی نگذارید.',
                    },
                },
                priority: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'فیلد اولویت را خالی نگذارید.',
                    },
                    format: {
                        pattern: /[0-9]+/,
                        message: '^' + 'اولویت باید از نوع عددی باشد.',
                    },
                },
            },
            addCategoryImage: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر را انتخاب کنید.',
                    },
                },
                category: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'دسته‌بندی را انتخاب کنید.',
                    },
                },
            },
            editCategoryImage: {
                image: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'تصویر را انتخاب کنید.',
                    },
                },
                category: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'دسته‌بندی را انتخاب کنید.',
                    },
                },
            },
            addStaticPage: {
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'دسته‌بندی را انتخاب کنید.',
                    },
                    length: {
                        maximum: 300,
                        message: '^' + 'عنوان ضفحه حداکثر باید ۳۰۰ کاراکتر باشد.',
                    },
                },
                url: {
                    format: {
                        pattern: /[a-zA-Z-_\/]+/,
                        message: '^' + 'آدرس صفحه باید از حروف انگلیسی، خط تیره، آندرلاین و اسلش تشکیل شده باشد.',
                    },
                },
                desc: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'دسته‌بندی را انتخاب کنید.',
                    },
                },
            },
            editStaticPage: {
                title: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'دسته‌بندی را انتخاب کنید.',
                    },
                    length: {
                        maximum: 300,
                        message: '^' + 'عنوان ضفحه حداکثر باید ۳۰۰ کاراکتر باشد.',
                    },
                },
                url: {
                    format: {
                        pattern: /[a-zA-Z-_\/]+/,
                        message: '^' + 'آدرس صفحه باید از حروف انگلیسی، خط تیره، آندرلاین و اسلش تشکیل شده باشد.',
                    },
                },
                desc: {
                    presence: {
                        allowEmpty: false,
                        message: '^' + 'دسته‌بندی را انتخاب کنید.',
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
                    $('<div class="position-fixed w-100 h-100" style="z-index: 1001; left: 0; top: 0; right: 0; bottom: 0;" id="' + id + '" />')
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
            currentTable = null,
            currentCategoryId = null,
            userIdInp,
            userId = null,
            editAddrId = null,
            editUnitId = null,
            editFAQId = null,
            editSlideId = null,
            editInstagramImageId = null,
            editBadgeId = null,
            addCategoryImageId = null,
            editCategoryImageId = null;

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
                priority: variables.validation.constraints.addSlide.priority,
            },
            editSlide: {
                image: variables.validation.constraints.addSlide.image,
                title: variables.validation.constraints.addSlide.title,
                subTitle: variables.validation.constraints.addSlide.subTitle,
                link: variables.validation.common.link,
                priority: variables.validation.constraints.addSlide.priority,
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
        };

        userIdInp = $('input[type="hidden"][data-user-id]');
        if (userIdInp.length) {
            userId = userIdInp.val();
        }

        /**
         * Instantiate switchery plugin with prevent multiple instantiation
         */
        function instantiateSwitchery() {
            if (typeof Switchery !== 'undefined') {
                // Initialize multiple switches
                var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
                elems.forEach(function (html) {
                    if (!$(html).attr('data-switchery')) {
                        var switchery = new Switchery(html);
                    }
                });
            }
        }

        /**
         * @param el
         * @param image
         */
        function addImageToPlaceholder(el, image) {
            $(el)
                .closest('.__file_picker_handler')
                .addClass('has-image')
                .append($('<img class="img-placeholder-image" src="' + variables.url.image + image + '" alt="the image">'))
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
                        $(table).DataTable().ajax.reload(null, true);
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
            url = $(btn).attr('data-change-status-url');
            id = $(btn).attr('data-change-status-id');

            if (url && id) {
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
                    data: {
                        'status': $(btn).is(':checked') ? 1 : 0,
                    },
                }, true);
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
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_address_edit');
            // clear element after each call
            $(variables.elements.editAddress.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.address.get + '/' + userId + '/' + id, 'get', function () {
                    var _ = this;
                    var provincesSelect = $('select[name="' + variables.elements.editAddress.inputs.province + '"]'),
                        citiesSelect = $(provincesSelect.attr('data-city-select-target'));
                    if (_.data.length && provincesSelect.length && citiesSelect.length) {
                        currentTable = table;
                        editAddrId = id;
                        //-----
                        admin.loadProvinces(provincesSelect.attr('data-current-province', _.data['province_id']));
                        admin.loadCities(citiesSelect.attr('data-current-city', _.data['city_id']));
                        editModal.find('[name="' + variables.elements.editAddress.inputs.province + '"]').val(_.data['full_name']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.mobile + '"]').val(_.data['mobile']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.postalCode + '"]').val(_.data['postal_code']);
                        editModal.find('[name="' + variables.elements.editAddress.inputs.address + '"]').val(_.data['address']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editUnitBtnClick(btn, table) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_edit_unit');
            // clear element after each call
            $(variables.elements.editUnit.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.unit.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (_.data.length) {
                        currentTable = table;
                        editUnitId = id;
                        //-----
                        editModal.find('[name="' + variables.elements.editUnit.inputs.title + '"]').val(_.data['title']);
                        editModal.find('[name="' + variables.elements.editUnit.inputs.sign + '"]').val(_.data['sign']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editFAQBtnClick(btn, table) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_edit_faq');
            // clear element after each call
            $(variables.elements.editFaq.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.faq.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (_.data.length) {
                        currentTable = table;
                        editFAQId = id;
                        //-----
                        editModal.find('[name="' + variables.elements.editFaq.inputs.question + '"]').val(_.data['question']);
                        editModal.find('[name="' + variables.elements.editFaq.inputs.answer + '"]').val(_.data['answer']);
                        editModal.find('[name="' + variables.elements.editFaq.inputs.tags + '"]').val(_.data['tags']);
                        if (core.isChecked(_.data['publish'])) {
                            editModal.find('[name="' + variables.elements.editFaq.inputs.status + '"]')
                                .attr('checked', 'checked')
                                .prop('checked', 'checked');
                        }
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editSlideBtnClick(btn, table) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_edit_slide');
            // clear element after each call
            $(variables.elements.editSlide.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.slider.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (_.data.length) {
                        currentTable = table;
                        editSlideId = id;
                        //-----
                        var imgEl = editModal.find('[name="' + variables.elements.editSlide.inputs.image + '"]');
                        imgEl.val(_.data['image']);
                        addImageToPlaceholder(imgEl, _.data['image']);
                        editModal.find('[name="' + variables.elements.editSlide.inputs.title + '"]').val(_.data['title']);
                        editModal.find('[name="' + variables.elements.editSlide.inputs.subTitle + '"]').val(_.data['note']);
                        editModal.find('[name="' + variables.elements.editSlide.inputs.link + '"]').val(_.data['link']);
                        editModal.find('[name="' + variables.elements.editSlide.inputs.priority + '"]').val(_.data['priority']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editInsagramImageBtnClick(btn, table) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_edit_ins_image');
            // clear element after each call
            $(variables.elements.editInstagramImage.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.instagram.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (_.data.length) {
                        currentTable = table;
                        editInstagramImageId = id;
                        //-----
                        var imgEl = editModal.find('[name="' + variables.elements.editInstagramImage.inputs.image + '"]');
                        imgEl.val(_.data['image']);
                        addImageToPlaceholder(imgEl, _.data['image']);
                        editModal.find('[name="' + variables.elements.editInstagramImage.inputs.link + '"]').val(_.data['link']);
                    }
                });
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editBadgeBtnClick(btn, table) {
            var id, editModal;
            id = $(btn).attr('data-edit-id');
            editModal = $('#modal_form_edit_badges');
            // clear element after each call
            $(variables.elements.editBadge.form).reset();
            if (id && editModal.length) {
                admin.request(variables.url.badge.get + '/' + id, 'get', function () {
                    var _ = this;
                    if (_.data.length) {
                        currentTable = table;
                        editBadgeId = id;
                        //-----
                        editModal.find('[name="' + variables.elements.editBadge.inputs.title + '"]').val(_.data['title']);
                        editModal
                            .find('[name="' + variables.elements.editBadge.inputs.color + '"]')
                            .val(_.data['color'])
                            .spectrum("set", _.data['color']);
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
            $(variables.elements.addCategoryImage.form).reset();
            if (cId && addModal.length) {
                addCategoryImageId = cId;
            }
        }

        /**
         * @param btn
         * @param [table]
         */
        function editCategoryImageBtnClick(btn, table) {
            var id, cId, editModal;
            id = $(btn).attr('data-edit-id');
            cId = $(btn).attr('data-edit-category-id');
            editModal = $('#modal_form_edit_cat_img');
            // clear element after each call
            $(variables.elements.editCategoryImage.form).reset();
            if (id && cId && editModal.length) {
                admin.request(variables.url.categoryImage.get + '/' + cId + '/' + id, 'get', function () {
                    var _ = this;
                    if (_.data.length) {
                        currentTable = table;
                        currentCategoryId = id;
                        editCategoryImageId = cId;
                        //-----
                        var imgEl = editModal.find('[name="' + variables.elements.editCategoryImage.inputs.image + '"]');
                        imgEl.val(_.data['image']);
                        addImageToPlaceholder(imgEl, _.data['image']);
                        editModal.find('[name="' + variables.elements.editCategoryImage.inputs.category + '"]').val(_.data['link']);
                    }
                });
            }
        }

        /**
         * Actions to take after a datatable initialize(reinitialize)
         */
        function datatableInitCompleteActions(table) {
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
                .off('change')
                .on('change', function () {
                    changeOperation($(this), table);
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
                    editInsagramImageBtnClick($(this), table);
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

        if ($().spectrum) {
            var cps = $(".colorpicker-show-input");
            cps.each(function () {
                var $this = $(this);

                $this.spectrum({
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
                })
            });
        }

        // Image lightbox
        if ($().fancybox) {
            $('[data-popup="lightbox"]').fancybox({
                padding: 3
            });
        }

        // uniform initialize
        if ($().uniform) {
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

        if ($().DataTable) {
            // Setting datatable defaults
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
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
                            // "error": function (err) {
                            //     console.log(err);
                            // },
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

            $.each($('.datatable-highlight'), function () {
                var $this, table, url;
                $this = $(this);
                url = $this.attr('data-ajax-url');
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
                            datatableInitCompleteActions($this);
                        },
                    });
                } else {
                    table = $this.DataTable({
                        stateSave: true,
                        initComplete: function () {
                            datatableInitCompleteActions($this);
                        },
                    });
                }

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

        // this must be after datatable
        if ($().select2) {
            // Basic example
            $('.form-control-select2').each(function () {
                var obj, parent;
                obj = {
                    minimumResultsForSearch: Infinity,
                };
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });

            // With search
            $('.form-control-select2-searchable').each(function () {
                var obj, parent;
                obj = {};
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });

            //
            // Select with icons
            //

            // Format icon
            function iconFormat(icon) {
                var originalOption = icon.element;
                if (!icon.id) {
                    return icon.text;
                }
                var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

                return $icon;
            }

            // Initialize with options
            $('.form-control-select2-icons').each(function () {
                var obj, parent;
                obj = {
                    templateResult: iconFormat,
                    dropdownParent: $(this).closest('.modal'),
                    minimumResultsForSearch: Infinity,
                    templateSelection: iconFormat,
                    escapeMarkup: function (m) {
                        return m;
                    }
                };
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });

            // Initialize
            $('.dataTables_length select').each(function () {
                var obj, parent;
                obj = {
                    minimumResultsForSearch: Infinity,
                    dropdownAutoWidth: true,
                    width: 'auto'
                };
                parent = $(this).closest('.modal');
                if (parent.length) {
                    obj['dropdownParent'] = parent;
                }
                $(this).select2(obj);
            });
        }

        if ($().tagsinput) {
            $('.tags-input').each(function () {
                var obj, maxTags;
                obj = {};

                // check for max tags
                maxTags = $(this).attr('data-max-tags');
                maxTags = maxTags && !isNaN(parseInt(maxTags, 10)) ? parseInt(maxTags, 10) : null;
                if (maxTags) {
                    obj['maxTags'] = maxTags;
                }

                $(this).tagsinput(obj);
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

            $('.maxlength-textarea').maxlength({
                alwaysShow: true,
                warningClass: 'text-success form-text',
                limitReachedClass: 'text-danger form-text',
                separator: ' از ',
                preText: 'شما دارای ',
                postText: ' کاراکتر هستید',
                validate: true
            });
        }

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
                $(variables.elements.addAddress.form).reset();
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload(null, true);
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
                    $(variables.elements.editAddress.form).reset();
                    // remove current id for province and city and reset current address id
                    $('select[name="' + variables.elements.editAddress.inputs.province + '"]').removeAttr('data-current-province');
                    $('select[name="' + variables.elements.editAddress.inputs.city + '"]').removeAttr('data-current-city');
                    editAddrId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
            admin.request(variables.url.unit.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addUnit.form).reset();
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload(null, true);
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
                    $(variables.elements.editUnit.form).reset();
                    editUnitId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
                $(variables.elements.addFaq.form).reset();
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload(null, true);
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
                    $(variables.elements.editFaq.form).reset();
                    editFAQId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
            admin.request(variables.url.slider.add, 'post', function () {
                admin.hideLoader(loaderId);
                // clear element after success
                $(variables.elements.addSlide.form).reset();
                removeImageFromPlaceholder($(variables.elements.addSlide.form).find('[name="' + variables.elements.addSlide.inputs.image + '"]'));
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload(null, true);
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
                    $(variables.elements.editSlide.form).reset();
                    removeImageFromPlaceholder($(variables.elements.editSlide.form).find('[name="' + variables.elements.editSlide.inputs.image + '"]'));
                    editSlideId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
                $(variables.elements.addInstagramImage.form).reset();
                removeImageFromPlaceholder($(variables.elements.addInstagramImage.form).find('[name="' + variables.elements.addInstagramImage.inputs.image + '"]'));
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload(null, true);
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
            if (editSlideId) {
                // do ajax
                if (createLoader) {
                    createLoader = false;
                    loaderId = admin.showLoader();
                }
                admin.request(variables.url.instagram.edit + '/' + editInstagramImageId, 'post', function () {
                    admin.hideLoader(loaderId);
                    // clear element after success
                    $(variables.elements.editInstagramImage.form).reset();
                    removeImageFromPlaceholder($(variables.elements.editInstagramImage.form).find('[name="' + variables.elements.editInstagramImage.inputs.image + '"]'));
                    editInstagramImageId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
                $(variables.elements.addNewsletter.form).reset();
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload(null, true);
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
                // clear element after success
                $(variables.elements.addBadge.form).reset();
                if (currentTable) {
                    $(currentTable).DataTable().ajax.reload(null, true);
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
                    // clear element after success
                    $(variables.elements.editBadge.form).reset();
                    editBadgeId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
                    $(variables.elements.addCategoryImage.form).reset();
                    addCategoryImageId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
                    $(variables.elements.editCategoryImage.form).reset();
                    currentCategoryId = null;
                    editCategoryImageId = null;
                    if (currentTable) {
                        $(currentTable).DataTable().ajax.reload(null, true);
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
    });
})(jQuery);
