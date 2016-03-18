<?php
/**
 * CHROME-PHP CMS
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */

namespace Chrome\Application;

use Chrome\Authorisation\Adapter\Simple;
use Chrome\View\Plugin\Facade;

/**
 * loads dependencies from composer
 */
require_once LIB . 'vendor/autoload.php';

/**
 * load chrome-php core
 */
require_once LIB . 'core/core.php';

/**
 * Application for web requests
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */
class DefaultApplication implements Application_Interface
{
    /**
     * @var \Chrome\Registry\Logger\Registry_Interface
     */
    protected $_loggerRegistry = null;

    /**
     * @var \Chrome\Context\Application_Interface
     */
    protected $_applicationContext = null;

    /**
     * @var \Chrome\Context\Model_Interface
     */
    protected $_modelContext = null;

    /**
     * @var \Chrome\Filter\Chain\Preprocessor
     */
    private $_preprocessor = null;

    /**
     * @var \Chrome\Filter\Chain\Postprocessor
     */
    private $_postprocessor = null;

    /**
     * @var \Chrome\Controller\Controller_Interface
     */
    private $_controller = null;

    /**
     * @var \Chrome\Router\Router_Interface
     */
    private $_router = null;

    /**
     * @var \Chrome\Exception\Handler_Interface
     */
    private $_exceptionHandler = null;

    /**
     * @var \Chrome\Exception\Configuration_Interface
     */
    private $_exceptionConfiguration = null;

    /**
     * @var \Chrome\Classloader\Autoloader_Interface
     */
    private $_classloader = null;

    /**
     * @var \Chrome\DI\Container_Interface
     */
    protected $_diContainer = null;

    /**
     * @return \Chrome\Controller\Controller_Interface
     */
    public function getController()
    {
        return $this->_controller;
    }

    public function __construct(\Chrome\Exception\Handler_Interface $exceptionHandler = null)
    {
        $this->_initLoggers();

        if($exceptionHandler === null)
        {
            require_once LIB . 'exception/handler/frontcontroller.php';
            $exceptionHandler = new \Chrome\Exception\Handler\HtmlStackTrace($this->_loggerRegistry->get('application'));
        }

        $this->_exceptionHandler = $exceptionHandler;
    }

