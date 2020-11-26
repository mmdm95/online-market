<?php

namespace Tests;

use App\Logic\Models\Model;
use Aura\SqlQuery\Mysql\Insert;
use Faker\Factory;
use Faker\Generator;

class FakeData
{
    /**
     * @var Generator
     */
    private $faker;

    /**
     * FakeData constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('fa_IR');
    }

    public function setupConfig()
    {
        /**
         * @var Model $model
         */
        $model = \container()->get(Model::class);

        //-----
        /**
         * @var Insert $insert
         */
        $insert = $model->insert();
        $insert
            ->into('settings')
            ->addRows([
                // main
                [
                    'setting_name' => 'logo_light',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'test',
                    'desc' => 'لوگوی روشن سایت (برای مثال سفید رنگ)',
                ],
                [
                    'setting_name' => 'logo',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'test',
                    'desc' => 'لوگوی اصلی سایت',
                ],
                [
                    'setting_name' => 'favicon',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'test',
                    'desc' => 'فاو آیکون - در بالای پنجره اصلی سایت نمایش داده می‌شود(برای سئو نیز توصیه می‌شود).',
                ],
                [
                    'setting_name' => 'title',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'test',
                    'desc' => 'عنوان اصلی سایت - نمایش در تمام پنجره‌های سایت',
                ],
                [
                    'setting_name' => 'description',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'test',
                    'desc' => 'توضیحات درباره سایت - برای سئو توصیه می‌شود.',
                ],
                [
                    'setting_name' => 'keywords',
                    'setting_value' => '',
                    'group_name' => 'main',
                    'default_value' => 'test',
                    'desc' => 'کلمات کلیدی سایت - برای سئو توصیه می‌شود.',
                ],
                // sms
                [
                    'setting_name' => 'sms_activation',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => 'test',
                    'desc' => 'پیامک برای ارسال کد فعالسازی',
                ],
                [
                    'setting_name' => 'sms_recover_pass',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => 'test',
                    'desc' => 'پیامک برای ارسال کد فراموشی کلمه عبور',
                ],
                [
                    'setting_name' => 'sms_buy',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => 'test',
                    'desc' => 'پیامک برای ارسال کد سفارش بعد از خرید',
                ],
                [
                    'setting_name' => 'sms_order_status',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => 'test',
                    'desc' => 'پیامک برای آگاهی از تغییر وضعیت سفارش',
                ],
                [
                    'setting_name' => 'sms_wallet_charge',
                    'setting_value' => '',
                    'group_name' => 'sms',
                    'default_value' => 'test',
                    'desc' => 'پیامک برای آگاهی از وضعیت کیف پول',
                ],
                // contact
                [
                    'setting_name' => 'address',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => 'test',
                    'desc' => 'آدرس محل کسب و کار - نمایش در فوتر و صفحه تماس با ما',
                ],
                [
                    'setting_name' => 'phones',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => 'test',
                    'desc' => 'شماره‌های تماس - نمایش در فوتر و صفحه تماس با ما',
                ],
                [
                    'setting_name' => 'main_phone',
                    'setting_value' => '',
                    'group_name' => 'contact',
                    'default_value' => 'test',
                    'desc' => 'شماره تماس اصلی',
                ],
                // socials
                [
                    'setting_name' => 'social_telegram',
                    'setting_value' => '',
                    'group_name' => 'social',
                    'default_value' => 'test',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'social_instagram',
                    'setting_value' => '',
                    'group_name' => 'social',
                    'default_value' => 'test',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'social_whatsapp',
                    'setting_value' => '',
                    'group_name' => 'social',
                    'default_value' => 'test',
                    'desc' => null,
                ],
                // footer
                [
                    'setting_name' => 'footer_section_1',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => 'test',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_section_2',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => 'test',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_namad_1',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => 'test',
                    'desc' => null,
                ],
                [
                    'setting_name' => 'footer_namad_2',
                    'setting_value' => '',
                    'group_name' => 'footer',
                    'default_value' => 'test',
                    'desc' => null,
                ],
            ]);
        $model->execute($insert);
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

        // create new fake data

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
}