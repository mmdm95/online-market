<?php

namespace Tests;

use App\Logic\Models\BaseModel;
use App\Logic\Models\Model;
use App\Logic\Models\UserModel;
use Aura\SqlQuery\Mysql\Insert;
use Faker\Factory;
use Faker\Generator;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IAuthenticator;
use Sim\Auth\Interfaces\IAuthorizer;
use Sim\Auth\Interfaces\IAuthValidator;
use Sim\Auth\Interfaces\IAuthVerifier;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Utils\ArrayUtil;

class FakeData
{
    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var Model
     */
    private $model;

    /**
     * FakeData constructor.
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function __construct()
    {
        $this->faker = Factory::create('fa_IR');
        $this->model = \container()->get(Model::class);
    }

    public function setupConfig()
    {
        // clear all
        $this->deleteAllFromTable('settings');

        /**
         * @var Insert $insert
         */
        $insert = $this->model->insert();
        $insert
            ->into('settings')
            ->addRows([
                // index page 3 images
                [
                    'setting_name' => 'index_3_images',
                    'setting_value' => json_encode([
                        [
                            'image' => 'image name',
                            'link' => '#',
                        ],
                        [
                            'image' => 'image name 2',
                            'link' => '#',
                        ],
                        [
                            'image' => 'image name 3',
                            'link' => '#',
                        ],
                    ]),
                    'group_name' => 'index_page',
                    'default_value' => '',
                    'desc' => '',
                ],
                // index page tabbed slider
                [
                    'setting_name' => 'index_tabbed_slider',
                    'setting_value' => json_encode([
                        'title' => 'محصولات اختصاصی',
                        'items' => [
                            /*
                             * [
                             *   [
                             *     'name' => name of tab,
                             *     'type' => predefined type,
                             *     'category' => id of category,
                             *     'limit' => 10,
                             *   ]
                             * ]
                             */
                            [
                                'name' => 'محصول جدید',
                                'type' => SLIDER_TABBED_NEWEST,
                                'category' => null,
                                'limit' => 10,
                            ],
                            [
                                'name' => 'پر فروش',
                                'type' => SLIDER_TABBED_MOST_SELLER,
                                'category' => null,
                                'limit' => 10,
                            ],
                            [
                                'name' => 'محصول ویژه',
                                'type' => SLIDER_TABBED_FEATURED,
                                'category' => null,
                                'limit' => 10,
                            ],
                            [
                                'name' => 'پرتخفیف ترین',
                                'type' => SLIDER_TABBED_MOST_DISCOUNT,
                                'category' => null,
                                'limit' => 10,
                            ],
                        ],
                    ]),
                    'group_name' => 'index_page',
                    'default_value' => '',
                    'desc' => '',
                ],
                // about us
                [
                    'setting_name' => 'about_section',
                    'setting_value' => json_encode([
                        'image' => '',
                        'title' => 'ما کی هستیم',
                        'desc' => encode_html('لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است
برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد.'),
                    ]),
                    'group_name' => 'about_page',
                    'default_value' => '',
                    'desc' => '',
                ],
                // normal menu
                [
                    'setting_name' => 'top_menu',
                    'setting_value' => json_encode([
                        [
                            'name' => 'خانه',
                            'link' => '#',
                            'children' => [],
                        ],
                        [
                            'name' => 'وبلاگ',
                            'link' => '#',
                            'children' => [],
                        ],
                        [
                            'name' => 'تماس با ما',
                            'link' => '#',
                            'children' => [],
                        ],
                    ]),
                    'group_name' => 'menu',
                    'default_value' => '',
                    'desc' => '',
                ],
                // pagination
                [
                    'setting_name' => 'blog_each_page',
                    'setting_value' => '',
                    'group_name' => 'pagination',
                    'default_value' => '15',
                    'desc' => 'تعداد نمایش بلاگ در هر صفحه',
                ],
                [
                    'setting_name' => 'product_each_page',
                    'setting_value' => '',
                    'group_name' => 'pagination',
                    'default_value' => '15',
                    'desc' => 'تعداد نمایش محصول در هر صفحه',
                ],
                // main
                [
                    'setting_name' => 'logo_light',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'logo/logo_light.png',
                    'desc' => 'لوگوی روشن سایت (برای مثال سفید رنگ)',
                ],
                [
                    'setting_name' => 'logo',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'logo/logo_dark.png',
                    'desc' => 'لوگوی اصلی سایت',
                ],
                [
                    'setting_name' => 'favicon',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => '',
                    'desc' => 'فاو آیکون - در بالای پنجره اصلی سایت نمایش داده می‌شود(برای سئو نیز توصیه می‌شود).',
                ],
                [
                    'setting_name' => 'title',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'Heeva Team',
                    'desc' => 'عنوان اصلی سایت - نمایش در تمام پنجره‌های سایت',
                ],
                [
                    'setting_name' => 'description',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'Heeva is a team that create quality codes',
                    'desc' => 'توضیحات درباره سایت - برای سئو توصیه می‌شود.',
                ],
                [
                    'setting_name' => 'keywords',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'Heeva,heeva team,هیوا,تبم هیوا,programming',
                    'desc' => 'کلمات کلیدی سایت - برای سئو توصیه می‌شود.',
                ],
                // sms
                [
                    'setting_name' => 'sms_activation',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => '',
                    'desc' => 'پیامک برای ارسال کد فعالسازی',
                ],
                [
                    'setting_name' => 'sms_recover_pass',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => '',
                    'desc' => 'پیامک برای ارسال کد فراموشی کلمه عبور',
                ],
                [
                    'setting_name' => 'sms_buy',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => '',
                    'desc' => 'پیامک برای ارسال کد سفارش بعد از خرید',
                ],
                [
                    'setting_name' => 'sms_order_status',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => '',
                    'desc' => 'پیامک برای آگاهی از تغییر وضعیت سفارش',
                ],
                [
                    'setting_name' => 'sms_wallet_charge',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => '',
                    'desc' => 'پیامک برای آگاهی از وضعیت کیف پول',
                ],
                // features
                [
                    'setting_name' => 'features',
                    'setting_value' => json_encode([
                        [
                            'title' => 'ارسال رایگان',
                            'sub_title' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
                        ],
                        [
                            'title' => '30 روز ضمانت بازگشت',
                            'sub_title' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
                        ],
                        [
                            'title' => 'پشتیبانی 24 ساعته',
                            'sub_title' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
                        ],
                    ]),
                    'group_name' => 'contact',
                    'default_value' => 'other',
                    'desc' => '',
                ],
                // contact
                [
                    'setting_name' => 'address',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => '',
                    'desc' => 'آدرس محل کسب و کار - نمایش در فوتر و صفحه تماس با ما',
                ],
                [
                    'setting_name' => 'phones',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => '',
                    'desc' => 'شماره‌های تماس - نمایش در فوتر و صفحه تماس با ما',
                ],
                [
                    'setting_name' => 'main_phone',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => '09139518055',
                    'desc' => 'شماره تماس اصلی',
                ],
                [
                    'setting_name' => 'email',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => 'info@sitename.com',
                    'desc' => 'ایمیل سایت',
                ],
                [
                    'setting_name' => 'lat_lng',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => json_encode([
                        'lat' => '35.804357',
                        'lng' => '51.414715',
                    ]),
                    'desc' => 'ایمیل سایت',
                ],
                // our team
                [
                    'setting_name' => 'our_team',
                    'setting_value' => json_encode([
                        'sub_title' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است.',
                    ]),
                    'group_name' => 'our_team',
                    'default_value' => '',
                    'desc' => null,
                ],
                // socials
                [
                    'setting_name' => 'social_telegram',
                    'setting_value' => '',
                    'group_name' => 'social',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'social_instagram',
                    'setting_value' => '',
                    'group_name' => 'social',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'social_whatsapp',
                    'setting_value' => '',
                    'group_name' => 'social',
                    'default_value' => '',
                    'desc' => null,
                ],
                // footer
                [
                    'setting_name' => 'footer_tiny_desc',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_section_1',
                    'setting_value' => json_encode([
                        'title' => 'لینک های مفید',
                        'links' => [
                            [
                                'name' => 'درباره ما',
                                'link' => '#',
                            ],
                            [
                                'name' => 'سؤالات متداول',
                                'link' => '#',
                            ],
                            [
                                'name' => 'موقعیت',
                                'link' => '#',
                            ],
                            [
                                'name' => 'شرکت ها',
                                'link' => '#',
                            ],
                            [
                                'name' => 'تماس',
                                'link' => '#',
                            ],
                        ],
                    ]),
                    'group_name' => 'footer',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_section_2',
                    'setting_value' => json_encode([
                        'title' => 'حساب کاربری من',
                        'links' => [
                            [
                                'name' => 'حساب کاربری',
                                'link' => '#',
                            ],
                            [
                                'name' => 'تخفیف',
                                'link' => '#',
                            ],
                            [
                                'name' => 'بازگشتی',
                                'link' => '#',
                            ],
                            [
                                'name' => 'تاریخچه سفارشات',
                                'link' => '#',
                            ],
                            [
                                'name' => 'رهگیری سفارش',
                                'link' => '#',
                            ],
                        ],
                    ]),
                    'group_name' => 'footer',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_namads',
                    'setting_value' => json_encode([]),
                    'group_name' => 'footer',
                    'default_value' => '',
                    'desc' => null,
                ],
            ]);
        $this->model->execute($insert);
        //-----
    }

