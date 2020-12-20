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
                Router::get('/user/add', 'Admin\UserController@add')->name('admin.user.add');
                Router::get('/user/edit/{id}', 'Admin\UserController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.user.edit');

                /**
                 * Category Route
                 */
                Router::get('/category/add', 'Admin\CategoryController@add')->name('admin.category.add');
                Router::get('/category/edit/{id}', 'Admin\CategoryController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.category.edit');
                Router::get('/category/view', 'Admin\CategoryController@view')->name('admin.category.view');

                /**
                 * Coupon Route
                 */
                Router::get('/coupon/add', 'Admin\CouponController@add')->name('admin.coupon.add');
                Router::get('/coupon/edit/{id}', 'Admin\CouponController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.coupon.edit');
                Router::get('/coupon/view', 'Admin\CouponController@view')->name('admin.coupon.view');

                /**
                 * Color Route
                 */
                Router::get('/color/add', 'Admin\FestivalController@add')->name('admin.color.add');

                /**
                 * Festival Route
                 */
                Router::get('/festival/add', 'Admin\festivalController@add')->name('admin.festival.add');
                Router::get('/festival/edit/{id}', 'Admin\FestivalController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.festival.edit');
                Router::get('/festival/view', 'Admin\FestivalController@view')->name('admin.festival.view');

                /**
                 * Blog Category Route
                 */
                Router::get('/blog/category/add', 'Admin\BlogController@CategoryAdd')->name('admin.blog.category.add');
                Router::get('/blog/category/edit/{id}', 'Admin\BlogController@CategoryEdit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.blog.category.edit');
                Router::get('blog/category/view', 'Admin\BlogController@CategoryView')->name('admin.blog.category.view');

                /**
                 * Contact us Route
                 */
                Router::get('/contact-us/view/{id?}', 'Admin\ContactUsController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.contact-us.view');

                /**
                 * Unit Route
                 */
                Router::get('/unit/view', 'Admin\UnitController@view')->name('admin.unit.view');

                /**
                 * FAQ Route
                 */
                Router::get('/faq/view', 'Admin\FaqController@view')->name('admin.faq.view');

                /**
                 * complaints Route
                 */
                Router::get('/complaints/view/{id?}', 'Admin\complaintsController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.complaints.view');

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
                Router::get('/wallet/deposit-type', 'Admin\WalletController@depositType')->name('admin.wallet.deposit-type');

                /**
                 * Order Route
                 */
                Router::get('/order/view/{id?}', 'Admin\OrderController@view')->where([
                    'id' => '[0-9]+',
                ])->name('admin.order.view');
                Router::get('/order/badges', 'Admin\OrderController@badges')->name('admin.order.badges');
                Router::get('/order/return-order/{id?}', 'Admin\OrderController@returnOrder')->where([
                    'id' => '[0-9]+',
                ])->name('admin.return.order');

                /**
                 * Slider Route
                 */
                Router::get('/slider/view', 'Admin\SliderController@view')->name('admin.slider.view');

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
            Router::post('/newsletter/remove', 'PageController@removeNewsletter')->name('ajax.newsletter.remove');

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

            /**
             * get captcha image
             */
            Router::get('/captcha', 'CaptchaController@generateCaptcha')->name('api.captcha');
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
