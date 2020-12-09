<?php

namespace App\Logic\Abstracts;

use App\Logic\Utils\CartUtil;
use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class AbstractColleagueController extends AbstractController
{
    /**
     * @var string
     */
    protected $main_layout = 'main-colleague';

    /**
     * AbstractAdminController constructor.
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * @var CartUtil $cartUtil
         */
        $cartUtil = \container()->get(CartUtil::class);

        $this->setDefaultArguments([
            'cart_section' => $cartUtil->getCartSection(),
        ]);
    }
}