<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Ajax\Address\Company\AddAddressForm as AjaxAddAddressForm;
use App\Logic\Forms\Ajax\Address\Company\EditAddressForm as AjaxEditAddressForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\AddressModel;
use App\Logic\Models\BaseModel;
use App\Logic\Utils\LogUtil;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Event\Interfaces\IEvent;

class AddressCompanyController extends AbstractAdminController implements IDatatableController
{
    /**
     * @param $user_id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function add($user_id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $currId = $auth->getCurrentUser()['id'] ?? null;
        if (empty($currId) || $user_id != $currId) {
            if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_CREATE)) {
                show_403();
            }
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            session()->setFlash('addr-add-user-id', $user_id);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('آدرس با موفقیت اضافه شد.')
                ->handle(AjaxAddAddressForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $user_id
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function edit($user_id, $id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $currId = $auth->getCurrentUser()['id'] ?? null;
        if (empty($currId) || $user_id != $currId) {
            if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_UPDATE)) {
                show_403();
            }
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            session()->setFlash('addr-edit-user-id', $user_id);
            session()->setFlash('addr-edit-id', $id);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('آدرس با موفقیت ویرایش شد.')
                ->handle(AjaxEditAddressForm::class);
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
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        /**
         * @var AddressModel $addressModel
         */
        $addressModel = container()->get(AddressModel::class);
        $user_id = $addressModel->getFirst(['user_id'], 'id=:id', ['id' => $id])['user_id'] ?? null;
        $currId = $auth->getCurrentUser()['id'] ?? null;
        if (empty($currId) || $user_id != $currId) {
            if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_DELETE)) {
                show_403();
            }
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_USER_COMPANY_ADDRESS, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $user_id
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get($user_id, $id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $currId = $auth->getCurrentUser()['id'] ?? null;
        if (empty($currId) || $user_id != $currId) {
            if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_READ)) {
                show_403();
            }
        }

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
            $res = $addressModel->getFirst(['*'], 'user_id=:uId AND id=:id', ['uId' => $user_id, 'id' => $id]);
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

    /**
     */
    /**
     * {@inheritdoc}
     * @param array $_
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getPaginatedDatatable(...$_): void
    {
        [$userId] = $_;

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $currId = $auth->getCurrentUser()['id'] ?? null;
        if (empty($currId) || $userId != $currId) {
            if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_READ)) {
                show_403();
            }
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($userId) {
                    $event->stopPropagation();

                    /**
                     * @var AddressModel $addressModel
                     */
                    $addressModel = container()->get(AddressModel::class);

                    if (!empty($where)) {
                        $where .= ' AND (uc_addr.user_id=:uId)';
                    } else {
                        $where = 'uc_addr.user_id=:uId';
                    }
                    $bindValues = array_merge($bindValues, [
                        'uId' => $userId,
                    ]);

                    $data = $addressModel->getUserAddresses($cols, $where, $bindValues, $limit, $offset, $order);
                    //-----
                    $recordsFiltered = $addressModel->getUserAddressesCount($where, $bindValues);
                    $recordsTotal = $addressModel->getUserAddressesCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'uc_addr.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'uc_addr.company_name', 'db_alias' => 'company_name', 'dt' => 'company_name'],
                    ['db' => 'uc_addr.economic_code', 'db_alias' => 'economic_code', 'dt' => 'eco_code'],
                    ['db' => 'uc_addr.economic_national_id', 'db_alias' => 'economic_national_id', 'dt' => 'eco_nid'],
                    ['db' => 'uc_addr.registration_number', 'db_alias' => 'registration_number', 'dt' => 'reg_number'],
                    ['db' => 'uc_addr.landline_tel', 'db_alias' => 'landline_tel', 'dt' => 'tel'],
                    ['db' => 'p.name', 'db_alias' => 'province_name', 'dt' => 'province'],
                    ['db' => 'c.name', 'db_alias' => 'city_name', 'dt' => 'city'],
                    ['db' => 'uc_addr.postal_code', 'db_alias' => 'postal_code', 'dt' => 'postal_code'],
                    ['db' => 'uc_addr.mobile', 'db_alias' => 'mobile', 'dt' => 'mobile'],
                    ['db' => 'uc_addr.address', 'db_alias' => 'address', 'dt' => 'address'],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $options = $this
                                ->setTemplate('partial/admin/datatable/actions-user-address-company')
                                ->render([
                                    'row' => $row,
                                ]);

                            return $options;
                        }
                    ],
                ];

                $response = DatatableHandler::handle($_POST, $columns);
            } else {
                response()->httpCode(403);
                $response = [
                    'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
                ];
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}
