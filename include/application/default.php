<?php
use Chrome\Authorisation\Adapter\Simple;
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

/**
 * loads dependencies from composer
 */
require_once LIB . 'autoload.php';

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
class Chrome_Application_Default implements Chrome_Application_Interface
{
    /**
     *
     * @var \Chrome\Registry\Logger\Registry_Interface
     */
    protected $_loggerRegistry = null;

    /**
     *
     * @var Chrome_Context_Application_Interface
     */
    protected $_applicationContext = null;

    /**
     *
     * @var Chrome_Context_Model_Interface
     */
    protected $_modelContext = null;

    /**
     *
     * @var Chrome_Filter_Chain_Preprocessor
     */
    private $_preprocessor = null;

    /**
     *
     * @var Chrome_Filter_Chain_Postprocessor
     */
    private $_postprocessor = null;

    /**
     *
     * @var Chrome_Controller_Interface
     */
    private $_controller = null;

    /**
     *
     * @var Chrome_Router_Interface
     */
    private $_router = null;

    /**
     *
     * @var Chrome_Exception_Handler_Interface
     */
    private $_exceptionHandler = null;

    /**
     *
     * @var Chrome_Exception_Configuration_Interface
     */
    private $_exceptionConfiguration = null;

    /**
     *
     * @var \Chrome\Classloader\Autoloader_Interface
     */
    private $_classloader = null;

    /**
     *
     * @var \Chrome\DI\Container_Interface
     */
    protected $_diContainer = null;

    /**
     * Chrome_Front_Controller::getController()
     *
     * @return Chrome_Controller_Abstract
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     *
     * @return Chrome_Front_Controller
     */
    public function __construct(Chrome_Exception_Handler_Interface $exceptionHandler = null)
    {
        $this->_initLoggers();

        if($exceptionHandler === null)
        {
            require_once LIB . 'exception/frontcontroller.php';
            $exceptionHandler = new Chrome_Exception_Handler_FrontController($this->_loggerRegistry->get('application'));
        }

        $this->_exceptionHandler = $exceptionHandler;
    }