    public function createUserWithRole($role)
    {

    }

    public function clearAllTables()
    {
//        foreach ([
//                     'blog', 'blog_categories', 'categories', 'colors', 'complaints',
//                     'contact_us', 'coupons', 'faq', 'main_slider', 'products', 'users'
//                 ] as $table) {
        // delete all data from table

//        }
    }

    public function blog()
    {
        // clear all

        // create new fake data

    }

    public function blogCategories()
    {
        // clear all

        // create new fake data

    }

    public function categories()
    {
        // clear all
        $this->deleteAllFromTable('categories');
        $parents1 = [];
        $parents2 = [];

        // create new fake data
        // menu level1
        for ($i = 1; $i <= 20; $i++) {
            $parents1[] = $i;
            $insert = $this->model->insert();
            $insert
                ->into('categories')
                ->cols([
                    'id' => $i,
                    'name' => $this->faker->lexify('level 1 menu - ????'),
                    'parent_id' => null,
                    'priority' => $this->faker->numberBetween(0, 20),
                    'level' => 1,
                    'show_in_menu' => $this->faker->randomElement([0, 1]),
                    'keywords' => implode(',', $this->faker->words(8)),
                    'created_at' => time(),
                ]);
            $this->model->execute($insert);
        }
        // menu level2
        for ($i = 21; $i <= 40; $i++) {
            $parents2[] = $i;
            $insert = $this->model->insert();
            $insert
                ->into('categories')
                ->cols([
                    'id' => $i,
                    'name' => $this->faker->lexify('level 2 menu - ????'),
                    'parent_id' => $this->faker->randomElement($parents1),
                    'priority' => $this->faker->numberBetween(20, 35),
                    'level' => 2,
                    'show_in_menu' => $this->faker->randomElement([0, 1]),
                    'keywords' => implode(',', $this->faker->words(8)),
                    'created_at' => time(),
                ]);
            $this->model->execute($insert);
        }
        // menu level3
        for ($i = 41; $i <= 60; $i++) {
            $insert = $this->model->insert();
            $insert
                ->into('categories')
                ->cols([
                    'id' => $i,
                    'name' => $this->faker->lexify('level 3 menu - ????'),
                    'parent_id' => $this->faker->randomElement($parents2),
                    'priority' => $this->faker->numberBetween(35, 45),
                    'level' => 3,
                    'show_in_menu' => $this->faker->randomElement([0, 1]),
                    'keywords' => implode(',', $this->faker->words(8)),
                    'created_at' => time(),
                ]);
            $this->model->execute($insert);
        }
    }

