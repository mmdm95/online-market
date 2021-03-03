<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Models\WalletFlowModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class WalletController extends AbstractUserController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function index()
    {
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);

        $user = $this->getDefaultArguments()['user'];

        $wallet_flow = $walletFlowModel->get(
            [
                'deposit_code',
                'deposit_price',
                'deposit_type_title',
                'deposit_at',
            ],
            'username=:username',
            ['username' => $user['username']],
            ['id DESC']
        );;

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/wallet/index');
        return $this->render([
            'wallet_flow' => $wallet_flow,
        ]);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function charge()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/user/wallet/charge');
        return $this->render();
    }
}