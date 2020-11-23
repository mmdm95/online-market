<?php

namespace Tests;

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

    public function clearAllTables()
    {
        foreach ([
                     'blog', 'blog_categories', 'categories', 'colors', 'complaints',
                     'contact_us', 'coupons', 'faq', 'main_slider', 'products', 'users'
                 ] as $table) {
            // delete all data from table

        }
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