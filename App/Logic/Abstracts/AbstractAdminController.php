<?php

namespace App\Logic\Abstracts;

use App\Logic\Models\UserModel;
use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IDBException;
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
     * @throws IDBException
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $auth->resume()->isLoggedIn();

        $id = $auth->getCurrentUser()['id'] ?? null;
        $info = [
            'info' => [],
            'role' => [],
        ];
        if (!is_null($id)) {
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);
            $info['info'] = $userModel->getFirst(['*'], 'id=:id', ['id' => $id]);
            $info['role'] = $auth->getCurrentUserRole();
        }

        $this->setDefaultArguments([
            'the_options' => [
                'allow_rename' => true,
                'allow_upload' => true,
                'allow_create_folder' => true,
                'allow_direct_link' => true,
                'MAX_UPLOAD_SIZE' => max_upload_size(),
            ],
            'main_user_info' => $info,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function show404(array $arguments = [], ?string $layout = '', string $template = 'error/404'): string
    {
        return parent::show404($arguments, $layout, $template);
    }
}