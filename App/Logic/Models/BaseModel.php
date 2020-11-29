<?php

namespace App\Logic\Models;

use Aura\Sql\ExtendedPdoInterface;
use Sim\DBConnector;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class BaseModel
{
    /**
     * Tables
     */
    const TBL_BLOG = 'blog';
    const TBL_BLOG_CATEGORIES = 'blog_categories';
    const TBL_BRANDS = 'brands';
    const TBL_CATEGORIES = 'categories';
    const TBL_CATEGORY_IMAGES = 'category_images';
    const TBL_CITIES = 'cities';
    const TBL_COLORS = 'colors';
    const TBL_COMPLAINTS = 'complaints';
    const TBL_CONTACT_US = 'contact_us';
    const TBL_COUPONS = 'coupons';
    const TBL_DEPOSIT_TYPES = 'deposit_types';
    const TBL_FAQ = 'faq';
    const TBL_FESTIVALS = 'festivals';
    const TBL_INSTAGRAM_IMAGES = 'instagram_images';
    const TBL_MAIN_SLIDER = 'main_slider';
    const TBL_ORDERS = 'orders';
    const TBL_ORDER_BADGES = 'order_badges';
    const TBL_ORDER_ITEMS = 'order_items';
    const TBL_ORDER_RESERVED = 'order_reserved';
    const TBL_PRODUCTS = 'products';
    const TBL_PRODUCT_ADVANCED = 'product_advanced';
    const TBL_PRODUCT_CATEGORY_FESTIVAL = 'product_category_festival';
    const TBL_PRODUCT_FESTIVAL = 'product_festival';
    const TBL_PRODUCT_GALLERY = 'product_gallery';
    const TBL_PRODUCT_RELATED = 'product_related';
    const TBL_PROVINCES = 'provinces';
    const TBL_ROLES = 'roles';
    const TBL_STATIC_PAGES = 'static_pages';
    const TBL_STEPPED_PRICES = 'stepped_prices';
    const TBL_UNITS = 'units';
    const TBL_USERS = 'users';
    const TBL_USER_ADDRESS = 'user_address';
    const TBL_USER_ROLE = 'user_role';
    const TBL_WALLET = 'wallet';
    const TBL_WALLET_FLOW = 'wallet_flow';

    /**
     * @var DBConnector
     */
    protected $connector;

    /**
     * @var ExtendedPdoInterface
     */
    protected $db;

    /**
     * Model constructor.
     */
    /**
     * BaseModel constructor.
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function __construct()
    {
        $this->connector = \connector();
        $this->db = $this->connector->getDb();
    }
}