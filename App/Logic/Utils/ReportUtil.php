<?php

namespace App\Logic\Utils;

use App\Logic\Models\UserModel;
use Sim\File\Download;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use Sim\Utils\StringUtil;

class ReportUtil
{
    /**
     * @param $where
     * @param $bindValues
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\File\Interfaces\IFileException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function exportUsersExcel($where, $bindValues)
    {
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        // Spreadsheet name
        $name = 'report-users';
        $reportPath = path()->get('upload-report');
        // remove previous excel files
        ReportUtil::removeAllExcelReports($reportPath, $name . '*');
        // Create IO for file
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheetArray = [
            0 => [
                '#',
                'نام کاربری',
                'نام و نام خانوادگی',
                'نقش های کاربر',
                'شماره شبا',
                'وضعیت کاربر',
                'وضعیت ورود',
                'وضعیت فعالیت',
                'فعال شده در تاریخ',
                'ثبت نام در تاریخ',
            ]
        ];
        //-----
        $totalUsers = 0;
        $k = 0;
        $limit = 100;
        $offset = 0;
        // make memory management better in this way by fetching chunk of users each time
        do {
            $users = $userModel->getUsers(['u.*'], $where, $bindValues, $limit, $offset * $limit);
            $totalUsers += count($users);
            foreach ($users as $item) {
                $spreadsheetArray[($k + 1)][] = $k + 1;
                $spreadsheetArray[($k + 1)][] = $item['username'];
                $spreadsheetArray[($k + 1)][] = $item['first_name'] . ' ' . $item['last_name'];
                // get user roles and put it to user object
                $roleNames = $userModel->getUserRoles($item['id'], 'r.show_to_user=:stu', [
                    'stu' => DB_YES,
                ], ['r.description']);
                $roleNames = implode(', ', array_column($roleNames, 'description'));
                $spreadsheetArray[($k + 1)][] = trim($roleNames);
                //
                $spreadsheetArray[($k + 1)][] = $item['shaba_number'];
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_activated']) ? 'فعال' : 'غیر فعال';
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_hidden']) ? 'باز' : 'بسته';
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_login_locked']) ? 'اجازه دارد' : 'منع فعالیت';
                $spreadsheetArray[($k + 1)][] = !empty($item['activated_at'])
                    ? Jdf::jdate('j F Y در ساعت H:i', $item['activated_at'])
                    : '-';
                $spreadsheetArray[($k + 1)][] = Jdf::jdate('j F Y در ساعت H:i', $item['created_at']);
                //
                $k++;
            }
            $offset++;
        } while (count($users));

        $spreadsheetArray[$totalUsers + 1][] = '';
        $spreadsheetArray[$totalUsers + 1][] = 'تعداد کل کاربران';
        $spreadsheetArray[$totalUsers + 1][] = StringUtil::toPersian(number_format(StringUtil::toEnglish($totalUsers)));
        $spreadsheetArray[$totalUsers + 1][] = '';
        $spreadsheetArray[$totalUsers + 1][] = '';
        $spreadsheetArray[$totalUsers + 1][] = '';

        // Add whole array to spreadsheet
        $spreadsheet
            ->getActiveSheet()
            ->setRightToLeft(true)
            ->fromArray($spreadsheetArray);
        // Create writer
        $writer = new WriterXlsx($spreadsheet);
        $writer->save($reportPath . $name . ".xlsx");
        //
        ReportUtil::downloadExcel(
            $reportPath . $name . '.xlsx',
            'گزارش کاربران ' . Jdf::jdate(REPORT_TIME_FORMAT, time())
        );
    }

    /**
     * @param $path
     * @param string|null $rule
     */
    public static function removeAllExcelReports($path, ?string $rule = null)
    {
        if (!is_null($rule)) {
            $mask = rtrim($path, '\\/') . '/' . $rule . '.xlsx';
            array_map('unlink', glob($mask));
        }
        $mask = $path . '*.xlsx';
        array_map('unlink', glob($mask));
    }

    /**
     * @param $path
     * @param $name
     * @throws \Sim\File\Interfaces\IFileException
     */
    public static function downloadExcel($path, $name)
    {
        $download = Download::makeDownloadFromPath($path);
        $download->download($name);
    }
}
