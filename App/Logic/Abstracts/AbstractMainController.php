<?php

namespace App\Logic\Abstracts;

use App\Logic\Utils\CartUtil;
use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Cart\Interfaces\IDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class AbstractMainController extends AbstractController
{
    /**
     * AbstractAdminController constructor.
     * @throws \ReflectionException
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function __construct()
    {
        parent::__construct();

        // restore all items from cookie storage
        cart()->restore();

        /**
         * @var CartUtil $cartUtil
         */
        $cartUtil = \container()->get(CartUtil::class);

        $this->setDefaultArguments([
            'cart_section' => $cartUtil->getCartSection(),
        ]);
    }
}