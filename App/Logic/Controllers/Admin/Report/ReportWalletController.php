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
use App\Logic\Models\WalletModel;
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

class ReportWalletController extends AbstractAdminController implements
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

        $this->setLayout($this->main_layout)->setTemplate('view/report/wallet');
        return $this->render([
            'query_builder' => ReportQBUtil::getWalletQB(),
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
            session()->set(SESSION_QUERY_BUILDER_WALLET, $qb);
        } else {
            session()->remove(SESSION_QUERY_BUILDER_WALLET);
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
        session()->remove(SESSION_QUERY_BUILDER_WALLET);
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
        ReportUtil::exportWalletExcel($where, $bindValues);
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
                     * @var WalletModel $walletModel
                     */
                    $walletModel = container()->get(WalletModel::class);

                    [$newWhere, $newBindValues] = $this->getQBFiltered();
                    $where .= $newWhere;
                    $bindValues = array_merge($bindValues, $newBindValues);

                    $where = rtrim(trim($where), 'AND');

                    $data = $walletModel->getWalletInfo($where, $bindValues, $order, $limit, $offset, $cols);
                    //-----
                    $recordsFiltered = $walletModel->getWalletInfoCount($where, $bindValues);
                    $recordsTotal = $walletModel->getWalletInfoCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'w.id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => '(CASE WHEN (u.id IS NOT NULL) THEN CONCAT(u.first_name, " ", u.last_name) ELSE w.username END)',
                        'db_alias' => 'full_name',
                        'dt' => 'user',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() .
                                    '" target="__blank">' .
                                    $d .
                                    '</a>';
                            }
                            return $d;
                        }
                    ],
                    [
                        'db' => 'balance',
                        'db_alias' => 'balance',
                        'dt' => 'balance',
                        'formatter' => function ($d) {
                            return number_format((int)StringUtil::toEnglish($d));
                        }
                    ],
                    [
                        'db' => 'is_available',
                        'db_alias' => 'is_available',
                        'dt' => 'is_available',
                        'formatter' => function ($d) {
                            return $this->setTemplate('partial/admin/parser/active-status')->render([
                                'status' => $d,
                            ]);
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
