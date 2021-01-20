<?php

namespace App\Logic;

use App\Logic\Handlers\CustomExceptionHandler;
use App\Logic\Middlewares\AdminAuthMiddleware;
use App\Logic\Middlewares\ApiVerifierMiddleware;
use App\Logic\Middlewares\AuthMiddleware;
use App\Logic\Utils\ConfigUtil;
use Pecee\SimpleRouter\Event\EventArgument;
use Pecee\SimpleRouter\Handlers\EventHandler;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInitialize;
use Sim\Interfaces\IInvalidVariableNameException;

class Route implements IInitialize
{
    /**
     * Route definitions
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function init()
    {
        $this->setDependencyInjection();
        $this->setEventHandlers();
        $this->setDefaultNamespace();
        //-----
        $this->webRoutes();
        $this->ajaxRoutes();
        $this->apiRoutes();
    }

    /**
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \Exception
     */
    protected function setDependencyInjection()
    {
        $mode = config()->get('main.mode') ?? MODE_PRODUCTION;
        if (MODE_PRODUCTION == $mode) {
            // Production code for container
            // Cache directory
            $cacheDir = cache_path('simple-router');

            // Create our new php-di container
            $container = (new \DI\ContainerBuilder())
                ->enableCompilation($cacheDir)
                ->writeProxiesToFile(true, $cacheDir . '/proxies')
                ->useAutowiring(true)
                ->build();
        } else {
            // Development code for container
            // Create our new php-di container
            $container = (new \DI\ContainerBuilder())
                ->useAutowiring(true)
                ->build();
        }

        // Add our container to simple-router and enable dependency injection
        Router::enableDependencyInjection($container);
    }

    protected function setEventHandlers()
    {
        $eventHandler = new EventHandler();

        // Add event that fires when a route is rendered
        $eventHandler->register(EventHandler::EVENT_RENDER_ROUTE, function (EventArgument $argument) {
            // read config from database and store in config manager
            /**
             * @var ConfigUtil $configUtil
             */
            $configUtil = \container()->get(ConfigUtil::class);
            $configUtil->pullConfig();
        });

        Router::addEventHandler($eventHandler);
    }

    /**
     * Default namespace for route
     */
    protected function setDefaultNamespace()
    {
        /**
         * The default namespace for route-callbacks, so we don't have to specify it each time.
         * Can be overwritten by using the namespace config option on your routes.
         */
        Router::setDefaultNamespace('\App\Logic\Controllers');
    }

