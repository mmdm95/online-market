<?php

namespace App\Logic\Controllers\Admin\Report;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Interfaces\Report\IReporter;
use App\Logic\Interfaces\Report\IReportExcel;
use App\Logic\Interfaces\Report\IReportFilter;
use App\Logic\Interfaces\Report\IReportPdf;
use App\Logic\Models\UserModel;
use ReflectionException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

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
     * @var string
     */
    protected $report_excel_layout = 'admin-report-excel';

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
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);


        $this->setLayout($this->main_layout)->setTemplate('view/report/wallet');
        return $this->render([

        ]);
    }

    public function filterReport()
    {

    }

    /**
     * Remove stored filter from session
     */
    public function filterClear()
    {
        $resourceHandler = new ResourceHandler();
        session()->remove(SESSION_QUERY_BUILDER_WALLET);
        $resourceHandler->type(RESPONSE_TYPE_SUCCESS);
        response()->json($resourceHandler->getReturnData());
    }

    public function exportExcel()
    {
        // TODO: Implement exportExcel() method.
    }

    public function exportPdfOne()
    {
        // TODO: Implement exportPdfOne() method.
    }

    /**
     * @param array $_
     * @return void
     */
    public function getPaginatedDatatable(...$_): void
    {
        // TODO: Implement getPaginatedDatatable() method.
    }
}