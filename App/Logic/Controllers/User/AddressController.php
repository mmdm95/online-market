<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Forms\User\Ajax\Address\AddAddressForm as UserAddAddressForm;
use App\Logic\Forms\User\Ajax\Address\EditAddressForm as UserEditAddressForm;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IAjaxController;
use App\Logic\Models\AddressModel;
use App\Logic\Models\BaseModel;
use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class AddressController extends AbstractUserController implements IAjaxController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function index()
    {
        /**
         * @var AddressModel $addressModel
         */
        $addressModel = container()->get(AddressModel::class);

        $user = $this->getDefaultArguments()['user'];

        $addresses = $addressModel->getUserAddresses([
            'u_addr.id', 'u_addr.full_name', 'u_addr.mobile', 'u_addr.address',
            'u_addr.postal_code', 'c.name AS city_name', 'p.name AS province_name'
        ], 'u_addr.user_id=:id', ['id' => $user['id']]);

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/address/index');
        return $this->render([
            'addresses' => $addresses,
        ]);
    }

    /**
     * @return void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function add(): void
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $user = $this->getDefaultArguments()['user'];

            session()->setFlash('user-address-add-id', $user['id']);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('آدرس با موفقیت اضافه شد.')
                ->handle(UserAddAddressForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @return void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function edit($id): void
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $user = $this->getDefaultArguments()['user'];

            session()->setFlash('user-address-edit-id', $user['id']);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('آدرس با موفقیت ویرایش شد.')
                ->handle(UserEditAddressForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($id): void
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $user = $this->getDefaultArguments()['user'];

            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_USER_ADDRESS, $id, 'user_id=:uId', ['uId' => $user['id']]);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @return void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get($id): void
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var AddressModel $addressModel
             */
            $addressModel = container()->get(AddressModel::class);
            $user = $this->getDefaultArguments()['user'];
            $res = $addressModel->getFirst(['*'], 'id=:id AND user_id=:uId', ['id' => $id, 'uId' => $user['id']]);
            if (count($res)) {
                $resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->data($res);
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('آدرس درخواست شده نامعتبر است!');
            }
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }
}