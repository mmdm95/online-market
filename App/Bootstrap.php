<?php

namespace App;

use App\Logic\Adapters\CsrfVerifier as CsrfVerifier;
use App\Logic\Container as ContainerDefinition;
use App\Logic\Route as RouteDefinition;
use App\Logic\Event as EventDefinition;
use App\Logic\Helper as HelperDefinition;

use Sim\Command\ApplicationKeyGeneratorCommand;
use Sim\Command\CleanWebpackFiles;
use Sim\Command\MoveWebpackFiles;
use Sim\ConfigManager\ConfigManager;
use Sim\ConfigManager\ConfigManagerSingleton;

use Sim\Event\Emitter;

use Sim\Handler\ErrorHandler;

use Sim\Interfaces\ConfigManager\IConfig;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Interfaces\Loader\ILoader;
use Sim\Interfaces\PathManager\IPath;

use Sim\Loader\Loader;

use Sim\Loader\LoaderSingleton;
use Sim\Logger\Handler\File\FileHandler;
use Sim\Logger\ILogger;
use Sim\Logger\Logger;

use App\Logic\Adapters\SessionTokenProvider as TokenProvider;
use Pecee\SimpleRouter\SimpleRouter as Router;

use DI\Container as Container;

use Dotenv\Dotenv;

use Symfony\Component\Console\Application as ConsoleApplication;

class Bootstrap
{
    const REQUIRED_PHP_VERSION = '7.2';

    /**
     * @var ConsoleApplication $consoleApp
     */
    protected $consoleApp = null;

    /**
     * @var ILoader $loader
     */
    protected $loader;

    /**
     * @var IPath $path
     */
    protected $path;

    /**
     * @var IConfig $config
     */
    protected $config;

    /**
     * @var bool $route_needed
     */
    protected $route_needed = true;

    /**
     * @var array $commands
     */
    protected $commands = [
        // base needed commands
        CleanWebpackFiles::class,
        MoveWebpackFiles::class,
        ApplicationKeyGeneratorCommand::class,

        // my custom commands
        // ...
    ];

    /**
     * @var array $vendor_helpers_path
     */
    protected $vendor_helpers_path = [
        __DIR__ . '/../vendor/simplicity-framework/Functions/generalHelper.php',
        __DIR__ . '/../vendor/simplicity-framework/Functions/appHelper.php',
        __DIR__ . '/../vendor/simplicity-framework/Functions/pathHelper.php',
        __DIR__ . '/../vendor/simplicity-framework/Functions/loadHelper.php',
        __DIR__ . '/../vendor/simplicity-framework/Functions/routerHelper.php',
    ];

    /**
     * Bootstrap constructor.
     * @param bool $route_needed
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Pecee\Http\Middleware\Exceptions\TokenMismatchException
     * @throws \Pecee\Http\Security\Exceptions\SecurityException
     * @throws \Pecee\SimpleRouter\Exceptions\HttpException
     * @throws \Pecee\SimpleRouter\Exceptions\NotFoundHttpException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     */
    public function __construct(bool $route_needed = true)
    {
        $this->route_needed = $route_needed;
        //-----
        date_default_timezone_set('Asia/Tehran');
        //-----
        $this->defineConstants();
        $this->init();
    }

