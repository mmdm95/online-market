<?php

namespace App\Logic\Controllers\Admin\Report;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Interfaces\Report\IReporter;
use App\Logic\Interfaces\Report\IReportExcel;
use App\Logic\Interfaces\Report\IReportFilter;
use App\Logic\Interfaces\Report\IReportPdf;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\ReportQBUtil;
use App\Logic\Utils\ReportUtil;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;

class ReportWalletDepositController extends AbstractAdminController implements
    IDatatableController,
    IReporter,
    IReportFilter,
    IReportExcel,
    IReportPdf
{
    /**
     * @var string
     */
    protected $report_layout = 'admin-report';

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function report()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/report/wallet-deposit');
        return $this->render([
            'query_builder' => ReportQBUtil::getWalletDepositQB(),
        ]);
    }

    /**
     * Store filter in a session to fetch it when want to show in table
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    public function filterReport()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        $qb = input()->post('filtered_qb')->getValue();
        $qb = json_decode($qb, true);
        if (!empty($qb)) {
            session()->set(SESSION_QUERY_BUILDER_WALLET_DEPOSIT, $qb);
        } else {
            session()->remove(SESSION_QUERY_BUILDER_WALLET_DEPOSIT);
        }

        $resourceHandler->type(RESPONSE_TYPE_SUCCESS);
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * Remove stored filter from session
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    public function filterClear()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();
        session()->remove(SESSION_QUERY_BUILDER_WALLET_DEPOSIT);
        $resourceHandler->type(RESPONSE_TYPE_SUCCESS);
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @return void
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Auth\Interfaces\IDBException
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\File\Interfaces\IFileException
     */
    public function exportExcel()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        [$where, $bindValues] = $this->getQBFiltered();
        ReportUtil::exportWalletDepositExcel($where, $bindValues);
    }

    public function exportPdfOne($id)
    {
        not_implemented_yet();
    }

    /**
     * @param ...$_
     * @return void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    public function getPaginatedDatatable(...$_): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) {
                    $event->stopPropagation();

                    /**
                     * @var WalletFlowModel $walletFlowModel
                     */
                    $walletFlowModel = container()->get(WalletFlowModel::class);

                    [$newWhere, $newBindValues] = $this->getQBFiltered();
                    $where .= $newWhere;
                    $bindValues = array_merge($bindValues, $newBindValues);

                    $cols[] = 'mu.id AS user_id';
                    $cols[] = 'u.id AS payer_id';

                    $where = rtrim(trim($where), 'AND');

                    $data = $walletFlowModel->getWalletFlowInfo($where, $bindValues, $order, $limit, $offset, $cols);
                    //-----
                    $recordsFiltered = $walletFlowModel->getWalletFlowInfoCount($where, $bindValues);
                    $recordsTotal = $walletFlowModel->getWalletFlowInfoCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'wf.id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => '(CASE WHEN (mu.id IS NOT NULL) THEN CONCAT(mu.first_name, " ", mu.last_name) ELSE wf.username END)',
                        'db_alias' => 'full_name',
                        'dt' => 'user',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() .
                                    '">' .
                                    $d .
                                    '</a>';
                            }
                            return $d;
                        }
                    ],
                    [
                        'db' => 'wf.deposit_price',
                        'db_alias' => 'deposit_price',
                        'dt' => 'deposit_price',
                        'formatter' => function ($d) {
                            return number_format(StringUtil::toEnglish($d)) . ' تومان';
                        }
                    ],
                    ['db' => 'wf.deposit_type_title', 'db_alias' => 'deposit_type_title', 'dt' => 'deposit_title'],
                    [
                        'db' => '(CASE WHEN (u.id IS NOT NULL) THEN CONCAT(u.first_name, " ", u.last_name) ELSE wf.username END)',
                        'db_alias' => 'payer_full_name',
                        'dt' => 'deposit_by',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['payer_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['payer_id']])->getRelativeUrl() .
                                    '">' .
                                    $d .
                                    '</a>';
                            }
                            return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'db' => 'wf.deposit_at',
                        'db_alias' => 'deposit_at',
                        'dt' => 'deposit_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
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

    /**
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    private function getQBFiltered(): array
    {
        $auth = auth_admin();

        $where = '';
        $bindValues = [];

        // use query builder sql and params
        $qb = session()->get(SESSION_QUERY_BUILDER_WALLET);
        [$newWhere, $newBind] = ReportQBUtil::getNormalizedQBStatement($qb);

        if (!empty(trim($where))) {
            $where .= ' AND ';
        }
        $where .= $newWhere;
        $bindValues = array_merge($bindValues, $newBind);

        $where = rtrim(trim($where), 'AND');

        return [$where, $bindValues];
    }
}