    /**
     *
     * @return void
     */
    public function init()
    {
        try
        {
            $this->_init();
        } catch(Chrome_Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    protected function _init()
    {
        $this->_exceptionConfiguration = new Chrome_Exception_Configuration();
        $this->_exceptionConfiguration->setExceptionHandler($this->_exceptionHandler);
        $this->_exceptionConfiguration->setErrorHandler(new Chrome_Exception_Error_Handler_Default());

        $viewContext = new Chrome_Context_View();
        $this->_modelContext = new Chrome_Context_Model();
        $this->_applicationContext = new Chrome_Context_Application();

        $this->_applicationContext->setLoggerRegistry($this->_loggerRegistry);
        $this->_applicationContext->setViewContext($viewContext);
        $this->_applicationContext->setModelContext($this->_modelContext);

        $this->_initDiContainer();
        $closureHandler = $this->_diContainer->getHandler('closure');
        $registryHandler = $this->_diContainer->getHandler('registry');
        $registryHandler->add('\Chrome_Context_View_Interface', $viewContext);
        $registryHandler->add('\Chrome_Context_Model_Interface', $this->_modelContext);
        $registryHandler->add('\Chrome_Context_Application_Interface', $this->_applicationContext);

        // TODO: remove view factory
        $viewFactory = new Chrome_View_Factory($viewContext);
        $viewContext->setFactory($viewFactory);
        $registryHandler->add('\Chrome_View_Factory_Interface', $viewFactory);
        $registryHandler->add('\Chrome\Hash\Hash_Interface', new \Chrome\Hash\Hash());

        $this->_initClassloader();

        $this->_initLocalization();

        $this->_initDatabase();
        $registryHandler->add('\Chrome_Database_Factory_Interface', $this->_modelContext->getDatabaseFactory());

        require_once LIB . 'core/classloader/model.php';
        require_once LIB.'core/database/interface/model.php';

        // init require-class, can be skipped if every class is defined
        $this->_classloader->prependResolver($this->_diContainer->get('\Chrome\Classloader\Resolver_Model_Interface'));

        $this->_initConfig();
        $registryHandler->add('\Chrome_Config_Interface', $this->_applicationContext->getConfig());

        $this->_initRequestAndResponse();

        // startup filters
        $this->_preprocessor = new Chrome_Filter_Chain_Preprocessor();
        $this->_postprocessor = new Chrome_Filter_Chain_Postprocessor();

        $this->_initAuthenticationAndAuthorisation();
        $registryHandler->add('\Chrome_Authentication_Interface', $this->_applicationContext->getAuthentication());

        $this->_initRouter();

        $pluginFacade = new Chrome_View_Plugin_Facade();
        $viewContext->setPluginFacade($pluginFacade);
        $viewContext->setLinker($this->_diContainer->get('\Chrome\Linker\Linker_Interface'));

        /**
         * @todo remove them from here
         */
        $pluginFacade->registerPlugin(new Chrome_View_Plugin_HTML($this->_applicationContext));
        $pluginFacade->registerPlugin(new Chrome_View_Plugin_Decorator($this->_applicationContext));

        $this->_initConverter();
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
            $resource = $this->_router->route(new Chrome_URI($this->_applicationContext->getRequestHandler()->getRequestData(), true), $this->_applicationContext->getRequestHandler()->getRequestData());

            $this->_classloader->load($resource->getClass());
            $this->_controller = $this->_diContainer->get($resource->getClass());

            $this->_controller->setExceptionHandler(new Chrome_Exception_Handler_Default());

            $this->_preprocessor->processFilters($this->_applicationContext->getRequestHandler()->getRequestData(), $this->_applicationContext->getResponse());

            $this->_controller->execute();

            // use the design from the controller, but only if he set one design
            if(($design = $this->_applicationContext->getDesign()) === null)
            {
                $design = new Chrome_Design();
                $this->_applicationContext->setDesign($design);

                $themeFactory = new Chrome_Design_Factory_Theme($this->_applicationContext);
                $theme = $themeFactory->build('chrome_one_sidebar');
                $theme->initDesign($design, $this->_controller, $this->_diContainer);
            }

            $this->_applicationContext->getResponse()->write($design->render());

            $this->_postprocessor->processFilters($this->_applicationContext->getRequestHandler()->getRequestData(), $this->_applicationContext->getResponse());

            $this->_applicationContext->getResponse()->flush();
        } catch(Chrome_Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    protected function _initDatabase()
    {
        $datbaseInitializer = new Chrome_Database_Initializer();
        $datbaseInitializer->initialize();

        $factory = $datbaseInitializer->getFactory();
        $factory->setLogger($this->_loggerRegistry->get('database'));

        $this->_modelContext->setDatabaseFactory($factory);
        /*
         * Testing... define('POSTGRESQL_HOST', 'localhost'); define('POSTGRESQL_USER', 'test'); define('POSTGRESQL_PASS', 'chrome');
         * define('POSTGRESQL_DB', 'chrome_db'); define('POSTGRESQL_SCHEMA', 'chrome'); // 5433 -> 9.1, 5432 -> 9.2, 5434 -> 9.3
         * define('POSTGRESQL_PORT', 5433); $dbRegistry = $factory->getConnectionRegistry();
         * $postgresqlTestConnection = new Chrome_Database_Connection_Postgresql();
         * $postgresqlTestConnection->setConnectionOptions(POSTGRESQL_HOST, POSTGRESQL_USER, POSTGRESQL_PASS, POSTGRESQL_DB, POSTGRESQL_PORT, POSTGRESQL_SCHEMA);
         * $postgresqlTestConnection->connect(); $dbRegistry->addConnection('postgresql_test', $postgresqlTestConnection);
         * $dbRegistry->addConnection(Chrome_Database_Registry_Connection::DEFAULT_CONNECTION, $postgresqlTestConnection, true); #
         */
    }

    protected function _initLocalization($locale = null)
    {
        if($locale === null)
        {
            $locale = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : CHROME_LOCALE_DEFAULT;
        }

        try
        {
            $locale = new \Chrome\Localization\Locale($locale);
            $localization = new \Chrome\Localization\Localization();
            $localization->setLocale($locale);
            $translate = new \Chrome\Localization\Translate_Simple($localization);
            #require_once 'Tests/dummies/localization/translate/test.php';
            #$translate = new \Chrome\Localization\Translate_Test_XX($localization);
            $localization->setTranslate($translate);
            $this->_applicationContext->getViewContext()->setLocalization($localization);
        } catch(Chrome_Exception $e)
        {
            $this->_initLocalization(CHROME_LOCALE_DEFAULT);
        }
    }

    protected function _initLoggers()
    {
        Chrome_Dir::createDir(TMP . CHROME_LOG_DIR, 0777, false);

        $dateFormat = 'Y-m-d H:i:s:u';
        $output = '[%datetime%] %channel%.%level_name%: %message%. %context% %extra%' . PHP_EOL;

        $formatter = new \Monolog\Formatter\LineFormatter($output, $dateFormat);
        $processor = new Chrome\Logger\Processor\Psr();
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
        $this->_classloader = new \Chrome\Classloader\Classloader();
        $this->_applicationContext->setClassloader($this->_classloader);

        // $this->_autoloader = new Chrome_Require_Autoloader();
        $this->_classloader->setLogger($this->_loggerRegistry->get('autoloader'));
        $this->_classloader->setExceptionHandler(new Chrome_Exception_Handler_Default());

        require_once PLUGIN . 'classloader/database.php';
        require_once PLUGIN . 'classloader/cache.php';
        require_once PLUGIN . 'classloader/model.php';

        $this->_classloader->appendResolver(new \Chrome\Classloader\Resolver_Database());
        $this->_classloader->appendResolver(new \Chrome\Classloader\Resolver_Cache());

        $autoloader = new \Chrome\Classloader\Autoloader($this->_classloader);
    }

    protected function _initAuthenticationAndAuthorisation()
    {
        $requestData = $this->_applicationContext->getRequestHandler()->getRequestData();
        $cookie = $requestData->getCookie();
        $session = $requestData->getSession();

        // setting up authentication, authorisation service
        $handler = new Chrome_Exception_Handler_Authentication();

        $authentication = new Chrome_Authentication();
        $authentication->setExceptionHandler($handler);

        $dbAuth = new Chrome_Authentication_Chain_Database(new Chrome_Model_Authentication_Database($this->_modelContext->getDatabaseFactory(), $this->_diContainer->get('\Chrome_Model_Database_Statement_Interface')));
        $cookieAuth = new Chrome_Authentication_Chain_Cookie(new Chrome_Model_Authentication_Cookie($this->_modelContext->getDatabaseFactory(), $this->_diContainer->get('\Chrome_Model_Database_Statement_Interface')), $cookie, $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));
        $sessionAuth = new Chrome_Authentication_Chain_Session($session);

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

    protected function _initRequestAndResponse()
    {
        // distinct which request is sent
        $requestFactory = new Chrome_Request_Factory();
        // set up the available request handler

        // watch out for the right order you add those handlers,
        // the more stricter handlers are the first, which get added, the less stricter are the last
        // the last one should _always_ return true in canHandleRequest
        // $request->addRequestObject();
        // this handler is always capable of handling a request, so it always returns true in canHandleRequest
        $hash = $this->_diContainer->get('\Chrome\Hash\Hash_Interface');
        $requestFactory->addRequestObject(new Chrome_Request_Handler_HTTP($hash));
        #$requestFactory->addRequestObject(new Chrome_Request_Handler_Console($hash));

        $reqHandler = $requestFactory->getRequest();
        $this->_applicationContext->setRequestHandler($requestFactory->getRequest());

        // distinct which response gets send
        $responseFactory = new Chrome_Response_Factory();
        // set up the available response handlers

        $responseFactory->addResponseHandler(new Chrome_Response_Handler_JSON($reqHandler));
        $responseFactory->addResponseHandler(new Chrome_Response_Handler_HTTP($reqHandler));
        #$responseFactory->addResponseHandler(new Chrome_Response_Handler_Console($reqHandler));

        $response = $responseFactory->getResponse();
        $this->_applicationContext->setResponse($response);
    }

    protected function _initConfig()
    {
        // configuration
        $closure = $this->_diContainer->getHandler('closure');

        $config = new Chrome_Config($this->_diContainer->get('\Chrome_Model_Config_Interface'));
        $this->_applicationContext->setConfig($config);
    }

    protected function _initRouter()
    {
        $this->_router = new Chrome_Router();
        $this->_router->setExceptionHandler(new Chrome_Exception_Handler_Default());
        // enable route matching

        $routerLogger = $this->_loggerRegistry->get('router');
        // import(array('Chrome_Route_Static', 'Chrome_Route_Dynamic') );
        // matches static routes

        $this->_router->addRoute(new Chrome_Route_Static($this->_diContainer->get('\Chrome_Model_Route_Static_Interface'), $routerLogger));
        // matches dynamic created routes
        $this->_router->addRoute(new Chrome_Route_Dynamic($this->_diContainer->get('\Chrome_Model_Route_Dynamic_Interface'), $routerLogger));
        // matches routes to administration site
        $this->_router->addRoute(new Chrome_Route_Administration(new Chrome_Model_Route_Administration($this->_modelContext), $routerLogger));
    }

    protected function _initConverter()
    {
        $converter = new Chrome_Converter();

        /**
         *
         * @todo remove them from here
         * @todo maybe delte autloader for converters? and create a factory?
         */
        $converter->addConverterDelegate(new Chrome_Converter_Delegate_String());
        $converter->addConverterDelegate(new Chrome_Converter_Delegate_TypeCastingAndStripping());

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
        $registry = new \Chrome\DI\Handler\Registry();
        $closure = new \Chrome\DI\Handler\Closure();
        $controller = new \Chrome\DI\Handler\Controller();
        $model = new \Chrome\DI\Handler\Model();
        $validator = new \Chrome\DI\Handler\Validator();

        $this->_diContainer->attachHandler('registry', $registry);
        $this->_diContainer->attachHandler('closure', $closure);
        $this->_diContainer->attachHandler('controller', $controller);
        $this->_diContainer->attachHandler('model', $model);
        $this->_diContainer->attachHandler('validator', $validator);

        $closure->add('\Chrome_Model_Config_Interface', function ($c) {

            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(CACHE . '_config.cache');
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new Chrome_Model_Config_Cache($c->get('\Chrome_Model_Config_Database'), $cache);
        }, true);

        $closure->add('\Chrome_Model_Database_Statement_Interface', function ($c) {
            return new \Chrome_Model_Database_Statement($c->get('\Chrome\Cache\Memory\DBStatement'));
        });

        $closure->add('\Chrome_Model_Route_Static_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(CACHE.'router/_static.cache');
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome_Model_Route_Static_Cache($c->get('\Chrome_Model_Route_Static_Database'), $cache);
        }, true);

        $closure->add('\Chrome_Model_Route_Static_Database', function ($c) {
            return $c->get('\Chrome_Model_Route_Static_DB');
        }, true);

        $closure->add('\Chrome_Design_Loader_Interface', function ($c) {
            $viewFactory = $c->get('\Chrome_View_Factory_Interface');
            $model = $c->get('\Chrome_Model_Design_Loader_Static_Interface');
            return new Chrome_Design_Loader_Static($c, $viewFactory, $model);
        });

        $closure->add('\Chrome_Model_Design_Loader_Static_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(CACHE.'_designLoaderStatic.cache');
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new Chrome_Model_Design_Loader_Static_Cache($c->get('\Chrome_Model_Design_Loader_Static_DB'), $cache);
        }, true);

        $closure->add('\Chrome\Classloader\Resolver_Model_Interface', function ($c) {
            return new \Chrome\Classloader\Resolver_Model($c->get('\Chrome_Model_Classloader_Model_Interface'));
        });

        $closure->add('\Chrome_Model_Classloader_Model_Interface', function ($c) {

            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(CACHE.'_require.cache');
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new Chrome_Model_Classloader_Cache($c->get('\Chrome_Model_Classloader_Model_Database'), $cache);
        }, true);

        $closure->add('\Chrome\Model\Authorisation\Simple\Model_Interface', function ($c) {
            $model = $c->get('\Chrome\Model\Authorisation\Adapter\Simple\Database');
            $model->setResourceModel($c->get('\Chrome\Resource\Model_Interface'));
            return $model;
        });

        $closure->add('\Chrome_Model_Route_Dynamic_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(CACHE.'router/_dynamic.cache');
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new Chrome_Model_Route_Dynamic_Cache($c->get('\Chrome_Model_Route_Dynamic_DB'), $cache);
        });

        $closure->add('\Chrome_Model_Register', function ($c) {
            return new Chrome_Model_Register($c->get('\Chrome_Database_Factory_Interface'), $c->get('\Chrome_Model_Database_Statement_Interface'), $c->get('\Chrome_Config_Interface'));
        });

        $closure->add('Chrome_Controller_Register', function ($c) {
            return new Chrome_Controller_Register($c->get('\Chrome_Context_Application_Interface'), $c->get('\Chrome\Interactor\User\Registration_Interface'), new Chrome_View_Register($c->get('\Chrome_Context_View_Interface')));
        });

        $closure->add('Chrome_Controller_Content_Login', function ($c) {
            return new Chrome_Controller_Content_Login($c->get('\Chrome_Context_Application_Interface'), $c->get('\Chrome\Interactor\User\Login_Interface'));
        });

        $closure->add('\Chrome\Interactor\User\Registration_Interface', function ($c) {
            require_once LIB.'modules/user/interactors/registration.php';

            $return = new \Chrome\Interactor\User\Registration($c->get('\Chrome_Config_Interface'), $c->get('\Chrome\Model\User\Registration_Interface'), $c->get('\Chrome\Hash\Hash_Interface'));
            $emailValidator = $c->get('\Chrome\Validator\User\Registration\Email');
            $nameValidator = $c->get('\Chrome_Validator_Name');
            $passwordValidator = $c->get('\Chrome_Validator_Password');
            $return->setValidators($emailValidator, $nameValidator, $passwordValidator);
            return $return;
        });

        $closure->add('\Chrome\Validator\User\Registration\Email', function ($c) {
            require_once LIB.'modules/user/validators/email.php';

            return new \Chrome\Validator\User\Registration\Email($c->get('\Chrome_Config_Interface'), $c->get('\Chrome\Helper\User\Email_Interface'));
        });

        $closure->add('\Chrome\Helper\User\Email_Interface', function ($c) {
            require_once LIB.'modules/user/helpers/email.php';

            return new \Chrome\Helper\User\Email($c->get('\Chrome\Model\User\User_Interface'), $c->get('\Chrome\Model\User\Registration_Interface'));
        }, true);

        $closure->add('\Chrome\Model\User\User_Interface', function ($c) {
            require_once LIB.'modules/user/models/user.php';

            return $c->get('\Chrome\Model\User\User');
        });

        $closure->add('\Chrome\Model\User\Registration_Interface', function ($c) {
            require_once LIB.'modules/user/models/registration.php';

            return $c->get('\Chrome\Model\User\Registration');
        });

        $closure->add('\Chrome\Cache\Memory\DBStatement', function ($c) {
            // fix this cache, only one instance!
            return $c->get('\Chrome\Cache\Memory');
        }, true);

        $closure->add('\Chrome\Cache\Memory', function ($c) {
            return new \Chrome\Cache\Memory();
        });

        $closure->add('\Chrome\Resource\Model_Interface', function ($c) {
            return $c->get('\Chrome\Model\Resource\Database');
        }, true);

        $closure->add('\Chrome\Linker\HTTP\Helper\Model\Static_Interface', function ($c) {
            $model = $c->get('\Chrome_Model_Route_Static_Database');
            $model->setResourceModel($c->get('\Chrome\Resource\Model_Interface'));
            return $model;
        }, true);

        $closure->add('\Chrome\Linker\Linker_Interface', function ($c) {
            $linker = new \Chrome\Linker\HTTP\Linker(new \Chrome_URI($c->get('\Chrome_Context_Application_Interface')->getRequestHandler()->getRequestData(), true), $c->get('\Chrome\Resource\Model_Interface'));

            require_once LIB.'core/linker/http/relative.php';
            require_once LIB.'core/linker/http/url.php';
            require_once LIB.'core/linker/http/static.php';

            $linker->addResourceHelper(new \Chrome\Linker\HTTP\RelativeHelper());
            $linker->addResourceHelper(new \Chrome\Linker\HTTP\StaticHelper($c->get('\Chrome\Linker\HTTP\Helper\Model\Static_Interface')));
            $linker->addResourceHelper(new \Chrome\Linker\HTTP\UrlHelper());

            return $linker;
        }, true);

        $closure->add('\Chrome\Interactor\User\Login_Interface', function ($c) {
            #require_once LIB.'modules/user/interactors/login.php';
            #$c->get('\Chrome_Context_Application_Interface')->getClassloader()->load('\Chrome\Interactor\User\Login');
            return new \Chrome\Interactor\User\Login($c->get('\Chrome_Authentication_Interface'));
        });
    }

    /**
     * setExceptionHandler()
     *
     * @param mixed $obj
     * @return void
     */
    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    /**
     * getExceptionHandler()
     *
     * @return Chrome_Exception_Handler_Interface
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