    /**
     * @return ConsoleApplication
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getConsoleApplication(): ConsoleApplication
    {
        if(is_null($this->consoleApp)) {
            $this->consoleApp = new ConsoleApplication();
        }

        foreach ($this->commands as $command) {
            $this->consoleApp->add(\container()->make($command));
        }

        return $this->consoleApp;
    }

    /**
     * Define framework constants
     */
    protected function defineConstants()
    {
        //******** Root Directory *********
        defined('BASE_ROOT') or define('BASE_ROOT', str_replace('\\', '/', dirname(__DIR__) . '/'));

        //********* Error Handler *********
        defined("E_FATAL") or define("E_FATAL", E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

        //************* Modes *************
        defined("MODE_DEVELOPMENT") or define("MODE_DEVELOPMENT", 0x1);
        defined("MODE_PRODUCTION") or define("MODE_PRODUCTION", 0x2);
    }

    /**
     * Initializer
     *
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Pecee\Http\Middleware\Exceptions\TokenMismatchException
     * @throws \Pecee\Http\Security\Exceptions\SecurityException
     * @throws \Pecee\SimpleRouter\Exceptions\HttpException
     * @throws \Pecee\SimpleRouter\Exceptions\NotFoundHttpException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     */
    protected function init()
    {
        // Load .env variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        // Validate .env variables
        $dotenv->required('APP_MAIN_KEY')->notEmpty();
        $dotenv->required('APP_ASSURED_KEY')->notEmpty();
        $dotenv->required('DB_HOST')->notEmpty();
        $dotenv->required('DB_NAME')->notEmpty();
        $dotenv->required('DB_USERNAME')->notEmpty();
        $dotenv->required('DB_PASSWORD');
        $dotenv->required('DB_PORT')->isInteger();

        // Needed helpers
        $this->loadHelper();

        // Check for version compatibility
        if (!is_version_compatible(self::REQUIRED_PHP_VERSION)) {
            die('Your php version is not compatible with requirements of using this framework! Please upgrade your php to version ' . self::REQUIRED_PHP_VERSION . ' or higher');
        }

        // Start session and other things that need start at first
        if (PHP_SESSION_NONE == session_status() && !headers_sent()) {
            session_start();
        }

        // Get needed objects from Container class
        $this->loader = \loader();
        $this->path = \path();
        $this->config = \config();

        // set env variable as config
        $this->config->setAsConfig('env', [
            'APP_MAIN_KEY' => $_ENV['APP_MAIN_KEY'],
            'APP_ASSURED_KEY' => $_ENV['APP_ASSURED_KEY'],
            'DB_HOST' => $_ENV['DB_HOST'],
            'DB_NAME' => $_ENV['DB_NAME'],
            'DB_USERNAME' => $_ENV['DB_USERNAME'],
            'DB_PASSWORD' => $_ENV['DB_PASSWORD'],
            'DB_PORT' => $_ENV['DB_PORT'],
        ]);

        // Load constants first
        LoaderSingleton::getInstance()->load(__DIR__ . '/../Config/constants.php', null, ILoader::TYPE_REQUIRE_ONCE);

        // Call needed functionality
        $this->defineConfig();
        $this->definePath();

        // check maintenance mode here
        $this->checkMaintenanceMode();

        // only for none route (without CLI) cases
        if ($this->route_needed) {
            $this->defineEvents();
            $this->defineContainer();
            //
            $this->customErrorHandler();
            $this->defineRoute();
        }
    }

    /**
     * Define all paths
     * @throws IFileNotExistsException
     */
    protected function definePath()
    {
        // Then load path config
        $paths = ConfigManagerSingleton::getInstance()->getDirectly(__DIR__ . '/../Config/path.php');

        // Add all of them to path collector if $path is an array
        if (is_array($paths)) {
            foreach ($paths as $alias => $path) {
                if (is_string($path) && 'default_config' != $path) {
                    try {
                        $this->path->set((string)$alias, (string)$path);
                    } catch (IFileNotExistsException $e) {
                        // do nothing for now
                    }
                }
            }
        }
    }

    /**
     * Load helpers that are not class but functions
     *
     * @throws IFileNotExistsException
     */
    protected function loadHelper()
    {
        foreach ($this->vendor_helpers_path as $vendor_helper) {
            LoaderSingleton::getInstance()->load_require_once($vendor_helper);
        }

        /**
         * @var HelperDefinition $helper
         */
        $helper = new HelperDefinition();
        $helpers = $helper->init();
        foreach ($helpers as $h) {
            if (is_string($h)) {
                LoaderSingleton::getInstance()->load_require_once($h);
            }
        }
    }

    /**
     * Define config alias and directories
     */
    protected function defineConfig()
    {
        // Get config paths
        $configs = $this->config->getDirectly(__DIR__ . '/../Config/path.php')['default_config'] ?? [];

        // Add all of them to config collector if $configs is an array
        if (is_array($configs)) {
            foreach ($configs as $alias => $path) {
                $this->config->set($alias, $path);
            }
        }
    }

    /**
     * Custom error handler initialization
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function customErrorHandler()
    {
        /**
         * @var ErrorHandler $errorHandler
         */
        $errorHandler = \container()->get(ErrorHandler::class);
        $errorHandler->init();
    }

    /**
     * Events
     */
    protected function defineEvents()
    {
        // Define emitter to container
        \container()->set(Emitter::class, function (Container $c) {
            // Read all events that defined by user
            /**
             * @var EventDefinition $event
             */
            $event = $c->get(EventDefinition::class);
            $closures = $event->closures();
            $events = $event->events();

            return new Emitter($events, $closures);
        });
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function defineContainer()
    {
        // Define container's object(s)

        \container()->set(ILogger::class, function () {
            return new Logger(new FileHandler($this->config->get('log.log_error_file')));
        });

        \container()->set(ErrorHandler::class, function (Container $c) {
            return new ErrorHandler($c->get(ConfigManager::class), $c->get(Loader::class), $c->get(ILogger::class));
        });

        // Read all container objects that defined by user
        /**
         * @var ContainerDefinition $container
         */
        $container = \container()->get(ContainerDefinition::class);
        $container->init();
    }

    /**
     * Read routes and
     *
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Pecee\Http\Middleware\Exceptions\TokenMismatchException
     * @throws \Pecee\Http\Security\Exceptions\SecurityException
     * @throws \Pecee\SimpleRouter\Exceptions\HttpException
     * @throws \Pecee\SimpleRouter\Exceptions\NotFoundHttpException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     */
    protected function defineRoute()
    {
        $verifier = new CsrfVerifier();
        $verifier->setTokenProvider(container()->get(TokenProvider::class));

        Router::csrfVerifier($verifier);

        /**
         * @var RouteDefinition $route
         */
        $route = \container()->get(RouteDefinition::class);
        $route->init();

        // Start the routing
        Router::start();
    }

    /**
     * Check and apply some config in maintenance mode or
     * even set access to some developers
     *
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    protected function checkMaintenanceMode()
    {
        try {
            $maintenanceConfig = config()->get('main.maintenance');
            if (empty($maintenanceConfig)) return;

            // is maintenance mode on?
            if (!isset($maintenanceConfig['is_on']) || $maintenanceConfig['is_on'] === false) return;

            $forceKeys = [];
            if (
                isset($maintenanceConfig['force_with']) &&
                (
                    is_array($maintenanceConfig['force_with']) ||
                    is_string($maintenanceConfig['force_with'])
                )
            ) {
                $forceKeys = $maintenanceConfig['force_with'];
                $forceKeys = is_string($forceKeys) ? [$forceKeys] : $forceKeys;
            }

            $key = input()->get('key', null)->getValue();
            $key = !$key ? session()->getTimed('__maintenance_mode_key__') : $key;
            if (!\count($forceKeys) || empty($key) || !in_array($key, $forceKeys)) {
                $content = loader()->getContent(path()->get('error') . $maintenanceConfig['page']);
                show_500($content);
                exit(0);
            }
            if (!empty($key)) {
                session()->setTimed('__maintenance_mode_key__', $key, 7200);
            }
        } catch (\Exception $e) {
            show_500();
            exit(0);
        }
    }
}
