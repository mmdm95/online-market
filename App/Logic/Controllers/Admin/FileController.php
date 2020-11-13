<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\ResourceHandler;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Cookie\Exceptions\CookieException;
use Sim\Cookie\SetCookie;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\File\Utils\MimeTypeUtil;
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
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
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
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function index()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/file-manager/index');

        return $this->render();
    }

    /**
     * @throws CookieException
     */
    public function list()
    {
        // Security options
        $allow_delete = true;
        $hidden_extensions = hidden_extensions();

        $file = $this->getFileFromRequest();

        $result = [];
        if (is_dir($file)) {
            $directory = $file;
            $files = array_diff(scandir($directory), ['.', '..']);
            foreach ($files as $entry) {
                $fileExt = get_extension($entry);
                if ($entry !== basename(__FILE__) && !in_array($fileExt, $hidden_extensions)) {
                    $i = $directory . '/' . $entry;
                    $stat = stat($i);
                    $result[] = [
                        'test' => $directory,
                        'mtime' => $stat['mtime'],
                        'size' => $stat['size'],
                        'ext' => $fileExt,
                        'name' => basename($i),
                        'path' => preg_replace('@^\./@', '', $i),
                        'is_dir' => is_dir($i),
                        'is_deleteable' => $allow_delete && ((!is_dir($i) && is_writable($directory)) ||
                                (is_dir($i) && is_writable($directory) && is_recursively_deletable($i))),
                        'is_readable' => is_readable($i),
                        'is_writable' => is_writable($i),
                        'is_executable' => is_executable($i),
                    ];
                }
            }
        } else {
            $this->data->resetData()->statusCode(412)->errorMessage('Not a Directory');
            \response()->json($this->data->getReturnData());
        }

        $this->data
            ->resetData()
            ->data($result);
        \response()->json($this->data->getReturnData());
    }

    /**
     * @throws CookieException
     */
    public function delete()
    {
        // Security options
        $allow_delete = true;

        $file = $this->getFileFromRequest();

        if ($allow_delete) {
            rmrf($file);
        }

        $this->data->resetData();
        \response()->json($this->data->getReturnData());
    }

    /**
     * @throws CookieException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function rename()
    {
        $file = $this->getFileFromRequest();

        $newName = input()->post('newName', '')->getValue();
        if (empty($newName)) {
            $this->data->resetData()->statusCode(412)->errorMessage('Invalid file name');
            \response()->json($this->data->getReturnData());
        }

        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        $filename = $xss->xss_clean(str_replace(' ', '-', $newName));
        $filename = StringUtil::toPersian($filename);
        $filename = StringUtil::toEnglish($filename);

        if (!file_exists($file)) {
            $this->data->resetData()->statusCode(412)->errorMessage('File does not exists');
            \response()->json($this->data->getReturnData());
        }

        if (strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false) {
            $this->data->resetData()->statusCode(412)->errorMessage('Invalid folder selected');
            \response()->json($this->data->getReturnData());
        }
        // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
        $dir = str_replace('/', '', $filename);
        if (substr($dir, 0, 2) === '..') {
            $this->data->resetData()->statusCode(412)->errorMessage('Invalid folder selected');
            \response()->json($this->data->getReturnData());
        }

        $bName = get_base_name($file);

        if ($bName == $filename) {
            $this->data->resetData();
            \response()->json($this->data->getReturnData());
        }

        $pos = mb_strrpos($file, $bName);
        if ($pos !== false) {
            $newFile = substr_replace($file, $filename, $pos, strlen($file));
        } else {
            $this->data->resetData()->statusCode(412)->errorMessage('Something went wrong!');
            \response()->json($this->data->getReturnData());
        }

        if (file_exists($newFile)) {
            $this->data->resetData()->statusCode(412)->errorMessage('File with this name is currently exists!');
            \response()->json($this->data->getReturnData());
        }

        rename($file, $newFile);
        $this->data->resetData();
        \response()->json($this->data->getReturnData());
    }

    /**
     * @throws CookieException
     */
    public function makeDir()
    {
        // Security options
        $allow_create_folder = true;

        $file = $this->getFileFromRequest();

        if ($allow_create_folder) {
            // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
            $dir = input()->post('name', '')->getValue();
            $dir = str_replace('/', '', $dir);

            if (check_file_uploaded_length($dir)) {
                $this->data->resetData()->statusCode(412)->errorMessage('Invalid name size');
                \response()->json($this->data->getReturnData());
            }
            if (substr($dir, 0, 2) === '..') {
                $this->data->resetData()->statusCode(412)->errorMessage('Invalid folder name');
                \response()->json($this->data->getReturnData());
            }
            chdir($file);
            @mkdir(str_replace(' ', '-', input()->post('name', '')->getValue()));

            $this->data->resetData();
            \response()->json($this->data->getReturnData());
        }
    }

    /**
     * @throws CookieException
     */
    public function moveDir()
    {
        // Security options
        $allow_create_folder = true;

        $file = $this->getFileFromRequest();

        if ($allow_create_folder) {
            $fileArr = json_decode($file);
            foreach ($fileArr as $files) {
                $file = $files;
                $newDir = input()->post('newPath', '')->getValue();

                if (!file_exists($file)) {
                    $this->data->resetData()->statusCode(412)->errorMessage('File does not exists!');
                    \response()->json($this->data->getReturnData());
                }

                if (strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false
                    || strpos(str_replace('\\', '/', $newDir), get_path('upload-root')) === false
                ) {
                    $this->data->resetData()->statusCode(412)->errorMessage('Invalid folder selected');
                    \response()->json($this->data->getReturnData());
                }
                // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
                $dir = str_replace('/', '', $newDir);
                if (substr($dir, 0, 2) === '..') {
                    $this->data->resetData()->statusCode(412)->errorMessage('Invalid folder selected');
                    \response()->json($this->data->getReturnData());
                }

                $bName = get_base_name($file);
                $newFile = $newDir . '/' . $bName;

                if ($file == $newFile) {
                    $this->data->resetData()->statusCode(412)->errorMessage('Invalid folder name');
                    \response()->json($this->data->getReturnData());
                }

                rename($file, $newFile);
            }

            $this->data->resetData();
            \response()->json($this->data->getReturnData());
        }
    }

    /**
     * @throws CookieException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function upload()
    {
        // Security options
        $allow_upload = true;
        $disallowed_extensions = disallowed_extensions();

        $file = $this->getFileFromRequest();

        if ($allow_upload) {
            foreach ($disallowed_extensions as $ext) {
                ;
                if (preg_match(sprintf('/\.%s$/', preg_quote($ext)), input()->file('file_data')->getName())) {
                    $this->data->resetData()->statusCode(412)->errorMessage('Files of this type are not allowed');
                    \response()->json($this->data->getReturnData());
                }
            }

            $xss = container()->get(AntiXSS::class);
            $filename = $xss->xss_clean(str_replace(' ', '-', input()->file('file_data')->getName()));
            $filename = str_replace('@', '', $filename);

            $filename = StringUtil::toPersian($filename);
            $filename = StringUtil::toEnglish($filename);

            if (check_file_uploaded_length($filename)) {
                $this->data->resetData()->statusCode(412)->errorMessage('Invalid name size');
                \response()->json($this->data->getReturnData());
            }

            $res = move_uploaded_file(input()->file('file_data')->getTmpName(), $file . '/' . $filename);

            if ($res) {
                $this->data->resetData();
                \response()->json($this->data->getReturnData());
            } else {
                $this->data->resetData()->statusCode(412)->errorMessage('Can not upload!');
                \response()->json($this->data->getReturnData());
            }
        }
    }

    /**
     * @param $name
     */
    public function download($name)
    {
        $file = str_replace('@', '.', $name);
        if (file_exists($file)) {
            $filename = basename($file);
            header('Content-Type: ' . MimeTypeUtil::getMimeTypeFromFilename($file));
            header('Content-Length: ' . filesize($file));
            header(sprintf('Content-Disposition: attachment; filename=%s',
                strpos('MSIE', $_SERVER['HTTP_REFERER']) ? rawurlencode($filename) : "\"$filename\""));
            ob_flush();
            readfile(get_base_url() . $file);
            exit;
        }
    }

    /**
     * @throws CookieException
     */
    public function foldersTree()
    {
        $file = $this->getFileFromRequest();

        if (!$file) {
            $this->data->resetData()->statusCode(412)->errorMessage('Not a Directory');
            \response()->json($this->data->getReturnData());
        }

        if (strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false) {
            $this->data->resetData()->statusCode(412)->errorMessage('Not a Directory');
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
            $this->data->resetData()->statusCode(412)->errorMessage('Not a Directory');
            \response()->json($this->data->getReturnData());
        }

        $this->data->resetData()->data($result);
        \response()->json($this->data->getReturnData());
    }

    /**
     * @return string
     * @throws CookieException
     */
    private function getFileFromRequest(): string
    {
        // Disable error report for undefined superglobals
        error_reporting(error_reporting() & ~E_NOTICE);
        // must be in UTF-8 or `basename` doesn't work
        setlocale(LC_ALL, 'en_US.UTF-8');
        $tmp_dir = get_path('upload-root');

        $tmp_dir = str_replace('/', DIRECTORY_SEPARATOR, $tmp_dir);
        $tmp = get_absolute_path($tmp_dir . '/' . input('file', '', 'post', 'get'));

        if ($tmp === false) {
            $this->data->resetData()->statusCode(404)->errorMessage('File or Directory Not Found');
            \response()->json($this->data->getReturnData());
        }
        if (substr($tmp, 0, strlen($tmp_dir)) !== $tmp_dir) {
            $this->data->resetData()->statusCode(403)->errorMessage('Forbidden');
            \response()->json($this->data->getReturnData());
        }
        if (strpos(input('file', '', 'post', 'get'), DIRECTORY_SEPARATOR) === 0) {
            $this->data->resetData()->statusCode(403)->errorMessage('Forbidden');
            \response()->json($this->data->getReturnData());
        }
        if (!is_null(cookie()->get('_sfm_xsrf'))) {
            cookie()->set(new SetCookie('_sfm_xsrf', bin2hex(openssl_random_pseudo_bytes(16))));
        }
        if ($_POST) {
            if (!is_null(input()->post('xsrf')->getValue()) || cookie()->get('_sfm_xsrf') ?: '' !== input()->post('xsrf')->getValue()) {
                $this->data->resetData()->statusCode(403)->errorMessage('XSRF Failure');
                \response()->json($this->data->getReturnData());
            }
        }

        $file = input('file', get_path('upload-root'), 'post', 'get');
        if (strpos(str_replace('\\', '/', $file), get_path('upload-root')) === false) {
            $file = get_path('upload-root');
        }
        $file = rtrim(str_replace(['//', '\\'], '/', $file));

        return $file;
    }
}