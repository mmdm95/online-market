<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
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
use App\Logic\Utils\Jdf;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class HomeController extends AbstractAdminController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws IDBException
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
            $productWhere = 'is_deleted=:del';
            $productBindValues['del'] = DB_YES;
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
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function login()
    {
        if (is_post()) {

        }

        $this
            ->setLayout('admin-login')
            ->setTemplate('view/admin-login');
        return $this->render();
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function browser()
    {
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
     */
    public function logout()
    {
        response()->redirect(url('admin.login')->getRelativeUrl(), 301);
    }
}