(function () {
    'use strict';

    // add/change global variable
    window.MyGlobalVariables.elements = $.extend({}, window.MyGlobalVariables.elements, {
        blog: {
            form: '#__form_blog_search',
            inputs: {
                search: 'inp-blog-search-side',
            },
        },
    });
    window.MyGlobalVariables.validation = $.extend(true, window.MyGlobalVariables.validation, {
        constraints: {
            blog: {
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
            mainBlogContainer,
            isInProgress = false;

        variables = window.MyGlobalVariables;
        core = window.TheCore;
        shop = new window.TheShop();
        /**
         * @var UriParser uriParser
         */
        uriParser = new UriParser();

        //-----
        constraints = {
            blog: {
                search: variables.validation.constraints.blog.search,
            },
        };
        //-----

        mainBlogContainer = $('#__main_blog_container');

        function loadBlog() {
            if (!isInProgress) {
                isInProgress = true;
                var loaderId = shop.showLoader();
                shop.request(variables.url.blog.search, 'get', function () {
                    // place received items in their place
                    mainBlogContainer.html(this.data);
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

        loadBlog();
        uriParser.onStateChange(function () {
            this.pushState(this.uniqueObj());
            loadBlog();
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

        // search string changed
        shop.forms.submitForm('blog', constraints.blog, function (values) {
            uriParser.push('q', values[variables.elements.blog.inputs.search], true);
            return false;
        }, function (errors) {
            shop.forms.showFormErrors(errors);
            return false;
        });
    });
})();