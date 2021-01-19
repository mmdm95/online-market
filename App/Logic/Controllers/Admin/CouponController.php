<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Coupon\AddCouponForm;
use App\Logic\Forms\Admin\Coupon\EditCouponForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CouponModel;
use App\Logic\Models\OrderModel;
use App\Logic\Utils\Jdf;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;

class CouponController extends AbstractAdminController implements IDatatableController
{
    /**
     * @return string
     * @throws ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/coupon/view');
        return $this->render();
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function add()
    {
        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddCouponForm::class, 'coupon_add');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/coupon/add');
        return $this->render($data);
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function edit($id)
    {
        /**
         * @var CouponModel $couponModel
         */
        $couponModel = container()->get(CouponModel::class);

        $coupon = $couponModel->get(['code'], 'id=:id', ['id' => $id]);

        if (0 == count($coupon)) {
            return $this->show404();
        }

        // store previous title to check for duplicate
        session()->setFlash('coupon-prev-code', $coupon[0]['code']);
        session()->setFlash('coupon-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditCouponForm::class, 'coupon_edit');
        }

        $coupon = $couponModel->getFirst(['*'], 'id=:id', ['id' => $id]);

        $this->setLayout($this->main_layout)->setTemplate('view/coupon/edit');
        return $this->render(array_merge($data, [
            'coupon' => $coupon,
        ]));
    }

    /**
     * @param $id
     * @throws ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function remove($id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_COUPONS, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param array $_
     * @return void
     */
    public function getPaginatedDatatable(...$_): void
    {
        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) {
                    $event->stopPropagation();

                    /**
                     * @var CouponModel $couponModel
                     */
                    $couponModel = container()->get(CouponModel::class);

                    $data = $couponModel->get($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $couponModel->count($where, $bindValues);
                    $recordsTotal = $couponModel->count();

                    // add number of used coupon to all records
                    foreach ($data as $k => &$v) {
                        /**
                         * @var OrderModel $orderModel
                         */
                        $orderModel = container()->get(OrderModel::class);
                        $v['used_count'] = $orderModel->count('coupon_id=:cId AND coupon_code=:cc', [
                            'cId' => $v['id'],
                            'cc' => $v['code'],
                        ]);
                    }

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'code', 'db_alias' => 'code', 'dt' => 'code'],
                    ['db' => 'title', 'db_alias' => 'title', 'dt' => 'title'],
                    [
                        'db' => 'price',
                        'db_alias' => 'price',
                        'dt' => 'price',
                        'formatter' => function ($d) {
                            return number_format(StringUtil::toEnglish($d)) . ' تومان';
                        }
                    ],
                    [
                        'db' => 'min_price',
                        'db_alias' => 'min_price',
                        'dt' => 'min_price',
                        'formatter' => function ($d) {
                            if (!empty($d)) {
                                return number_format(StringUtil::toEnglish($d)) . ' تومان';
                            } else {
                                return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                        }
                    ],
                    [
                        'db' => 'max_price',
                        'db_alias' => 'max_price',
                        'dt' => 'max_price',
                        'formatter' => function ($d) {
                            if (!empty($d)) {
                                return number_format(StringUtil::toEnglish($d));
                            } else {
                                return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                        }
                    ],
                    [
                        'db' => 'start_at',
                        'db_alias' => 'start_at',
                        'dt' => 'start_date',
                        'formatter' => function ($d) {
                            if (!empty($d)) {
                                $date = Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                                if ($d > time()) {
                                    $date = "<span class='text-success'>" . $date . "</span>";
                                }
                            } else {
                                $date = $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                            return $date;
                        }
                    ],
                    [
                        'db' => 'expire_at',
                        'db_alias' => 'expire_at',
                        'dt' => 'end_date',
                        'formatter' => function ($d) {
                            if (!empty($d)) {
                                $date = Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                                if ($d < time()) {
                                    $date = "<span class='text-danger'>" . $date . "</span>";
                                }
                            } else {
                                $date = $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                            return $date;
                        }
                    ],
                    [
                        'db' => 'reusable_after',
                        'db_alias' => 'reusable_after',
                        'dt' => 'reusable_after',
                        'formatter' => function ($d) {
                            if (!empty($d)) {
                                return $d . ' روز';
                            } else {
                                return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                        }
                    ],
                    [
                        'db' => 'use_count',
                        'db_alias' => 'use_count',
                        'dt' => 'used_from_whole',
                        'formatter' => function ($d, $row) {
                            return $this->setTemplate('partial/admin/parser/range_colored')->render([
                                'min' => $row['used_count'],
                                'max' => $row['use_count'],
                            ]);
                        }
                    ],
                    [
                        'db' => 'publish',
                        'db_alias' => 'publish',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/parser/active-status')
                                ->render([
                                    'status' => $d,
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-coupon')
                                ->render([
                                    'row' => $row,
                                ]);
                            return $operations;
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
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}