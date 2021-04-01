<?php

namespace App\Logic\Abstracts;

use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class AbstractAdminController extends AbstractController
{
    /**
     * @var string
     */
    protected $main_layout = 'admin';

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

        $this->setDefaultArguments([
            'the_options' => [
                'allow_rename' => true,
                'allow_upload' => true,
                'allow_create_folder' => true,
                'allow_direct_link' => true,
                'MAX_UPLOAD_SIZE' => max_upload_size(),
            ],
        ]);

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $auth->resume()->isLoggedIn();
    }

    /**
     * {@inheritdoc}
     */
    public function show404(array $arguments = [], ?string $layout = '', string $template = 'error/404'): string
    {
        return parent::show404($arguments, $layout, $template);
    }
}