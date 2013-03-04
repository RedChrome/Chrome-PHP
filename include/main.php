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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 14:32:14] --> $
 * @link       http://chrome-php.de
 */

if(!defined('CHROME_PHP')) {
    /**
     * @var boolean
     */
    define('CHROME_PHP', true);
}
/**
 * load config
 */
require_once 'config.php';

/**
 * load chrome-php core
 */
require_once 'lib/core/core.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.FrontController
 */
interface Chrome_Front_Controller_Interface extends Chrome_Exception_Processable_Static_Interface
{
    /**
     * setController()
     *
     * @param mixed $controller
     * @return
     */
    public function setController(Chrome_Controller_Abstract $controller);

    /**
     * getController()
     *
     * @return
     */
    public function getController();

    /**
     * getInstance()
     *
     * @return
     */
    public static function getInstance();

    /**
     * setResponse()
     *
     * @param mixed $response
     * @return
     */
    public function setResponse(Chrome_Response $response);

    /**
     *
     * @return Chrome_Request_Handler_Interface
     */
    public function getRequestHandler();

    /**
     * init()
     *
     * @return void
     */
    public function init();

    /**
     * execute()
     *
     * @return void
     */
    public function execute();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.FrontController
 */
class Chrome_Front_Controller implements Chrome_Front_Controller_Interface
{
    private static $_instance = null;

    /**
     * @var Chrome_Filter_Chain_Preprocessor
     */
    private $_preprocessor = null;

    /**
     * @var Chrome_Filter_Chain_Postprocessor
     */
    private $_postprocessor = null;

    /**
     * @var Chrome_Request_Data_Interface
     */
    private $_requestData = null;

    /**
     * @var Chrome_Request_Handler_Interface
     */
    private $_requestHandler;

    /**
     * @var Chrome_Response
     */
    private $_response = null;

    /**
     * @var Chrome_Controller_Abstract
     */
    private $_controller = null;

    /**
     * @var Chrome_Exception_Handler_Interface
     */
    private static $_exceptionHandler = null;

    /**
     * Chrome_Front_Controller::setResponse()
     *
     * Sets the Request class
     *
     * @param Chrome_Request $response
     * @return void
     */
    public function setResponse(Chrome_Response $response)
    {
        $this->_response = $response;
    }

    /**
     * Chrome_Front_Controller::setController()
     *
     * Sets the controller class
     *
     * @param Chrome_Controller_Abstract $controller
     * @return void
     */
    public function setController(Chrome_Controller_Abstract $controller)
    {
        $this->_controller = $controller;
    }

    /**
     * Chrome_Front_Controller::getController()
     *
     * @return Chrome_Controller_Abstract
     */
    public function getController()
    {
        return $this->_controller;
    }

    public function getRequestHandler() {
        return $this->_requestHandler;
    }

