<?php

namespace App\Logic\Abstracts;

use Sim\Auth\DBAuth;
use Sim\Cart\Interfaces\IDBException as ICartDBException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class AbstractHomeController extends AbstractMainController
{
    /**
     * @var string
     */
    protected $main_layout = 'main';

    /**
     * @var string
     */
    protected $main_index_layout = 'main-index';

    /**
     * AbstractHomeController constructor.
     * @throws ConfigNotRegisteredException
     * @throws ICartDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        $auth->resume()->isLoggedIn();
    }
}