    public function init(Application_Interface $app = null)
    {
        try
        {
            $this->_init($app);
        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    protected function _init(Application_Interface $app = null)
    {
        $this->_exceptionConfiguration = new \Chrome\Exception\Configuration();
        $this->_exceptionConfiguration->setExceptionHandler($this->_exceptionHandler);
        $this->_exceptionConfiguration->setErrorHandler(new \Chrome\Exception\Handler\DefaultErrorHandler());

        $viewContext = new \Chrome\Context\View();
        $this->_modelContext = new \Chrome\Context\Model();
        $this->_applicationContext = new \Chrome\Context\Application();

        $this->_initClassloader();

        $this->_applicationContext->setLoggerRegistry($this->_loggerRegistry);
        $this->_applicationContext->setViewContext($viewContext);
        $this->_applicationContext->setModelContext($this->_modelContext);


        $this->_initDiContainer();
        $closureHandler = $this->_diContainer->getHandler('closure');
        $registryHandler = $this->_diContainer->getHandler('registry');
        $registryHandler->add('\Chrome\Context\View_Interface', $viewContext);
        $registryHandler->add('\Chrome\Context\Model_Interface', $this->_modelContext);
        $registryHandler->add('\Chrome\Context\Application_Interface', $this->_applicationContext);
        $registryHandler->add('\Chrome\Registry\Logger\Registry_Interface', $this->_loggerRegistry);

        $viewFactory = new \Chrome\View\Factory($this->_diContainer);
        $viewContext->setFactory($viewFactory);
        $registryHandler->add('\Chrome\View\Factory_Interface', $viewFactory);
        $registryHandler->add('\Chrome\Hash\Hash_Interface', new \Chrome\Hash\Hash());


        $this->_initDatabase();
        $registryHandler->add('\Chrome\Database\Factory\Factory_Interface', $this->_modelContext->getDatabaseFactory());

        require_once LIB.'core/classloader/model.php';
        require_once LIB.'core/database/facade/model.php';

        // init require-class, can be skipped if every class is defined
        $this->_classloader->prependResolver($this->_diContainer->get('\Chrome\Classloader\Resolver\Model_Interface'));

        $this->_initConfig();
        $registryHandler->add('\Chrome\Config\Config_Interface', $this->_applicationContext->getConfig());

        $this->_initRequestAndResponse($app);

        // startup filters
        $this->_preprocessor = new \Chrome\Filter\Chain\Preprocessor();
        $this->_postprocessor = new \Chrome\Filter\Chain\Postprocessor();

        $this->_initAuthenticationAndAuthorisation();
        $registryHandler->add('\Chrome\Authentication\Authentication_Interface', $this->_applicationContext->getAuthentication());

        $this->_initRouter();

        $pluginFacade = new \Chrome\View\Plugin\Facade();
	// TODO: remove pluginc facade
        $viewContext->setPluginFacade($pluginFacade);

        $linker = $this->_diContainer->get('\Chrome\Linker\Linker_Interface');
        //$linker->setBasepath(ROOT_URL);
        $viewContext->setLinker($linker);

        /**
         * @todo remove them from here
         */
        $pluginFacade->registerPlugin(new \Chrome\View\Plugin\Html($this->_applicationContext));
        $pluginFacade->registerPlugin(new \Chrome\View\Plugin\Decorator($this->_applicationContext));

        $this->_initConverter();
        $this->_initLocalization();
    }

    /**
     * Executes the controller
     *
     * @return void
     */
    public function execute()
    {
        try
        {
            // get the accessed resource by Router
            $resource = $this->_router->route($this->_applicationContext->getRequestContext()->getRequest());

            $this->_classloader->load($resource->getClass());
            $this->_controller = $this->_diContainer->get($resource->getClass());

            $this->_controller->setExceptionHandler(new \Chrome\Exception\Handler\HtmlStackTrace());

            $this->_preprocessor->processFilters($this->_applicationContext->getRequestContext()->getRequest(), $this->_applicationContext->getResponse());

            $this->_controller->execute();

            $design = $this->_initDesign();

            $this->_applicationContext->getResponse()->write($design->render());

            $this->_postprocessor->processFilters($this->_applicationContext->getRequestContext()->getRequest(), $this->_applicationContext->getResponse());

            $this->_applicationContext->getResponse()->flush();
        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    protected function _initDesign()
    {
        // use the design from the controller, but only if he set one design
        if(($design = $this->_applicationContext->getDesign()) !== null) {
            return $design;
        }

        $design = new \Chrome\Design\Design();
        $this->_applicationContext->setDesign($design);

        $theme = $this->_diContainer->get('\Chrome\Design\Theme\ChromeOneSidebar');

        $theme->setDesign($design);
        $theme->setController($this->_controller);
        $theme->apply();

        return $design;
    }

    protected function _initDatabase()
    {
        $datbaseInitializer = new \Chrome\Database\Initializer\Initializer();
        $datbaseInitializer->initialize();

        $factory = $datbaseInitializer->getFactory();
        $factory->setLogger($this->_loggerRegistry->get('database'));

        $this->_modelContext->setDatabaseFactory($factory);
        /*
         * Testing...
              define('POSTGRESQL_HOST', 'localhost'); define('POSTGRESQL_USER', 'test'); define('POSTGRESQL_PASS', 'chrome');
              define('POSTGRESQL_DB', 'chrome_db'); define('POSTGRESQL_SCHEMA', 'chrome'); // 5433 -> 9.1, 5432 -> 9.2, 5434 -> 9.3
              define('POSTGRESQL_PORT', 5433); $dbRegistry = $factory->getConnectionRegistry();
              $postgresqlTestConnection = new \Chrome\Database\Connection\Postgresql();
              $postgresqlTestConnection->setConnectionOptions(POSTGRESQL_HOST, POSTGRESQL_USER, POSTGRESQL_PASS, POSTGRESQL_DB, POSTGRESQL_PORT, POSTGRESQL_SCHEMA);
              $postgresqlTestConnection->connect(); $dbRegistry->addConnection('postgresql_test', $postgresqlTestConnection);
              $dbRegistry->addConnection(Chrome\Database\Registry\Connection::DEFAULT_CONNECTION, $postgresqlTestConnection, true);
         */
    }

    protected function _initLocalization($locale = null)
    {
        if($locale === null)
        {
            $serverData = $this->_applicationContext->getRequestContext()->getRequest()->getServerParams();

            $locale = isset($serverData['HTTP_ACCEPT_LANGUAGE']) ? $serverData['HTTP_ACCEPT_LANGUAGE'] : null;

            if($locale === null)
            {
                $local = CHROME_LOCALE_DEFAULT;
            }
        }

        try
        {
            $locale = new \Chrome\Localization\Locale($locale);
            $localization = new \Chrome\Localization\Localization();
            $localization->setLocale($locale);

            // for testing
            if($locale->getPrimaryLanguage() == 'xx' AND $locale->getRegion() == 'XX') {
                require_once 'tests/dummies/localization/translate/test.php';
                $translate = new \Chrome\Localization\Translate_Test_XX(new \Chrome\Directory(''), $localization);
            } else {
                $translate = new \Chrome\Localization\Translate_Simple(new \Chrome\Directory(RESOURCE.'translations/'), $localization);
            }

            // load default validate messages
            $translate->load('validate');
            #require_once 'tests/dummies/localization/translate/test.php';
            #   $translate = new \Chrome\Localization\Translate_Test_XX($localization);
            $localization->setTranslate($translate);
            $this->_applicationContext->getViewContext()->setLocalization($localization);
        } catch(\Chrome\Exception $e)
        {
            $this->_initLocalization(CHROME_LOCALE_DEFAULT);
        }
    }

    protected function _initLoggers()
    {
        $loggerDirectory = new \Chrome\Directory(TMP . CHROME_LOG_DIR);
        $loggerDirectory->create();

        $dateFormat = 'Y-m-d H:i:s:u';
        $output = '[%datetime%] %channel%.%level_name%: %message%. %context% %extra%' . PHP_EOL;

        $formatter = new \Monolog\Formatter\LineFormatter($output, $dateFormat);
        $processor = new \Chrome\Logger\Processor\Psr();
        $stream = new \Monolog\Handler\StreamHandler(TMP . CHROME_LOG_DIR . 'log.log');
        $streamDatabase = new \Monolog\Handler\StreamHandler(TMP . CHROME_LOG_DIR . 'database.log');
        $stream->setFormatter($formatter);
        $stream->pushProcessor($processor);
        $streamDatabase->setFormatter($formatter);
        $streamDatabase->pushProcessor($processor);

        $this->_loggerRegistry = new \Chrome\Registry\Logger\Registry();

        $loggers = array('application', 'router', 'autoloader');

        foreach($loggers as $loggerName)
        {
            $logger = new \Monolog\Logger($loggerName);
            $logger->pushHandler($stream);

            $this->_loggerRegistry->set($loggerName, $logger);
        }

        $databaseLogger = new \Monolog\Logger('database');
        $databaseLogger->pushHandler($streamDatabase);
        $this->_loggerRegistry->set('database', $databaseLogger);

        $this->_loggerRegistry->set(\Chrome\Registry\Logger\Registry::DEFAULT_LOGGER, $this->_loggerRegistry->get('application'));
    }

    protected function _initClassloader()
    {
        // autoloader
        $this->_classloader = new \Chrome\Classloader\Classloader(new \Chrome\Directory(BASEDIR));
        $this->_applicationContext->setClassloader($this->_classloader);

        $this->_classloader->setLogger($this->_loggerRegistry->get('autoloader'));
        $this->_classloader->setExceptionHandler(new \Chrome\Exception\Handler\HtmlStackTrace());

        require_once PLUGIN . 'classloader/database.php';
        require_once PLUGIN . 'classloader/cache.php';
        require_once PLUGIN . 'classloader/model.php';

        $this->_classloader->appendResolver(new \Chrome\Classloader\Resolver\Database(new \Chrome\Directory('lib/core/database')));
        $this->_classloader->appendResolver(new \Chrome\Classloader\Resolver\Cache(new \Chrome\Directory('plugins/cache')));

        $autoloader = new \Chrome\Classloader\Autoloader($this->_classloader);
    }

    protected function _initAuthenticationAndAuthorisation()
    {
        $requestContext = $this->_applicationContext->getRequestContext();
        $cookie = $requestContext->getCookie();
        $session = $requestContext->getSession();

        // setting up authentication, authorisation service
        $handler = new \Chrome\Exception\Handler\Authentication();

        $authentication = new \Chrome\Authentication\Authentication();
        $authentication->setExceptionHandler($handler);

        $dbAuth = new \Chrome\Authentication\Chain\DatabaseChain(new \Chrome\Model\Authentication\Database($this->_modelContext->getDatabaseFactory(), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface')));
        $cookieAuth = new \Chrome\Authentication\Chain\CookieChain(new \Chrome\Model\Authentication\Cookie($this->_modelContext->getDatabaseFactory(), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface')), $cookie, $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));
        $sessionAuth = new \Chrome\Authentication\Chain\SessionChain($session);

        // set authentication chains in the right order
        // the first chain should be session, because its the fastest one
        // the last should be the slowest, thats the db
        $authentication->addChain($cookieAuth)->addChain($sessionAuth)->addChain($dbAuth);

        // first authentication
        // user gets authenticated if session or cookie is set
        // for db authentication use:
        //
        // $authentication->authenticate(new Chrome_Authentication_Resource_Database($userName, $password, $autoLogin));
        //
        // $authentication->authenticate(new Chrome_Authentication_Resource_Database('RedChrome', 'tiger', true));
        $authentication->authenticate();

        // set authorisation service
        // Chrome_Authorisation::setAuthorisationAdapter(Chrome_RBAC::getInstance(new Chrome_Model_RBAC_DB())); // better one, but not finished ;)
        $adapter = new \Chrome\Authorisation\Adapter\Simple($this->_diContainer->get('\Chrome\Model\Authorisation\Simple\Model_Interface'));
        $authorisation = new \Chrome\Authorisation\Authorisation($adapter, $authentication->getAuthenticationID());

        $this->_applicationContext->setAuthentication($authentication);
        $this->_applicationContext->setAuthorisation($authorisation);
    }

    protected function _initRequestAndResponse(Application_Interface $app = null)
    {
        $request = null;

        if($app !== null) {
            $context = $app->getApplicationContext()->getRequestContext();
            if($context !== null) {
                $request = $context->getRequest();
            }
        }

        if($request === null) {
            $request = $this->_diContainer->get('\Psr\Http\Message\ServerRequestInterface');
        }

        $this->_applicationContext->setRequestContext($this->_diContainer->get('\Chrome\Request\RequestContext_Interface'));
        $this->_applicationContext->setResponse(new \Chrome\Response\HTTP($request->getServerParams()['SERVER_PROTOCOL']));
    }

    protected function _initConfig()
    {
        $config = new \Chrome\Config\Config($this->_diContainer->get('\Chrome\Model\Config'));
        $this->_applicationContext->setConfig($config);
    }

    protected function _initRouter()
    {
        $this->_router = new \Chrome\Router\Router();
        $this->_router->setExceptionHandler(new \Chrome\Exception\Handler\HtmlStackTrace());
        $this->_router->setBasepath(ROOT_URL);
        // enable route matching

        $routerLogger = $this->_loggerRegistry->get('router');
        // import(array('\Chrome\Router\Route\StaticRoute', '\Chrome\Router\Route\DynamicRoute') );
        // matches static routes

        $this->_router->addRoute(new \Chrome\Router\Route\FixedRoute($this->_diContainer->get('\Chrome\Model\Route\Static_Interface'), $routerLogger));
        // matches dynamic created routes
        $this->_router->addRoute(new \Chrome\Router\Route\DynamicRoute($this->_diContainer->get('\Chrome\Model\Route\Dynamic'), $routerLogger));

        $this->_router->addRoute(new \Chrome\Router\Route\FallbackRoute($this->_diContainer->get('\Chrome\Config\Config_Interface')));
        // matches routes to administration site
        //$this->_router->addRoute(new Chrome_Route_Administration(new Chrome_Model_Route_Administration($this->_modelContext), $routerLogger));
    }

    protected function _initConverter()
    {
        $converter = new \Chrome\Converter\Converter();

        /**
         *
         * @todo remove them from here
         * @todo maybe delte autloader for converters? and create a factory?
         */
        $converter->addConverterDelegate(new \Chrome\Converter\Delegate\StringDelegate());
        $converter->addConverterDelegate(new \Chrome\Converter\Delegate\TypeCastingAndStrippingDelegate());

        $this->_applicationContext->setConverter($converter);
    }

    protected function _initDiContainer()
    {
        $this->_diContainer = new \Chrome\DI\Container();
        $this->_applicationContext->setDiContainer($this->_diContainer);

        require_once LIB . 'core/dependency_injection/closure.php';
        require_once LIB . 'core/dependency_injection/registry.php';
        require_once LIB . 'core/dependency_injection/controller.php';
        require_once LIB . 'core/dependency_injection/model.php';
        require_once LIB . 'core/dependency_injection/validator.php';
	    require_once LIB . 'core/dependency_injection/view.php';
        require_once LIB . 'core/dependency_injection/theme.php';

        #require_once LIB . 'core/dependency_injection/invoker/loggable.php';
        require_once LIB . 'core/dependency_injection/invoker/processable.php';

        $this->_diContainer->attachHandler('registry', new \Chrome\DI\Handler\Registry());
        $this->_diContainer->attachHandler('closure', new \Chrome\DI\Handler\Closure());
        $this->_diContainer->attachHandler('controller', new \Chrome\DI\Handler\Controller());
        $this->_diContainer->attachHandler('model', new \Chrome\DI\Handler\Model());
	    $this->_diContainer->attachHandler('view', new \Chrome\DI\Handler\Validator());
	    $this->_diContainer->attachHandler('validator', new \Chrome\DI\Handler\View());
        $this->_diContainer->attachHandler('theme', new \Chrome\DI\Handler\Theme());

        #$this->_diContainer->attachInvoker('loggable', new \Chrome\DI\Invoker\LoggableInterfaceInvoker());
        $this->_diContainer->attachInvoker('processable', new \Chrome\DI\Invoker\ProcessableInterfaceInvoker());

        $structuredDirectoryLoader = new \Chrome\DI\Loader\StructuredDirectory(new \Chrome\Directory(BASEDIR.'application/default/dependency_injection'));
        $structuredDirectoryLoader->setLogger($this->_loggerRegistry->get('application'));
        $classIterator = $structuredDirectoryLoader->load($this->_diContainer);

        $classIteratorLoader = new \Chrome\DI\Loader\ClassIterator($classIterator);
        $classIteratorLoader->setLogger($this->_loggerRegistry->get('application'));
        $classIteratorLoader->load($this->_diContainer);
    }

    /**
     * @param \Chrome\Exception\Handler_Interface $obj
     */
    public function setExceptionHandler(\Chrome\Exception\Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    /**
     * @return \Chrome\Exception\Handler_Interface
     */
    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    public function getApplicationContext()
    {
        return $this->_applicationContext;
    }

    public function getDiContainer()
    {
        return $this->_diContainer;
    }

    public function getExceptionConfiguration()
    {
        return $this->_exceptionConfiguration;
    }
}