    /**
     * Chrome_Front_Controller::getInstance()
     *
     * @return Chrome_Front_Controller
     */
    public static function getInstance()
    {
        if(self::$_exceptionHandler === null) {

            require_once LIB.'exception/frontcontroller.php';
            self::$_exceptionHandler = new Chrome_Exception_Handler_FrontController();
        }

        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Front_Controller::__construct()
     *
     * @return Chrome_Front_Controller
     */
    private function __construct()
    {
        try {
            $this->init();
        } catch (Chrome_Exception $e) {
            self::$_exceptionHandler->exception($e);
        }
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    public function init()
    {
        // only log sth. if we're in developer mode
        if(CHROME_DEVELOPER_STATUS === true) {
            Chrome_Log::setLogger(new Chrome_Logger_File(TMP . CHROME_LOG_DIR . CHROME_LOG_FILE));
            self::$_exceptionHandler = new Chrome_Exception_Handler_Default();
        } else {
            Chrome_Log::setLogger(new Chrome_Logger_Null());
        }

        if(CHROME_LOG_SQL_ERRORS === true) {
            require_once PLUGIN.'Log/database.php';
        }

        require_once LIB.'core/require/model.php';
        Chrome_Require::setModel(new Chrome_Model_Require_Cache(new Chrome_Model_Require_DB()));
        // init require-class, can be skipped if every class is defined
        // but if not, then we get nasty error, that cannot get handled easily
        $require = Chrome_Require::getInstance();
        // startup registry, can be skipped
        $registry = Chrome_Registry::getInstance();

        $hash = Chrome_Hash::getInstance();

        $cookie  = new Chrome_Cookie($hash);
        $session = new Chrome_Session($cookie, $hash);

        // distinct which request is sent
        $requestFactory = new Chrome_Request_Factory();
        // set up the available request handler
        {
            // watch out for the right order you add those handlers,
            // the more stricter handlers are the first, which get added, the less stricter are the last
            // the last one should _always_ return true in canHandleRequest
            //$request->addRequestObject();
            $requestFactory->addRequestObject(new Chrome_Request_Handler_AJAX($cookie, $session));
            // this handler is always capable of handling a request, so it always returns true in canHandleRequest
            $requestFactory->addRequestObject(new Chrome_Request_Handler_HTTP($cookie, $session));
        }

        $this->_requestHandler = $requestFactory->getRequest();
        $this->_requestData = $requestFactory->getRequestDataObject();



        // startup filters

        $this->_preprocessor = new Chrome_Filter_Chain_Preprocessor();
        $this->_postprocessor = new Chrome_Filter_Chain_Postprocessor();

        // setting up authentication, authorisation service
        {
            $handler = new Chrome_Exception_Handler_Authentication();

            $authentication = Chrome_Authentication::getInstance();
            $authentication->setExceptionHandler($handler);

            $dbAuth = new Chrome_Authentication_Chain_Database(new Chrome_Model_Authentication_Database());
            $cookieAuth = new Chrome_Authentication_Chain_Cookie(new Chrome_Model_Authentication_Cookie(), $cookie);
            $sessionAuth = new Chrome_Authentication_Chain_Session($session);

            // set authentication chains in the right order
            // the first chain should be session, because its the fastest one
            // the last should be the slowest, thats the db
            $authentication->addChain($cookieAuth)->addChain($sessionAuth)->addChain($dbAuth);

            // set authorisation service
            //Chrome_Authorisation::setAuthorisationAdapter(Chrome_RBAC::getInstance(new Chrome_Model_RBAC_DB())); // better one, but not finished ;)
            $adapter = Chrome_Authorisation_Adapter_Default::getInstance();
            $adapter->setModel(new Chrome_Model_Authorisation_Default_DB());

            Chrome_Authorisation::setAuthorisationAdapter($adapter);

            // needed for the database, because it fetches there the rightHandler instance
            $registry->set('database', 'right_handler', new Chrome_Database_Right_Handler_Default(), true);

            // first authentication
            // user gets authenticated if session or cookie is set
            // for db authentication use:
            //
            //$authentication->authenticate(new Chrome_Authentication_Resource_Database($userName, $password, $autoLogin));
            //
            //$authentication->authenticate(new Chrome_Authentication_Resource_Database('RedChrome', 'tiger', true));
            $authentication->authenticate();
        }

        // enable route matching
        {
            //import(array('Chrome_Route_Static', 'Chrome_Route_Dynamic') );
            // matches static routes
            new Chrome_Route_Static(new Chrome_Model_Route_Static_Cache( new Chrome_Model_Route_Static_DB() ));
            // matches dynamic created routes
            new Chrome_Route_Dynamic(new Chrome_Model_Route_Dynamic_Cache(new Chrome_Model_Route_Dynamic_DB()));
            // matches routes to administration site
            new Chrome_Route_Administration(new Chrome_Model_Route_Administration());
        }

        Chrome_View_Abstract::setPluginObject(Chrome_View_Handler::getInstance());
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
            $resource = Chrome_Router::getInstance()->setExceptionHandler(new Chrome_Exception_Handler_Default())->route(new
                Chrome_URI($this->_requestData, true), $this->_requestData);

            // create controller class and set exception handler
            $this->_controller = $resource->initClass($this->_requestHandler);
            $this->_controller->setExceptionHandler(new Chrome_Exception_Handler_Default());


            $this->_response = $this->_controller->getResponse();

            $this->_preprocessor->processFilters($this->_requestData, $this->_response);

            $this->_controller->execute();

            Chrome_Design::getInstance()->render($this->_controller);

            $this->_postprocessor->processFilters($this->_requestData, $this->_response);

            $this->_response->flush();

        } catch (Chrome_Exception $e) {
            self::$_exceptionHandler->exception($e);
        }
    }

    /**
     * setExceptionHandler()
     *
     * @param mixed $obj
     * @return void
     */
    public static function setExceptionHandler(Chrome_Exception_Handler_Interface $obj) {
        self::$_exceptionHandler = $obj;
    }

    /**
     * getExceptionHandler()
     *
     * @return Chrome_Exception_Handler_Interface
     */
    public static function getExceptionHandler() {
        return self::$_exceptionHandler;
    }
}