    /**
     * Routes of web part
     */
    protected function webRoutes()
    {
        //==========================
        // not found route in admin
        //==========================
        Router::get('/admin/page/404', 'PageController@adminNotFound')->name(NOT_FOUND_ADMIN);

        //==========================
        // not found route
        //==========================
        Router::get('/page/404', 'PageController@notFound')->name(NOT_FOUND);

        //==========================
        // server error route
        //==========================
        Router::get('/page/500', 'PageController@serverError')->name(SERVER_ERROR);

        Router::group(['exceptionHandler' => CustomExceptionHandler::class], function () {
            //==========================
            // admin routes
            //==========================
            // admin login page
            Router::form('/admin/login', 'Admin\HomeController@login')->name('admin.login');

            // other pages that need authentication
            Router::group(['prefix' => '/admin/', 'middleware' => AdminAuthMiddleware::class], function () {
                Router::get('/', 'Admin\HomeController@index')->name('admin.index');

                /**
                 * User Route
                 */
                Router::get('/user/view/{id?}', 'Admin\UserController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.user.view');
                Router::form('/user/add', 'Admin\UserController@add')->name('admin.user.add');
                Router::form('/user/edit/{id}', 'Admin\UserController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.user.edit');
                Router::post('/user/view/dt', 'Admin\UserController@getPaginatedDatatable')->name('admin.user.dt.view');

                /**
                 * Address Route
                 */
                Router::post('/address/view/dt/{user_id}', 'Admin\AddressController@getPaginatedDatatable')->where([
                    'user_id' => '[0-9]+',
                ])->name('admin.addr.dt.view');

                /**
                 * User Order Route
                 */
                Router::post('/address/view/dt/{user_id}', 'Admin\UserController@getOrderPaginatedDatatable')->where([
                    'user_id' => '[0-9]+',
                ])->name('admin.user.order.dt.view');

                /**
                 * Category Route
                 */
                Router::form('/category/add', 'Admin\CategoryController@add')->name('admin.category.add');
                Router::form('/category/edit/{id}', 'Admin\CategoryController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.category.edit');
                Router::get('/category/view', 'Admin\CategoryController@view')->name('admin.category.view');
                Router::post('/category/view/dt', 'Admin\CategoryController@getPaginatedDatatable')->name('admin.category.dt.view');

                /**
                 * Category Image Route
                 */
                Router::get('/category/image/view', 'Admin\CategoryImageController@view')->name('admin.category.image.view');
                Router::post('/category/image/view/dt', 'Admin\CategoryImageController@getPaginatedDatatable')
                    ->name('admin.category.image.dt.view');

                /**
                 * Coupon Route
                 */
                Router::form('/coupon/add', 'Admin\CouponController@add')->name('admin.coupon.add');
                Router::form('/coupon/edit/{id}', 'Admin\CouponController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.coupon.edit');
                Router::get('/coupon/view', 'Admin\CouponController@view')->name('admin.coupon.view');
                Router::post('/coupon/view/dt', 'Admin\CouponController@getPaginatedDatatable')->name('admin.coupon.dt.view');

                /**
                 * Payment Methods Route
                 */
                Router::form('/pay-method/add', 'Admin\PaymentMethodController@add')->name('admin.pay_method.add');
                Router::form('/pay-method/edit/{id}', 'Admin\PaymentMethodController@edit')->where([
                    'id' => '[0-9]',
                ])->name('admin.pay_method.edit');
                Router::get('/pay-method/view', 'Admin\PaymentMethodController@view')->name('admin.pay_method.view');
                Router::post('/pay-method/view/dt', 'Admin\PaymentMethodController@getPaginatedDatatable')->name('admin.pay_method.dt.view');

                /**
                 * Color Route
                 */
                Router::form('/color/add', 'Admin\ColorController@add')->name('admin.color.add');
                Router::form('/color/edit/{id}', 'Admin\ColorController@edit')->where([
                    'id' => '[0-9]',
                ])->name('admin.color.edit');
                Router::get('/color/view', 'Admin\ColorController@view')->name('admin.color.view');
                Router::post('/color/view/dt', 'Admin\ColorController@getPaginatedDatatable')->name('admin.color.dt.view');

                /**
                 * Festival Route
                 */
                Router::form('/festival/add', 'Admin\festivalController@add')->name('admin.festival.add');
                Router::form('/festival/edit/{id}', 'Admin\FestivalController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.festival.edit');
                Router::get('/festival/view', 'Admin\FestivalController@view')->name('admin.festival.view');
                Router::post('/festival/view/dt', 'Admin\FestivalController@getPaginatedDatatable')->name('admin.festival.dt.view');

                /**
                 * Product Festival Route
                 */
                Router::form('/festival/detail/{id}', 'Admin\ProductFestivalController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.festival.detail');
                Router::post('/product/festival/view/dt/{f_id}', 'Admin\ProductFestivalController@getPaginatedDatatable')->where([
                    'f_id' => '[0-9]+',
                ])->name('admin.product.festival.dt.view');

                /**
                 * Brand Route
                 */
                Router::form('/brand/add', 'Admin\BrandController@add')->name('admin.brand.add');
                Router::form('/brand/edit/{id}', 'Admin\BrandController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.brand.edit');
                Router::get('/brand/view', 'Admin\BrandController@view')->name('admin.brand.view');
                Router::post('/brand/view/dt', 'Admin\BrandController@getPaginatedDatatable')
                    ->name('admin.brand.dt.view');

                /**
                 * Product Route
                 */
                Router::form('/product/add', 'Admin\ProductController@add')->name('admin.product.add');
                Router::form('/product/edit/{id}', 'Admin\ProductController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.edit');
                Router::get('/product/view', 'Admin\ProductController@view')->name('admin.product.view');
                Router::post('/product/view/dt', 'Admin\ProductController@getPaginatedDatatable')->name('admin.product.dt.view');

                /**
                 * Comment Route
                 */
                Router::get('/comment/view/{p_id}/{id?}', 'Admin\CommentController@view')->where([
                    'p_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('admin.comment.view');
                Router::post('/comment/view/dt/{p_id}', 'Admin\CommentController@getPaginatedDatatable')->where([
                    'p_id' => '[0-9]+',
                ])->name('admin.comment.dt.view');

                /**
                 * Blog Route
                 */
                Router::form('/blog/add', 'Admin\BlogController@add')->name('admin.blog.add');
                Router::form('/blog/edit/{id}', 'Admin\BlogController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.blog.edit');
                Router::get('/blog/view', 'Admin\BlogController@view')->name('admin.blog.view');
                Router::post('/blog/view/dt', 'Admin\BlogController@getPaginatedDatatable')->name('admin.blog.dt.view');

                /**
                 * Blog Category Route
                 */
                Router::form('/blog/category/add', 'Admin\BlogCategoryController@add')->name('admin.blog.category.add');
                Router::form('/blog/category/edit/{id}', 'Admin\BlogCategoryController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.blog.category.edit');
                Router::get('/blog/category/view', 'Admin\BlogCategoryController@view')->name('admin.blog.category.view');
                Router::post('/blog/category/view/dt', 'Admin\BlogCategoryController@getPaginatedDatatable')
                    ->name('admin.blog.category.dt.view');

                /**
                 * Static Page Route
                 */
                Router::form('/static-page/add', 'Admin\StaticPageController@add')->name('admin.static.page.add');
                Router::form('/static-page/edit/{id}', 'Admin\StaticPageController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.static.page.edit');
                Router::get('/static-page/view', 'Admin\StaticPageController@view')->name('admin.static.page.view');
                Router::post('/static-page/view/dt', 'Admin\StaticPageController@getPaginatedDatatable')->name('admin.static.page.dt.view');

                /**
                 * Unit Route
                 */
                Router::get('/unit/view', 'Admin\UnitController@view')->name('admin.unit.view');
                Router::post('/unit/view/dt', 'Admin\UnitController@getPaginatedDatatable')->name('admin.unit.dt.view');

                /**
                 * FAQ Route
                 */
                Router::get('/faq/view', 'Admin\FaqController@view')->name('admin.faq.view');
                Router::post('/faq/view/dt', 'Admin\FaqController@getPaginatedDatatable')->name('admin.faq.dt.view');

                /**
                 * Contact Us Route
                 */
                Router::get('/contact-us/view/{id?}', 'Admin\ContactUsController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.contact-us.view');
                Router::post('/contact-us/view/dt', 'Admin\ContactUsController@getPaginatedDatatable')
                    ->name('admin.contact-us.dt.view');

                /**
                 * Complaints Route
                 */
                Router::get('/complaints/view/{id?}', 'Admin\ComplaintsController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.complaints.view');
                Router::post('/complaints/view/dt', 'Admin\ComplaintsController@getPaginatedDatatable')
                    ->name('admin.complaints.dt.view');

                /**
                 * Newsletter Route
                 */
                Router::get('/newsletter/view/{id?}', 'Admin\NewsletterController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.newsletter.view');

                /**
                 * Wallet Route
                 */
                Router::get('/wallet/view/{id?}', 'Admin\WalletController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.wallet.view');
                Router::form('/wallet/deposit-type', 'Admin\WalletController@depositType')->name('admin.wallet.deposit-type');

                /**
                 * Order Route
                 */
                Router::get('/order/view/{id?}', 'Admin\OrderController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.order.view');
                Router::get('/order/return-order/{id?}', 'Admin\OrderController@returnOrder')->where([
                    'id' => '[0-9]+',
                ])->name('admin.return.order');

                /**
                 * Order Badges Route
                 */
                Router::get('/order/badges', 'Admin\OrderBadgeController@view')->name('admin.badge.view');
                Router::post('/order/badges/dt', 'Admin\OrderBadgeController@getPaginatedDatatable')->name('admin.badge.dt.view');

                /**
                 * Slider Route
                 */
                Router::get('/slider/view', 'Admin\SliderController@view')->name('admin.slider.view');
                Router::post('/slider/view/dt', 'Admin\SliderController@getPaginatedDatatable')->name('admin.slider.dt.view');

                /**
                 * Instagram Route
                 */
                Router::get('/instagram/view', 'Admin\InstagramController@view')->name('admin.instagram.view');
                Router::post('/instagram/view/dt', 'Admin\InstagramController@getPaginatedDatatable')->name('admin.instagram.dt.view');

                /**
                 * Security Question Route
                 */
                Router::get('/sec-question/view', 'Admin\SecurityQuestionController@view')->name('admin.sec_question.view');
                Router::post('/sec-question/view/dt', 'Admin\SecurityQuestionController@getPaginatedDatatable')->name('admin.sec_question.dt.view');

                /**
                 * Setting Route
                 */
                Router::get('/setting', 'Admin\SettingController@view')->name('admin.setting');
                /**
                 * File Manager Route
                 */
                Router::get('/file-manager', 'Admin\FileController@index')->name('admin.file-manager');
            });

            // show images outside of public folder
            Router::get('/images/{filename}', 'Admin\FileController@showImage')->where([
                'filename' => '.+',
            ])->name('image.show');

            //==========================
            // user routes
            //==========================
            Router::group(['prefix' => '/user/', 'middleware' => AuthMiddleware::class], function () {
                /**
                 * dashboard
                 */
                Router::get('/', 'User\HomeController@index')->name('user.index');
            });

            //==========================
            // colleague routes
            //==========================
            Router::group(['prefix' => '/colleague/', 'middleware' => AuthMiddleware::class], function () {
                /**
                 * dashboard
                 */
                Router::get('/', 'Colleague\HomeController@index')->name('colleague.index');
            });

            //==========================
            // other routes
            //==========================
            /**
             * index routes
             */
            Router::get('/', 'HomeController@index')->name('home.index');

            /**
             * common routes
             */
            Router::get('/about', 'PageController@about')->name('home.about');
            Router::get('/faq', 'PageController@faq')->name('home.faq');
            Router::form('/contact', 'PageController@contact')->name('home.contact');
            Router::form('/complaint', 'PageController@complaint')->name('home.complaint');
            Router::get('/pages/{url}', 'PageController@pages')->where([
                'url' => '.+',
            ])->name('home.pages');

            /**
             * login & signup routes
             */
            Router::form('/login', 'LoginController@index')->name('home.login');
            Router::form('/forget-password/{step?}', 'LoginController@forgetPassword')->where([
                'step' => 'step[1|2|3|4]',
            ])->name('home.forget-password');
            Router::form('/signup', 'RegisterController@index')->name('home.signup');
            Router::form('/signup/code', 'RegisterController@enterCode')->name('home.signup.code');
            Router::form('/signup/password', 'RegisterController@enterPassword')->name('home.signup.password');

            /**
             * cart routes
             */
            Router::get('/cart', 'CartController@index')->name('home.cart');

            /**
             * product routes
             */
            Router::get('/search/{category?}/{category_slug?}', 'ProductController@index')->where([
                'category' => '[0-9]+',
            ])->name('home.search');
            Router::get('/product/{id}/{slug?}', 'ProductController@show')->where([
                'id' => '[0-9]+',
            ])->name('home.product.show');
            Router::get('/compare', 'CompareController@compare')->name('home.compare');

            /**
             * brand routes
             */
            Router::get('/brands/{id}/{slug?}', 'BrandController@show')->where([
                'id' => '[0-9]+'
            ])->name('home.brand.show');

            /**
             * blog routes
             */
            Router::get('/blog/', 'BlogController@index')->name('home.blog');
            Router::get('/blog/{id}/{slug?}', 'BlogController@show')->where([
                'id' => '[0-9]+',
            ])->name('home.blog.show');
        });
    }

    /**
     * Routes of ajax part
     */
    protected function ajaxRoutes()
    {
        Router::group([
            'prefix' => '/ajax/',
            'exceptionHandler' => CustomExceptionHandler::class,
        ], function () {
            /**
             * newsletter route
             */
            Router::post('/newsletter/add', 'PageController@addNewsletter')->name('ajax.newsletter.add');

            /**
             * cart routes
             */
            Router::post('/cart/add/{product_code}', 'CartController@addToCart')->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.add');
            Router::post('/cart/update/{product_code}', 'CartController@updateCart')->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.update');
            Router::post('/cart/remove/{product_code}', 'CartController@removeFromCart')->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.remove');

            /**
             * blog route
             */
            Router::get('/blog/search', 'BlogController@search')->name('ajax.blog.search');

            /**
             * product route
             */
            Router::get('/product/search', 'ProductController@search')->name('ajax.product.search');
            Router::get('/product/price/{product_code}', 'ProductController@getPrice')->where([
                'product_code' => '\w+',
            ])->name('ajax.product.price');
            Router::get('/product/comments/{product_id}', 'CommentController@paginate')->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.comments');
            Router::post('/product/comment/add/{product_id}', 'CommentController@saveComment')->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.comments');
            Router::post('/product/wishlist/toggle/{product_id}', 'ProductController@wishlistToggle')->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.wishlist.toggle');
            Router::post('/product/wishlist/remove/{product_id}', 'ProductController@wishlistRemove')->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.wishlist.remove');

            /**
             * get provinces
             */
            Router::get('/provinces', 'PageController@getProvinces')->name('ajax.province.get');

            /**
             * get cities
             */
            Router::get('/cities/{province_id}', 'PageController@getCities')->where([
                'province_id' => '[0-9]+',
            ])->name('ajax.city.get');

            /**
             * get captcha image
             */
            Router::get('/captcha', 'CaptchaController@generateCaptcha')->name('api.captcha');

            // other pages that need authentication
            Router::group(['middleware' => AdminAuthMiddleware::class], function () {
                /**
                 * editor browser route
                 */
                Router::get('/editor/browser', 'Admin\HomeController@browser')->name('ajax.editor.browser');

                /**
                 * user route
                 */
                Router::delete('/user/remove/{id}', 'Admin\UserController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.remove');

                /**
                 * address route
                 */
                Router::get('/address/get/{user_id}/{id}', 'Admin\AddressController@get')->where([
                    'user_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.get');
                Router::post('/address/add/{user_id}', 'Admin\AddressController@add')->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.admin.addr.add');
                Router::post('/address/edit/{user_id}/{id}', 'Admin\AddressController@edit')->where([
                    'user_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.edit');
                Router::delete('/address/remove/{id}', 'Admin\AddressController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.remove');

                /**
                 * user order route
                 */
                Router::delete('/user/order/remove/{id}', 'Admin\UserController@removeOrder')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.order.remove');

                /**
                 * unit route
                 */
                Router::get('/unit/get/{id}', 'Admin\UnitController@get')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.get');
                Router::post('/unit/add/{user_id}', 'Admin\UnitController@add')->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.unit.add');
                Router::post('/unit/edit/{id}', 'Admin\UnitController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.edit');
                Router::delete('/unit/remove/{id}', 'Admin\UnitController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.remove');

                /**
                 * blog route
                 */
                Router::delete('/blog/remove/{id}', 'Admin\BlogController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.remove');

                /**
                 * blog category route
                 */
                Router::delete('/blog/category/remove/{id}', 'Admin\BlogCategoryController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.category.remove');
                Router::post('/blog/category/side-status/{id}', 'Admin\BlogCategoryController@sideStatusChange')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.category.side.status');

                /**
                 * static page route
                 */
                Router::delete('/static-page/remove/{id}', 'Admin\StaticPageController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.static.page.remove');

                /**
                 * faq route
                 */
                Router::get('/faq/get/{id}', 'Admin\FaqController@get')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.get');
                Router::post('/faq/add/{user_id}', 'Admin\FaqController@add')->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.faq.add');
                Router::post('/faq/edit/{id}', 'Admin\FaqController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.edit');
                Router::delete('/faq/remove/{id}', 'Admin\FaqController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.remove');

                /**
                 * payment method route
                 */
                Router::delete('/pay-method/remove/{id}', 'Admin\PaymentMethodController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.pay_method.remove');

                /**
                 * color route
                 */
                Router::delete('/color/remove/{id}', 'Admin\ColorController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.color.remove');

                /**
                 * brand route
                 */
                Router::post('/brand/slider-status/{id}', 'Admin\BrandController@sliderStatusChange')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.brand.slider.status');

                /**
                 * festival route
                 */
                Router::delete('/festival/remove/{id}', 'Admin\FestivalController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.remove');
                Router::post('/festival/pub-status/{id}', 'Admin\FestivalController@pubStatusChange')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.pub.status');
                Router::post('/festival/main-status/{id}', 'Admin\FestivalController@mainStatusChange')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.main.status');

                /**
                 * product festival route
                 */
                Router::post('/product/festival/add/{f_id}', 'Admin\ProductFestivalController@add')->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.add');
                Router::post('/product/festival/add-category/{f_id}', 'Admin\ProductFestivalController@addCategory')->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.category.add');
                Router::delete('/product/festival/category/remove/{f_id}', 'Admin\ProductFestivalController@removeCategory')->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.category.remove');
                Router::delete('/product/festival/remove/{id}', 'Admin\ProductFestivalController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.product.festival.remove');

                /**
                 * product route
                 */
                Router::delete('/product/remove/{id}', 'Admin\ProductController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.remove');

                /**
                 * slider route
                 */
                Router::get('/slider/get/{id}', 'Admin\SliderController@get')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.get');
                Router::post('/slider/add/{user_id}', 'Admin\SliderController@add')->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.slider.add');
                Router::post('/slider/edit/{id}', 'Admin\SliderController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.edit');
                Router::delete('/slider/remove/{id}', 'Admin\SliderController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.remove');

                /**
                 * newsletter route
                 */
                Router::delete('/admin/newsletter/remove/{id}', 'Admin\NewsletterController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.newsletter.remove');
                Router::post('/admin/newsletter/add/{id}', 'Admin\NewsletterController@add')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.newsletter.add');

                /**
                 * instagram route
                 */
                Router::get('/instagram/get/{id}', 'Admin\InstagramController@get')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.get');
                Router::post('/instagram/add/{user_id}', 'Admin\InstagramController@add')->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.instagram.add');
                Router::post('/instagram/edit/{id}', 'Admin\InstagramController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.edit');
                Router::delete('/instagram/remove/{id}', 'Admin\InstagramController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.remove');

                /**
                 * order badge route
                 */
                Router::get('/badge/get/{id}', 'Admin\OrderBadgeController@get')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.get');
                Router::post('/badge/add/{user_id}', 'Admin\OrderBadgeController@add')->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.badge.add');
                Router::post('/badge/edit/{id}', 'Admin\OrderBadgeController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.edit');
                Router::delete('/badge/remove/{id}', 'Admin\OrderBadgeController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.remove');

                /**
                 * category route
                 */
                Router::post('/category/menu-status/{id}', 'Admin\CategoryController@menuStatusChange')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.category.menu.status');

                /**
                 * category images route
                 */
                Router::post('/category/image/add/{c_id}', 'Admin\CategoryImageController@add')->where([
                    'c_id' => '[0-9]+',
                ])->name('ajax.category.image.add');
                Router::post('/category/image/edit/{c_id}/{id}', 'Admin\CategoryImageController@edit')->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.edit');
                Router::get('/category/image/get/{c_id}/{id}', 'Admin\CategoryImageController@get')->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.get');
                Router::delete('/category/image/remove/{c_id}/{id}', 'Admin\CategoryImageController@remove')->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.remove');

                /**
                 * coupon route
                 */
                Router::delete('/coupon/remove/{id}', 'Admin\CouponController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.coupon.remove');

                /**
                 * security question route
                 */
                Router::post('/sec-question/add', 'Admin\SecurityQuestionController@add')->where([
                    'c_id' => '[0-9]+',
                ])->name('ajax.sec_question.add');
                Router::post('/sec-question/edit/{id}', 'Admin\SecurityQuestionController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.edit');
                Router::get('/sec-question/get/{id}', 'Admin\SecurityQuestionController@get')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.get');
                Router::delete('/sec-question/remove/{id}', 'Admin\SecurityQuestionController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.remove');

                /**
                 * contact us route
                 */
                Router::delete('/contact-us/remove/{id}', 'Admin\ContactUsController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.contact-us.remove');

                /**
                 * complaint route
                 */
                Router::delete('/complaints/remove/{id}', 'Admin\ComplaintsController@remove')->where([
                    'id' => '[0-9]+',
                ])->name('ajax.complaints.remove');

                /**
                 * File Manager Route
                 */
                Router::get('/file-manager/list', 'Admin\FileController@list')->name('api.file-manager.list');
                Router::post('/file-manager/rename', 'Admin\FileController@rename')->name('api.file-manager.rename');
                Router::post('/file-manager/delete', 'Admin\FileController@delete')->name('api.file-manager.delete');
                Router::post('/file-manager/mkdir', 'Admin\FileController@makeDir')->name('api.file-manager.mkdir');
                Router::post('/file-manager/mvdir', 'Admin\FileController@moveDir')->name('api.file-manager.mvdir');
                Router::post('/file-manager/upload', 'Admin\FileController@upload')->name('api.file-manager.upload');
                Router::get('/file-manager/download/{filename}', 'Admin\FileController@download')->where([
                    'filename' => '.+',
                ])->name('api.file-manager.download');
                Router::get('/file-manager/dir-tree', 'Admin\FileController@foldersTree')->name('api.file-manager.tree');
            });
        });
    }

    /**
     * Routes of api part
     */
    protected function apiRoutes()
    {
        Router::group([
            'prefix' => '/api/',
            'exceptionHandler' => CustomExceptionHandler::class,
            'middleware' => ApiVerifierMiddleware::class
        ], function () {

        });
    }
}
