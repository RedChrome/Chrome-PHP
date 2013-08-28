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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.FrontController
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 18:54:26] --> $
 * @link       http://chrome-php.de
 */

//TODO: set up converter in context

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */
class Chrome_Application_Default implements Chrome_Application_Interface
{
	/**
	 * @var Chrome_Context_Application_Interface
	 */
	protected $_applicationContext = null;

	/**
	 * @var Chrome_Context_Model_Interface
	 */
	protected $_modelContext = null;

	/**
	 * @var Chrome_Filter_Chain_Preprocessor
	 */
	private $_preprocessor = null;

	/**
	 * @var Chrome_Filter_Chain_Postprocessor
	 */
	private $_postprocessor = null;

	/**
	 * @var Chrome_Controller_Abstract
	 */
	private $_controller = null;

	/**
	 * @var Chrome_Router_Interface
	 */
	private $_router = null;

	/**
	 * @var Chrome_Exception_Handler_Interface
	 */
	private $_exceptionHandler = null;

	/**
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
		if($exceptionHandler === null) {
			require_once LIB.'exception/frontcontroller.php';
			$exceptionHandler = new Chrome_Exception_Handler_FrontController();
		}

		$this->_exceptionHandler = $exceptionHandler;
	}

	/**
	 * @return void
	 */
	public function init()
	{
		try {
			$this->_init();
		} catch (Chrome_Exception $e) {
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

        $viewContext               = new Chrome_Context_View();
        $this->_modelContext       = new Chrome_Context_Model();
        $this->_applicationContext = new Chrome_Context_Application();

        $this->_applicationContext->setViewContext($viewContext);
        $this->_applicationContext->setModelContext($this->_modelContext);

        $viewFactory = new Chrome_View_Factory($viewContext);
        $viewContext->setFactory($viewFactory);

		// logging
		{
			// only log sth. if we're in developer mode
			if(CHROME_DEVELOPER_STATUS === true) {
				Chrome_Log::setLogger(new Chrome_Logger_File(TMP.CHROME_LOG_DIR.CHROME_LOG_FILE));
				$this->_exceptionHandler = new Chrome_Exception_Handler_Default();
			} else {
				Chrome_Log::setLogger(new Chrome_Logger_Null());
			}

			require_once PLUGIN.'Log/database.php';
		}

		// autoloader
		$autoloader = new Chrome_Require_Autoloader();
        {
			$autoloader->setExceptionHandler(new Chrome_Exception_Handler_Default());

			require_once PLUGIN.'Require/database.php';
			require_once PLUGIN.'Require/cache.php';
            require_once PLUGIN.'Require/model.php';

			$autoloader->appendAutoloader(new Chrome_Require_Loader_Database());
			$autoloader->appendAutoloader(new Chrome_Require_Loader_Cache());
		}

		$this->_initDatabase($this->_modelContext);

        require_once LIB.'core/require/model.php';
		// init require-class, can be skipped if every class is defined
		$autoloader->prependAutoloader(new Chrome_Require_Loader_Model(new Chrome_Model_Require_Cache(new Chrome_Model_Require_DB($this->_modelContext))));

        // configuration
        {
            $config = new Chrome_Config(new Chrome_Model_Config_Cache(new Chrome_Model_Config_DB($this->_modelContext)));
            $this->_applicationContext->setConfig($config);
        }

        // distinct which request is sent
		$requestFactory = new Chrome_Request_Factory();
		// set up the available request handler
		{
			// watch out for the right order you add those handlers,
			// the more stricter handlers are the first, which get added, the less stricter are the last
			// the last one should _always_ return true in canHandleRequest
			//$request->addRequestObject();
			// this handler is always capable of handling a request, so it always returns true in canHandleRequest
			$requestFactory->addRequestObject(new Chrome_Request_Handler_HTTP());
			$requestFactory->addRequestObject(new Chrome_Request_Handler_Console());
		}

		$reqHandler = $requestFactory->getRequest();
		$requestData = $requestFactory->getRequestDataObject();

		$this->_applicationContext->setRequestHandler($reqHandler);
		$session = $requestData->getSession();
		$cookie = $requestData->getCookie();

		// distinct which response gets send
		$responseFactory = new Chrome_Response_Factory();
		// set up the available response handlers
		{
			$responseFactory->addResponseHandler(new Chrome_Response_Handler_JSON($reqHandler));
			$responseFactory->addResponseHandler(new Chrome_Response_Handler_HTTP($reqHandler));
			$responseFactory->addResponseHandler(new Chrome_Response_Handler_Console($reqHandler));
		}

		$response = $responseFactory->getResponse();
		$this->_applicationContext->setResponse($response);

		// startup filters
		$this->_preprocessor = new Chrome_Filter_Chain_Preprocessor();
		$this->_postprocessor = new Chrome_Filter_Chain_Postprocessor();

		// setting up authentication, authorisation service
		{
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
			//Chrome_Authorisation::setAuthorisationAdapter(Chrome_RBAC::getInstance(new Chrome_Model_RBAC_DB())); // better one, but not finished ;)
			$adapter = new Chrome_Authorisation_Adapter_Default($authentication);
			$adapter->setModel(new Chrome_Model_Authorisation_Default_DB($this->_modelContext));

			$authorisation = new Chrome_Authorisation($adapter);

			// first authentication
			// user gets authenticated if session or cookie is set
			// for db authentication use:
			//
			//$authentication->authenticate(new Chrome_Authentication_Resource_Database($userName, $password, $autoLogin));
			//
			//$authentication->authenticate(new Chrome_Authentication_Resource_Database('RedChrome', 'tiger', true));
			$authentication->authenticate();
		}

		$this->_applicationContext->setAuthentication($authentication);
		$this->_applicationContext->setAuthorisation($authorisation);

		$this->_router = new Chrome_Router();
		$this->_router->setExceptionHandler(new Chrome_Exception_Handler_Default());
		// enable route matching
		{
			//import(array('Chrome_Route_Static', 'Chrome_Route_Dynamic') );
			// matches static routes
			$this->_router->addRoute(new Chrome_Route_Static(new Chrome_Model_Route_Static_Cache(new Chrome_Model_Route_Static_DB($this->_modelContext))));
			// matches dynamic created routes
			$this->_router->addRoute(new Chrome_Route_Dynamic(new Chrome_Model_Route_Dynamic_Cache(new Chrome_Model_Route_Dynamic_DB($this->_modelContext))));
			// matches routes to administration site
			$this->_router->addRoute(new Chrome_Route_Administration(new Chrome_Model_Route_Administration($this->_modelContext)));
		}

        $pluginFacade = new Chrome_View_Plugin_Facade();
        $viewContext->setPluginFacade($pluginFacade);

		/**
		 * @todo remove them from here
		 */
		$pluginFacade->registerPlugin(new Chrome_View_Plugin_HTML($this->_applicationContext));
		$pluginFacade->registerPlugin(new Chrome_View_Plugin_Decorator($this->_applicationContext));

        $converter = new Chrome_Converter();
        {
            /**
             * @todo remove them from here
             * @todo maybe delte autloader for converters? and create a factory?
             */
            $converter->addConverterDelegate(new Chrome_Converter_Delegate_String());
            $converter->addConverterDelegate(new Chrome_Converter_Delegate_TypeCastingAndStripping());
        }

        $this->_applicationContext->setConverter($converter);
	}

	/**
	 * Executes the controller
	 *
	 * @return void
	 */
	public function execute()
	{
		try {
			// get the accessed resource by Router
			$resource = $this->_router->route(new Chrome_URI($this->_applicationContext->getRequestHandler()->getRequestData(), true), $this->_applicationContext->getRequestHandler()->getRequestData());

			// create controller class and set exception handler
			$controllerFactory = new Chrome_Controller_Factory($this->_applicationContext);
			$controllerFactory->loadControllerClass($resource->getFile());
			$this->_controller = $controllerFactory->build($resource->getClass());
			$this->_controller->setExceptionHandler(new Chrome_Exception_Handler_Default());

			$this->_preprocessor->processFilters($this->_applicationContext->getRequestHandler()->getRequestData(), $this->_applicationContext->getResponse());

			$this->_controller->execute();
            {
				$design       = new Chrome_Design($this->_applicationContext, $this->_controller);
				$themeFactory = new Chrome_Design_Factory_Theme($this->_applicationContext);
				$theme        = $themeFactory->build();

				$theme->initDesign($design);

				$design->render();
			}

			$this->_postprocessor->processFilters($this->_applicationContext->getRequestHandler()->getRequestData(), $this->_applicationContext->getResponse());

			$this->_applicationContext->getResponse()->flush();

		} catch (Chrome_Exception $e) {
			$this->_exceptionHandler->exception($e);
		}
	}

	protected function _initDatabase()
    {
        $datbaseInitializer = new Chrome_Database_Initializer();
        $datbaseInitializer->initialize();

        $factory = $datbaseInitializer->getFactory();
        $factory->setLogger(new Chrome_Logger_Database());

        $this->_modelContext->setDatabaseFactory($factory);
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