    public function colors()
    {
        // clear all

        // create new fake data

    }

    public function complaints()
    {
        // clear all

        // create new fake data

    }

    public function contactUs()
    {
        // clear all

        // create new fake data

    }

    public function coupons()
    {
        // clear all

        // create new fake data

    }

    public function faq()
    {
        // clear all

        // create new fake data

    }

    public function mainSliders()
    {
        // clear all

        // create new fake data

    }

    public function products()
    {
        // clear all

        // create new fake data

    }

    /**
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function createAdminUser()
    {
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        //
        $userModel->registerUser([
            'username' => '09139518055',
            'password' => password_hash('heeva92155', PASSWORD_BCRYPT),
            'first_name' => 'سعید',
            'last_name' => 'گرامی فر',
            'image' => PLACEHOLDER_USER_IMAGE,
            'is_activated' => 1,
            'activated_at' => time(),
            'created_at' => time(),
        ], [ROLE_SUPER_USER]);
        //
        $userModel->registerUser([
            'username' => '09179516271',
            'password' => password_hash('m9516271', PASSWORD_BCRYPT),
            'first_name' => 'محمد مهدی',
            'last_name' => 'دهقان منشادی',
            'image' => PLACEHOLDER_USER_IMAGE,
            'is_activated' => 1,
            'activated_at' => time(),
            'created_at' => time(),
        ], [ROLE_DEVELOPER]);
    }

    /**
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function users()
    {
        // clear all
        $this->deleteAllFromTable(BaseModel::TBL_USERS);

        // create new fake data
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        for ($i = 0; $i < 100; $i++) {
            $rndActive = $this->faker->randomElement([0, 1]);
            $activatedAt = 1 === $rndActive ? time() : null;
            $roleSubset = $this->faker->randomElements([
                ROLE_ADMIN,
                ROLE_COLLEAGUE,
                ROLE_USER
            ], $this->faker->numberBetween(1, 3));

            $userModel->registerUser([
                'username' => $this->faker->mobileNumber,
                'password' => password_hash('123456789', PASSWORD_BCRYPT),
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'image' => PLACEHOLDER_USER_IMAGE,
                'is_activated' => $rndActive,
                'activated_at' => $activatedAt,
                'created_at' => time(),
            ], $roleSubset);
        }
    }

    public function definePageResPerm()
    {
        $structure = [
            ROLE_DEVELOPER => [
                'user',
                'pay_method',
                'color',
                'brand',
                'category',
                'festival',
                'unit',
                'coupon',
                'product',
                'wallet',
                'order',
                'report_user',
                'report_product',
                'report_wallet',
                'report_order',
                'blog',
                'blog_category',
                'static_page',
                'contact_us',
                'complaint',
                'faq',
                'newsletter',
                'slideshow',
                'instagram',
                'sec_question',
                'filemanager',
                'setting',
            ],
            ROLE_SUPER_USER => [
                'user',
                'pay_method',
                'color',
                'brand',
                'category',
                'festival',
                'unit',
                'coupon',
                'product',
                'wallet',
                'order',
                'report_user',
                'report_product',
                'report_wallet',
                'report_order',
                'blog',
                'blog_category',
                'static_page',
                'contact_us',
                'complaint',
                'faq',
                'newsletter',
                'slideshow',
                'instagram',
                'sec_question',
                'filemanager',
                'setting',
            ],
            ROLE_ADMIN => [
                'pay_method',
                'color',
                'brand',
                'category',
                'festival',
                'unit',
                'coupon',
                'product',
                'wallet',
                'order',
                'report_user',
                'report_product',
                'report_wallet',
                'report_order',
                'blog',
                'blog_category',
                'static_page',
                'contact_us',
                'complaint',
                'faq',
                'newsletter',
                'slideshow',
                'instagram',
                'sec_question',
                'filemanager',
                'setting',
            ],
            ROLE_SHOP_ADMIN => [
                'color',
                'brand',
                'category',
                'festival',
                'unit',
                'coupon',
                'product',
                'wallet',
                'order',
                'report_product',
                'report_wallet',
                'report_order',
                'filemanager',
            ],
            ROLE_WRITER => [
                'blog',
                'blog_category',
                'static_page',
                'filemanager',
            ],
        ];

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        $roles = $auth->getRoles();
        $roles = ArrayUtil::arrayGroupBy('name', $roles);
        $resources = $auth->getResources();
        $resources = ArrayUtil::arrayGroupBy('name', $resources);
        $permissions = [
            IAuth::PERMISSION_CREATE,
            IAuth::PERMISSION_READ,
            IAuth::PERMISSION_UPDATE,
            IAuth::PERMISSION_DELETE
        ];

        foreach ($structure as $role => $resNames) {
            $roleId = (int)$roles[$role][0]['id'];

            foreach ($resNames as $resName) {
                $resId = (int)$resources[$resName][0]['id'];

                /**
                 * @var Insert $insert
                 */
                foreach ($permissions as $permission) {
                    $insert = $this->model->insert();
                    $insert
                        ->into('role_res_perm')
                        ->cols([
                            'role_id' => $roleId,
                            'resource_id' => $resId,
                            'perm_id' => $permission,
                        ]);
                    $this->model->execute($insert);
                }
            }
        }
    }

    /**
     * @param string $table
     * @return bool
     */
    private function deleteAllFromTable(string $table): bool
    {
        $delete = $this->model->delete();
        $delete->from($table);
        return $this->model->execute($delete);
    }
}