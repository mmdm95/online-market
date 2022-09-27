<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\LoginForm as AdminLoginForm;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\BaseModel;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Models\BlogModel;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ColorModel;
use App\Logic\Models\ComplaintModel;
use App\Logic\Models\ContactUsModel;
use App\Logic\Models\CouponModel;
use App\Logic\Models\FAQModel;
use App\Logic\Models\FestivalModel;
use App\Logic\Models\InstagramImagesModel;
use App\Logic\Models\Model;
use App\Logic\Models\NewsletterModel;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\SecurityQuestionModel;
use App\Logic\Models\SliderModel;
use App\Logic\Models\StaticPageModel;
use App\Logic\Models\UnitModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\ChartUtil;
use App\Logic\Utils\Jdf;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Auth\Exceptions\IncorrectPasswordException;
use Sim\Auth\Exceptions\InvalidUserException;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;

class HomeController extends AbstractAdminController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function index()
    {
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var PaymentMethodModel $payMethodModel
         */
        $payMethodModel = container()->get(PaymentMethodModel::class);
        /**
         * @var ColorModel $colorModel
         */
        $colorModel = container()->get(ColorModel::class);
        /**
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var FestivalModel $festivalModel
         */
        $festivalModel = container()->get(FestivalModel::class);
        /**
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);
        /**
         * @var CouponModel $couponModel
         */
        $couponModel = container()->get(CouponModel::class);
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var BlogModel $blogModel
         */
        $blogModel = container()->get(BlogModel::class);
        /**
         * @var BlogCategoryModel $blogCategoryModel
         */
        $blogCategoryModel = container()->get(BlogCategoryModel::class);
        /**
         * @var StaticPageModel $staticPageModel
         */
        $staticPageModel = container()->get(StaticPageModel::class);
        /**
         * @var ContactUsModel $contactModel
         */
        $contactModel = container()->get(ContactUsModel::class);
        /**
         * @var ComplaintModel $complaintModel
         */
        $complaintModel = container()->get(ComplaintModel::class);
        /**
         * @var FAQModel $faqModel
         */
        $faqModel = container()->get(FAQModel::class);
        /**
         * @var NewsletterModel $newletterModel
         */
        $newletterModel = container()->get(NewsletterModel::class);
        /**
         * @var SliderModel $sliderModel
         */
        $sliderModel = container()->get(SliderModel::class);
        /**
         * @var InstagramImagesModel $instagramModel
         */
        $instagramModel = container()->get(InstagramImagesModel::class);
        /**
         * @var SecurityQuestionModel $secQuestionModel
         */
        $secQuestionModel = container()->get(SecurityQuestionModel::class);
        /**
         * @var OrderBadgeModel $orderBadgeModel
         */
        $orderBadgeModel = container()->get(OrderBadgeModel::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        $productWhere = 'is_deleted=:del';
        $productBindValues = ['del' => DB_NO];
        if ($auth->userHasRole(ROLE_DEVELOPER) || $auth->userHasRole(ROLE_SUPER_USER)) {
            $productWhere = '';
            $productBindValues = [];
        }
        $productCount = $productModel->getLimitedProductCount($productWhere, $productBindValues);

        // get badges
        $badgeIds = $orderBadgeModel->get(['code', 'title', 'color'], 'is_deleted=:del', ['del' => DB_NO], ['id ASC']);
        $orderBadges = [];
        foreach ($badgeIds as $k => $badge) {
            $orderBadges[$k] = $badge;
            $orderBadges[$k]['count'] = $orderModel->count(
                'send_status_code=:ssc',
                [
                    'ssc' => $badge['code'],
                ]
            );
        }

        $this
            ->setLayout($this->main_layout)
            ->setTemplate('view/index');
        return $this->render([
            'today_date' => Jdf::jdate('l d F Y') . ' - ' . date('d F'),
            'unread_contact_count' => $contactModel->count(
                'status=:status',
                [
                    'status' => DB_NO,
                ]
            ),
            'unread_complaint_count' => $complaintModel->count(
                'status=:status',
                [
                    'status' => COMPLAINT_STATUS_UNREAD,
                ]
            ),
            //-----
            'order_badges_count' => $orderBadges,
            //-----
            'users_count' => $userModel->getUsersCount(
                'r.show_to_user=:stu',
                [
                    'stu' => DB_YES,
                ]
            ),
            'user_admin_count' => $userModel->getUsersCount(
                'r.is_admin=:isAdmin AND r.show_to_user=:stu',
                [
                    'isAdmin' => DB_YES,
                    'stu' => DB_YES,
                ]
            ),
            'user_normal_count' => $userModel->getUsersCount(
                'r.is_admin=:isAdmin AND r.show_to_user=:stu',
                [
                    'isAdmin' => DB_NO,
                    'stu' => DB_YES,
                ]
            ),
            'user_deactivate_count' => $userModel->getUsersCount(
                'u.is_activated=:isActive AND r.show_to_user=:stu',
                [
                    'isActive' => DB_NO,
                    'stu' => DB_YES,
                ]
            ),
            'payment_method_count' => $payMethodModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'color_count' => $colorModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'brand_count' => $brandModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'category_count' => $categoryModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'festival_count' => $festivalModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'unit_count' => $unitModel->count(),
            'coupon_count' => $couponModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'product_count' => $productCount,
            'order_count' => $orderModel->count(),
            'blog_count' => $blogModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'blog_category_count' => $blogCategoryModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'static_page_count' => $staticPageModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'contact_count' => $contactModel->count(),
            'complaint_count' => $complaintModel->count(),
            'faq_count' => $faqModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
            'newsletter_count' => $newletterModel->count(),
            'slide_show_count' => $sliderModel->count(),
            'instagram_image_count' => $instagramModel->count(),
            'security_question_count' => $secQuestionModel->count(
                'publish=:pub',
                [
                    'pub' => DB_YES,
                ]
            ),
        ]);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws FormException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function login()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        if ($auth->isLoggedIn()) {
            $backUrl = ArrayUtil::get($_GET, 'back_url', null);
            $backUrl = urldecode($backUrl);
            if (!empty($backUrl)) {
                response()->redirect($backUrl, 301);
            } else {
                response()->redirect(url('admin.index')->getRelativeUrlTrimmed(), 301);
            }
        }
        $data = [];
        if (is_post()) {
            $auth->logout();
            try {
                /**
                 * @var AdminLoginForm $loginForm
                 */
                $loginForm = container()->get(AdminLoginForm::class);
                [$status, $errors] = $loginForm->validate();
                if ($status) {
                    $auth->login([
                        'username' => input()->post('inp-username', '')->getValue(),
                        'password' => input()->post('inp-password', '')->getValue()
                    ], BaseModel::TBL_USERS . '.is_activated=:isActive',
                        [
                            'isActive' => DB_YES
                        ]);
                    if ($auth->isLoggedIn()) {
                        $backUrl = ArrayUtil::get($_GET, 'back_url', null);
                        $backUrl = urldecode($backUrl);
                        if (!empty($backUrl)) {
                            response()->redirect($backUrl);
                        } else {
                            response()->redirect(url('admin.index')->getRelativeUrlTrimmed());
                        }
                    } else {
                        $data['login_errors'] = [
                            'عملیات ورود ناموفق بود، لطفا مجددا تلاش نمایید.'
                        ];
                    }
                } else {
                    $data['login_errors'] = $errors;
                }
            } catch (IncorrectPasswordException|InvalidUserException|IDBException $e) {
                $data['login_errors'] = [
                    'نام کاربری یا کلمه عبور نادرست است!'
                ];
            }
        }

        $this
            ->setLayout('admin-login')
            ->setTemplate('view/admin-login');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function browser()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setTemplate('partial/editor/browser');
        return $this->render([
            'the_options' => [
                'allow_upload' => false,
                'allow_create_folder' => false,
                'allow_direct_link' => false,
            ],
        ]);
    }

    /**
     * Logout from system
     *
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function logout()
    {
        auth_admin()->logout();
        response()->redirect(url('admin.login')->getRelativeUrl(), 301);
    }

    //------------------------------------------------
    // Chart Methods
    //------------------------------------------------

    /**
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function chartBoughtStatus()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->userHasRole(ROLE_DEVELOPER) && !$auth->userHasRole(ROLE_SUPER_USER)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data(ChartUtil::getBoughStatus());
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }
}
