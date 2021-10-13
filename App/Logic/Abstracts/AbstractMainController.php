<?php

namespace App\Logic\Abstracts;

use App\Logic\Utils\CartUtil;
use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Cart\Interfaces\IDBException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class AbstractMainController extends AbstractController
{
    /**
     * AbstractAdminController constructor.
     * @throws ConfigNotRegisteredException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        parent::__construct();

        // restore all items from cookie storage
        cart()->restore(true);

        /**
         * @var CartUtil $cartUtil
         */
        $cartUtil = \container()->get(CartUtil::class);

        $this->setDefaultArguments(array_merge($this->getDefaultArguments(), [
            'cart_section' => $cartUtil->getCartSection(),
        ]));
    }
}