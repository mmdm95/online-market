<?php

namespace App\Logic\Abstracts;

use Sim\Auth\DBAuth;
use Sim\Cart\Interfaces\IDBException as ICartDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws ICartDBException
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
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