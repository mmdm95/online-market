<?php

namespace Tests;

use App\Logic\Models\Model;
use Aura\SqlQuery\Mysql\Insert;
use Faker\Factory;
use Faker\Generator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

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
                                'name' => 'پیشنهاد ویژه',
                                'type' => SLIDER_TABBED_SPECIAL,
                                'category' => null,
                                'limit' => 10,
                            ],
                        ],
                    ]),
                    'group_name' => 'index_page',
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
                    'setting_name' => 'footer_section_1',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_section_2',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_namad_1',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => '',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_namad_2',
                    'setting_value' => '',
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

    public function users()
    {
        // clear all

        // create new fake data

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