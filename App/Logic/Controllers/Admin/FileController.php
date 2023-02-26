<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Middlewares\Logic\NonePublicFolderAccessMiddleware;
use App\Logic\Middlewares\Logic\PublicFolderModifyMiddleware;
use App\Logic\Models\UserModel;
use App\Logic\Utils\LogUtil;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\File\Download;
use Sim\File\FileSystem;
use Sim\File\Interfaces\IFileException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class FileController extends AbstractAdminController
{
    /**
     * @var ResourceHandler
     */
    private $data;

    /**
     * FileController constructor.
     * @throws ConfigNotRegisteredException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        parent::__construct();

        $this->data = new ResourceHandler();
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
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
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/file-manager/index');

        return $this->render();
    }

    /**
     * Get a list of specific directory
     *
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function list()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        // Security options
        $allow_delete = true;
        $hidden_extensions = hidden_extensions();

        $file = $this->getFileFromRequest();

        $this->checkListAccess($file);

        /**
         * @var DBAuth $auth
         */
        $auth = \container()->get('auth_home');

        /**
         * @var UserModel $userModel
         */
        $userModel = \container()->get(UserModel::class);
        $username = $userModel->getUsernameFromID($auth->getCurrentUser()['id'] ?? null);

        $result = [];
        if (is_dir($file)) {
            $directory = $file;
            $files = array_diff(scandir($directory), ['.', '..']);

            // cancel error showing
            $this->doNotShowAnyError();

            foreach ($files as $entry) {
//                if(
//                    $filename === '' &&
//                    PUBLIC_ACCESS_DIR !== $filename &&
//                    $filename !== $username
//                ) {
//                    continue;
//                }

                $fileExt = get_extension($entry);
                if ($entry !== basename(__FILE__) && !in_array($fileExt, $hidden_extensions)) {
                    $i = $directory . '/' . urldecode($entry);
                    $stat = filemtime($i);
                    $isUTF8 = preg_match('//u', basename($i));
                    if ($isUTF8) {
                        $result[] = [
                            'mtime' => is_int($stat) ? $stat : '',
                            'size' => FileSystem::getFileSize($i),
                            'ext' => $fileExt,
                            'name' => basename($i),
                            'path' => ltrim(str_replace(get_path('upload-root'), '', preg_replace('@^\./@', '', $i)), '\\/'),
                            'is_dir' => is_dir($i),
                            'is_deleteable' => $allow_delete && ((!is_dir($i) && is_writable($directory)) ||
                                    (is_dir($i) && is_writable($directory) && is_recursively_deletable($i))),
                            'is_readable' => is_readable($i),
                            'is_writable' => is_writable($i),
                            'is_executable' => is_executable($i),
                        ];
                    }
                }
            }
        } else {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه‌ای انتخاب نشده');
            \response()->json($this->data->getReturnData());
        }
        // make error showing to normal if it's in development mode
        $this->showAllErrorAgain();

        $this->data
            ->resetData()
            ->data($result);
        \response()->json($this->data->getReturnData());
    }

    /**
     * Delete a file or directory
     *
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function delete()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        // Security options
        $allow_delete = true;

        $file = $this->getFileFromRequest();

        $this->checkAccess($file);

        try {
            if ($allow_delete && is_recursively_deletable($file)) {
                rmrf($file);
            } else {
                $this->data->resetData()->statusCode(412)->errorMessage('امکان حذف وجود ندارد!');
                \response()->json($this->data->getReturnData());
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $this->data->resetData()->statusCode(412)->errorMessage($e->getMessage());
            \response()->json($this->data->getReturnData());
        }

        $this->data->resetData();
        \response()->json($this->data->getReturnData());
    }

    /**
     * Delete a file or directory
     *
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function deleteAll()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        // Security options
        $allow_delete = true;

        $file = $this->getFileFromRequest(true);

        $this->checkAccess($file);

        try {
            if (!is_array($file)) {
                $this->data->resetData()->statusCode(412)->errorMessage('ورودی نامعتبر است!')->data([$file]);
                \response()->json($this->data->getReturnData());
            }
            if ($allow_delete) {
                foreach ($file as $f) {
                    if (!is_dir($f)) {
                        unlink($f);
                    }
                }
            } else {
                $this->data->resetData()->statusCode(412)->errorMessage('امکان حذف وجود ندارد!');
                \response()->json($this->data->getReturnData());
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $this->data->resetData()->statusCode(412)->errorMessage($e->getMessage());
            \response()->json($this->data->getReturnData());
        }

        $this->data->resetData();
        \response()->json($this->data->getReturnData());
    }

    /**
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function rename()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        $file = $this->getFileFromRequest();

        $newName = input()->post('newName', '');
        if (is_array($newName) || empty($newName)) {
            $this->data->resetData()->statusCode(412)->errorMessage('نام فایل نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }
        $newName = $newName->getValue();

        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        $filename = $xss->xss_clean(str_replace(' ', '-', $newName));
        $filename = StringUtil::toPersian($filename);
        $filename = StringUtil::toEnglish($filename);
        $filename = rtrim($filename, '\\/');

        $this->checkAccess($file);

        if (!file_exists($file)) {
            $this->data->resetData()->statusCode(412)->errorMessage('فایل وجود ندارد.');
            \response()->json($this->data->getReturnData());
        }

        if (mb_strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false) {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }
        // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
        $dir = str_replace('/', '', $filename);
        if (mb_substr($dir, 0, 2) === '..') {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }

        $file = rtrim($file, '\\/');
        $bName = get_base_name($file);

        if ($bName == $filename) {
            $this->data->resetData();
            \response()->json($this->data->getReturnData());
        }

        $pos = mb_strrpos($file . '/', $bName);
        if ($pos !== false) {
            $newFile = StringUtil::mbSubstrReplace($file, $filename, $pos, mb_strlen($file));
        } else {
            $this->data->resetData()->statusCode(412)->errorMessage('مشکلی پیش آمده!');
            \response()->json($this->data->getReturnData());
        }

        if (file_exists($newFile)) {
            $this->data->resetData()->statusCode(412)->errorMessage('فایلی با این نام وجود دارد.');
            \response()->json($this->data->getReturnData());
        }

        rename($file, $newFile);
        $this->data->resetData();
        \response()->json($this->data->getReturnData());
    }

    /**
     * Create a directory
     *
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function makeDir()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        // Security options
        $allow_create_folder = true;

        $file = $this->getFileFromRequest();

        $this->checkAccess($file);

        if ($allow_create_folder) {
            // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
            $dir = input()->post('name', '');
            if (is_array($dir) || empty($dir)) {
                $this->data->resetData()->statusCode(412)->errorMessage('نام پوشه نامعتبر است.');
                \response()->json($this->data->getReturnData());
            }
            $dir = $dir->getValue();
            $dir = str_replace('/', '', $dir);

            if (check_file_uploaded_length($dir)) {
                $this->data->resetData()->statusCode(412)->errorMessage('تعداد کاراکترهای نام پوشه از حد مجاز رد شده است. تعداد کاراکتر مجاز ۲۰۰ کاراکتر می‌باشد.');
                \response()->json($this->data->getReturnData());
            }
            if (mb_substr($dir, 0, 2) === '..') {
                $this->data->resetData()->statusCode(412)->errorMessage('نام پوشه نامعتبر است.');
                \response()->json($this->data->getReturnData());
            }
            chdir($file);
            @mkdir(str_replace(' ', '-', input()->post('name', '')->getValue()));

            $this->data->resetData();
            \response()->json($this->data->getReturnData());
        }
    }

    /**
     * Move items to another directory
     *
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function moveDir()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        // Security options
        $allow_create_folder = true;

        $file = $this->getFileFromRequest(true);

        if ($allow_create_folder) {
            $fileArr = $file ?: [];
            $counter = 0;
            foreach ($fileArr as $files) {
                if (is_dir($files)) {
                    // do nothing for now
                } else {
                    $newDir = input()->post('newPath', '');
                    if (is_array($newDir) || empty($newDir)) {
                        $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
                        \response()->json($this->data->getReturnData());
                    }
                    $newDir = $newDir->getValue();
                    $newDir = urldecode($newDir);
                    if ($this->moveSingleFile($files, $newDir)) {
                        $counter++;
                    }
                }
            }

            if (count($fileArr) != $counter) {
                $this->data->resetData()->statusCode(99);
                \response()->json($this->data->getReturnData());
            } else {
                $this->data->resetData();
                \response()->json($this->data->getReturnData());
            }
        }
    }

    /**
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function upload()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        // Security options
        $allow_upload = true;
        $disallowed_extensions = disallowed_extensions();

        $file = $this->getFileFromRequest();

        $this->checkAccess($file);

        if ($allow_upload) {
            foreach ($disallowed_extensions as $ext) {
                if (preg_match(sprintf('/\.%s$/', preg_quote($ext)), input()->file('file_data')->getName())) {
                    $this->data->resetData()->statusCode(412)->errorMessage('فایل با پسوند وارد شده، اجازه آپلود ندارد.');
                    \response()->json($this->data->getReturnData());
                }
            }

            $xss = container()->get(AntiXSS::class);
            $filename = $xss->xss_clean(str_replace(' ', '-', input()->file('file_data')->getFilename()));
            $filename = str_replace('@', '', $filename);

            $filename = StringUtil::toPersian($filename);
            $filename = StringUtil::toEnglish($filename);

            if (check_file_uploaded_length($filename)) {
                $this->data->resetData()->statusCode(412)->errorMessage('تعداد کاراکترهای نام پوشه از حد مجاز رد شده است. تعداد کاراکتر مجاز ۲۰۰ کاراکتر می‌باشد.');
                \response()->json($this->data->getReturnData());
            }

            $res = move_uploaded_file(input()->file('file_data')->getTmpName(), $file . '/' . $filename);

            if ($res) {
                $this->data->resetData();
                \response()->json($this->data->getReturnData());
            } else {
                $this->data->resetData()->statusCode(412)->errorMessage('آپلود با خطا مواجه شد!');
                \response()->json($this->data->getReturnData());
            }
        }
    }

    /**
     * @param $file
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function download($file)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $path = urldecode(get_path('upload-root', $file, false));

        $this->checkAccess($path);

        try {
            Download::makeDownloadFromPath($path)->download(null);
        } catch (IFileException|\Exception $e) {
            // do nothing
        }
    }

    /**
     * Get folders tree
     *
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function foldersTree()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FILEMANAGER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $file = $this->getFileFromRequest();

        if (!$file) {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }

        if (mb_strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false) {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }

        $result = [];
        if (is_dir($file)) {
            $directory = $file;
            $files = array_diff(scandir($directory), ['.', '..']);
            foreach ($files as $entry) {
                $i = $directory . '/' . $entry;

                if ($entry !== basename(__FILE__) && is_dir($i)) {
                    $result[] = [
                        'name' => basename($i),
                        'path' => preg_replace('@^\./@', '', $i),
                    ];
                }
            }
        } else {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }

        $this->data->resetData()->data($result);
        \response()->json($this->data->getReturnData());
    }

    /**
     * @param string $filename
     * @return string
     */
    public function showImage(string $filename)
    {
        $filename = urldecode($filename);
        $path = get_path('upload-root', trim($filename, '\\/'), false);

        if (FileSystem::fileExists($path)) {
            $file = FileSystem::getFromFile($path);
            $type = FileSystem::getFileMimeType($path, 'application/octet-stream');
        } else {
            $file = FileSystem::getFromFile(PLACEHOLDER_IMAGE);
            $type = FileSystem::getFileMimeType(PLACEHOLDER_IMAGE, 'application/octet-stream');
        }

        \response()->header('Content-Type: ' . $type);
        return $file;
    }

    /**
     * @param $file
     * @param $newDir
     * @return bool
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    private function moveSingleFile($file, $newDir)
    {
        $this->checkAccess($file);

        if (!file_exists($file)) {
            $this->data->resetData()->statusCode(412)->errorMessage('فایل وجود ندارد!');
        }

        if (mb_strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false
            || mb_strpos(str_replace('\\', '/', $newDir), get_path('upload-root')) === false
        ) {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }
        // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
        $dir = str_replace('/', '', $newDir);
        if (mb_substr($dir, 0, 2) === '..') {
            $this->data->resetData()->statusCode(412)->errorMessage('پوشه انتخاب شده نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }

        $bName = get_base_name($file);
        $newFile = $newDir . '/' . $bName;

        if ($file == $newFile) {
            $this->data->resetData()->statusCode(412)->errorMessage('نام پوشه نامعتبر است.');
            \response()->json($this->data->getReturnData());
        }

        if (!file_exists($newFile)) {
            return rename($file, $newFile);
        }
        return false;
    }

    /**
     * @param bool $is_array
     * @return string|array
     */
    private function getFileFromRequest(bool $is_array = false)
    {
        // Disable error report for undefined superglobals
        error_reporting(error_reporting() & ~E_NOTICE);
        // must be in UTF-8 or `basename` doesn't work
        setlocale(LC_ALL, 'en_US.UTF-8');
        $tmp_dir = get_path('upload-root');
        $tmp_dir = str_replace('/', DIRECTORY_SEPARATOR, $tmp_dir);


        if (is_null(cookie()->get('_sfm_xsrf'))) {
            \setcookie('_sfm_xsrf', bin2hex(openssl_random_pseudo_bytes(16)), 0, "/");
        }

        if (!empty($_POST)) {
            if (is_null(input()->post('xsrf')->getValue()) ||
                (
                    !is_null(input()->post('xsrf')->getValue()) &&
                    (
                        is_null(input()->post('xsrf')->getValue()) ||
                        (\cookie()->get('_sfm_xsrf') ?: '') !== input()->post('xsrf')->getValue()
                    )
                )
            ) {
                $this->data->resetData()->statusCode(403)->errorMessage('مشکل در امنیت مدیریت فایل، لطفا دوباره تلاش کنید.');
                \response()->json($this->data->getReturnData());
            }
        }

        if ($is_array) {
            $tFile = input()->all()['file'] ?? '';
            $dFiles = json_decode($tFile, true);
            if ($dFiles) {
                foreach ($dFiles as &$f) {
                    if (is_string($f)) {
                        $tmp = get_absolute_path($tmp_dir . '/' . $f);
                        if ($tmp === false) {
                            $this->data->resetData()->statusCode(404)->errorMessage('فایل یا پوشه انتخاب شده، پیدا نشد.');
                            \response()->json($this->data->getReturnData());
                        }
                        if (mb_substr($tmp, 0, mb_strlen($tmp_dir)) !== $tmp_dir) {
                            $this->data->resetData()->statusCode(403)->errorMessage('دسترسی غیر مجاز!');
                            \response()->json($this->data->getReturnData());
                        }
                        //-----
                        if (mb_strpos(str_replace('\\', '/', $f), get_path('upload-root')) === false) {
                            $f = get_path('upload-root') . '/' . trim($f, '\\/');
                        }
                        $f = rtrim(str_replace(['//', '\\'], '/', $f));
                        $f = urldecode($f);
                    }
                }

                $file = $dFiles;
            } else {
                $this->data->resetData()->statusCode(404)->errorMessage('فایل یا پوشه انتخاب شده، پیدا نشد.');
                \response()->json($this->data->getReturnData());
            }
        } else {
            $tmp = get_absolute_path($tmp_dir . '/' . input()->all()['file'] ?? '');
            if ($tmp === false) {
                $this->data->resetData()->statusCode(404)->errorMessage('فایل یا پوشه انتخاب شده، پیدا نشد.');
                \response()->json($this->data->getReturnData());
            }
            if (mb_substr($tmp, 0, mb_strlen($tmp_dir)) !== $tmp_dir) {
                $this->data->resetData()->statusCode(403)->errorMessage('دسترسی غیر مجاز!');
                \response()->json($this->data->getReturnData());
            }
            //-----
            $file = input()->all()['file'] ?? get_path('upload-root');
            if (strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false) {
                $file = get_path('upload-root', trim($file, '\\/'));
            }
            $file = rtrim(str_replace(['//', '\\'], '/', $file));
            $file = urldecode($file);
        }

        // do not worry it'll never be empty
        return $file;
    }

    /**
     * @param $filename
     */
    private function checkListAccess($filename)
    {
        $middleware = new NonePublicFolderAccessMiddleware();
        $filename = str_replace(get_path('upload-root'), '', $filename);
        $res = $middleware->handle($filename);
        if (!$res) {
            if (\request()->isAjax()) {
                $this->data->resetData()->statusCode(403)->errorMessage('اجازه دسترسی داده نشد!');
                \response()->json($this->data->getReturnData());
            } else {
                echo 'دسترسی غیرمجاز';
                exit(0);
            }
        }
    }

    /**
     * @param $filename
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    private function checkAccess($filename)
    {
        if (is_array($filename)) {
            foreach ($filename as $f) {
                $this->checkAccess($f);
            }
        } else {
            if (false === mb_strpos($filename, get_path('upload-root'))) {
                if (\request()->isAjax()) {
                    $this->data->resetData()->statusCode(403)->errorMessage('اجازه دسترسی داده نشد!');
                    \response()->json($this->data->getReturnData());
                } else {
                    echo 'دسترسی غیرمجاز';
                    exit(0);
                }
            }

            $filename = str_replace(get_path('upload-root'), '', $filename);
            $middleware = new PublicFolderModifyMiddleware();
            $res = $middleware->handle($filename);
            if (!$res) {
                if (\request()->isAjax()) {
                    $this->data->resetData()->statusCode(403)->errorMessage('اجازه دسترسی داده نشد!');
                    \response()->json($this->data->getReturnData());
                } else {
                    echo 'دسترسی غیرمجاز';
                    exit(0);
                }
            }
        }
    }

    /**
     * @return void
     */
    private function doNotShowAnyError()
    {
        error_reporting(0);
        @ini_set("display_errors", 0);
    }

    /**
     * @return void
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    private function showAllErrorAgain()
    {
        $mode = $this->config->get('main.mode') ?? MODE_PRODUCTION;
        if (MODE_DEVELOPMENT == $mode) {
            error_reporting(E_ALL);
            @ini_set("display_errors", 1);
        } else {
            error_reporting(0);
            @ini_set("display_errors", 0);
        }
    }
}
