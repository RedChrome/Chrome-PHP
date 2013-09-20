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

/**
 * loads dependencies from composer
 */
require_once LIB.'autoload.php';

/**
 * load chrome-php core
 */
require_once LIB.'core/core.php';

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
     * @var Chrome_Controller_Abstract
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

        $viewFactory = new Chrome_View_Factory($viewContext);
        $viewContext->setFactory($viewFactory);

        $cacheFactoryRegistry = new \Chrome\Registry\Cache\Factory\Registry();
        $cacheFactoryRegistry->set(\Chrome\Registry\Cache\Factory\Registry::DEFAULT_FACTORY, new Chrome_Cache_Factory());

        // @todo: just a dummy instanciation, do a better one later

        $locale = new \Chrome\Localization\Locale();
        $localization = new \Chrome\Localization\Localization();
        $localization->setLocale($locale);
        $translate = new \Chrome\Localization\Translate_Simple($localization);
        #require_once 'Tests/dummies/localization/translate/test.php';
        #$translate = new \Chrome\Localization\Translate_Test_XX($localization);
        $localization->setTranslate($translate);

        $viewContext->setLocalization($localization);

        // autoloader
        $autoloader = new Chrome_Require_Autoloader();
        $autoloader->setLogger($this->_loggerRegistry->get('autoloader'));
        {
            $autoloader->setExceptionHandler(new Chrome_Exception_Handler_Default());

            require_once PLUGIN . 'Require/database.php';
            require_once PLUGIN . 'Require/cache.php';
            require_once PLUGIN . 'Require/model.php';

            $autoloader->appendAutoloader(new Chrome_Require_Loader_Database());
            $autoloader->appendAutoloader(new Chrome_Require_Loader_Cache());
        }

        $this->_initDatabase();

        $this->_initControllerFactory();

        require_once LIB . 'core/require/model.php';
        // init require-class, can be skipped if every class is defined
        $autoloader->prependAutoloader(new Chrome_Require_Loader_Model(new Chrome_Model_Require_Cache(new Chrome_Model_Require_DB($this->_modelContext))));

        $this->_initConfig();

        $this->_initRequestAndResponse();

        // startup filters
        $this->_preprocessor = new Chrome_Filter_Chain_Preprocessor();
        $this->_postprocessor = new Chrome_Filter_Chain_Postprocessor();

        $this->_initAuthenticationAndAuthorisation();

        $this->_initRouter();

        $pluginFacade = new Chrome_View_Plugin_Facade();
        $viewContext->setPluginFacade($pluginFacade);

        /**
         *
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

            // create controller class and set exception handler
            $controllerFactory = $this->_applicationContext->getControllerFactoryRegistry()->get();
            $controllerFactory->loadControllerClass($resource->getFile());
            $this->_controller = $controllerFactory->build($resource->getClass());
            $this->_controller->setExceptionHandler(new Chrome_Exception_Handler_Default());

            $this->_preprocessor->processFilters($this->_applicationContext->getRequestHandler()->getRequestData(), $this->_applicationContext->getResponse());

            $this->_controller->execute();

            // use the design from the controller, but only if he set one design
            if( ($design = $this->_applicationContext->getDesign()) === null)
            {
                $design = new Chrome_Design();
                $this->_applicationContext->setDesign($design);

                $themeFactory = new Chrome_Design_Factory_Theme($this->_applicationContext);
                $theme = $themeFactory->build('chrome_one_sidebar');
                $theme->initDesign($design, $this->_controller);
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
    }

    protected function _initLoggers()
    {
        Chrome_Dir::createDir(TMP . CHROME_LOG_DIR, 0777, false);

        $dateFormat = 'Y-m-d H:i:s:u';
        $output = '[%datetime%] %channel%.%level_name%: %message%. %context% %extra%' . PHP_EOL;

        $formatter = new \Monolog\Formatter\LineFormatter($output, $dateFormat);
        $processor = new Chrome\Logger\Processor\Psr();
        $stream = new \Monolog\Handler\StreamHandler(TMP . CHROME_LOG_DIR . CHROME_LOG_FILE);
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

    protected function _initControllerFactory()
    {
        $controllerFactoryRegistry = new \Chrome\Registry\Controller\Factory\Registry_Single();

        $controllerFactory = new \Chrome_Controller_Factory($this->_applicationContext);
        $controllerFactoryRegistry->set(\Chrome\Registry\Controller\Factory\Registry_Single::DEFAULT_FACTORY, $controllerFactory);

        $this->_applicationContext->setControllerFactoryRegistry($controllerFactoryRegistry);
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

        $dbAuth = new Chrome_Authentication_Chain_Database(new Chrome_Model_Authentication_Database($this->_modelContext));
        $cookieAuth = new Chrome_Authentication_Chain_Cookie(new Chrome_Model_Authentication_Cookie($this->_modelContext), $cookie);
        $sessionAuth = new Chrome_Authentication_Chain_Session($session);

        // set authentication chains in the right order
        // the first chain should be session, because its the fastest one
        // the last should be the slowest, thats the db
        $authentication->addChain($cookieAuth)->addChain($sessionAuth)->addChain($dbAuth);

        // set authorisation service
        // Chrome_Authorisation::setAuthorisationAdapter(Chrome_RBAC::getInstance(new Chrome_Model_RBAC_DB())); // better one, but not finished ;)
        $adapter = new Chrome_Authorisation_Adapter_Default($authentication);
        $adapter->setModel(new Chrome_Model_Authorisation_Default_DB($this->_modelContext));

        $authorisation = new Chrome_Authorisation($adapter);

        // first authentication
        // user gets authenticated if session or cookie is set
        // for db authentication use:
        //
        // $authentication->authenticate(new Chrome_Authentication_Resource_Database($userName, $password, $autoLogin));
        //
        // $authentication->authenticate(new Chrome_Authentication_Resource_Database('RedChrome', 'tiger', true));
        $authentication->authenticate();

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
        $requestFactory->addRequestObject(new Chrome_Request_Handler_HTTP());
        $requestFactory->addRequestObject(new Chrome_Request_Handler_Console());

        $reqHandler = $requestFactory->getRequest();
        $this->_applicationContext->setRequestHandler($requestFactory->getRequest());

        // distinct which response gets send
        $responseFactory = new Chrome_Response_Factory();
        // set up the available response handlers

        $responseFactory->addResponseHandler(new Chrome_Response_Handler_JSON($reqHandler));
        $responseFactory->addResponseHandler(new Chrome_Response_Handler_HTTP($reqHandler));
        $responseFactory->addResponseHandler(new Chrome_Response_Handler_Console($reqHandler));

        $response = $responseFactory->getResponse();
        $this->_applicationContext->setResponse($response);
    }

    protected function _initConfig()
    {
        // configuration
        $config = new Chrome_Config(new Chrome_Model_Config_Cache(new Chrome_Model_Config_DB($this->_modelContext)));
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
        $this->_router->addRoute(new Chrome_Route_Static(new Chrome_Model_Route_Static_Cache(new Chrome_Model_Route_Static_DB($this->_modelContext)), $routerLogger));
        // matches dynamic created routes
        $this->_router->addRoute(new Chrome_Route_Dynamic(new Chrome_Model_Route_Dynamic_Cache(new Chrome_Model_Route_Dynamic_DB($this->_modelContext)), $routerLogger));
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

    public function getExceptionConfiguration()
    {
        return $this->_exceptionConfiguration;
    }
}
