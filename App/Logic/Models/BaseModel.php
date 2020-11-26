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
    const TBL_USERS = 'users';
    const TBL_USER_ADDRESS = 'user_address';
    const TBL_ROLES = 'roles';
    const TBL_USER_ROLE = 'user_role';
    const TBL_PROVINCES = 'provinces';
    const TBL_CITIES = 'cities';
    const TBL_BLOG = 'blog';
    const TBL_BLOG_CATEGORIES = 'blog_categories';
    const TBL_CATEGORIES = 'categories';

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