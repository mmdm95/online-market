<?php

namespace App\Logic\Abstracts;

use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class AbstractHomeController extends AbstractController
{
    /**
     * @var string
     */
    protected $main_layout = 'main';

    /**
     * AbstractAdminController constructor.
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultArguments([]);
    }
}