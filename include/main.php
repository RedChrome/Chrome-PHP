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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [19.11.2012 10:12:24] --> $
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
interface Chrome_Front_Controller_Interface
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
     * setRequest()
     *
     * @param mixed $request
     * @return
     */
    public function setRequest(Chrome_Request $request);

    /**
     * setResponse()
     *
     * @param mixed $response
     * @return
     */
    public function setResponse(Chrome_Response $response);

    /**
     * handleRequest()
     *
     * @return
     */
    public function handleRequest();

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
     * @var Chrome_Request
     */
    private $_request = null;

    /**
     * @var Chrome_Response
     */
    private $_response = null;

    /**
     * @var Chrome_Controller_Abstract
     */
    private $_controller = null;

    /**
     * Chrome_Front_Controller::setRequest()
     *
     * @param mixed $request
     * @return void
     */
    public function setRequest(Chrome_Request $request)
    {
        $this->_request = $request;
    }

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

    /**
     * Chrome_Front_Controller::getInstance()
     *
     * @return Chrome_Front_Controller
     */
    public static function getInstance()
    {
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
        }
        catch (Chrome_Exception $e) {

            $handler = new Chrome_Exception_Handler_FrontController();
            $handler->exception($e);
        }
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    public function init()
    {
        // init require-class, can be skipped if every class is defined
        // but if not, then we get nasty error, that cannot get handled easily
        $require = Chrome_Require::getInstance();
        // startup registry, can be skipped
        $registry = Chrome_Registry::getInstance();

        // init logging
        $log = Chrome_Log::getInstance();

        // only log sth. if we're in developer mode
        if(CHROME_DEVELOPER_STATUS === true) {
            $log->setLogger(new Chrome_Logger_File(TMP . CHROME_LOG_DIR . CHROME_LOG_FILE));
        } else {
            $log->setLogger(new Chrome_Logger_Null());
        }

        // startup filters

        $this->_preprocessor = new Chrome_Filter_Chain_Preprocessor();
        $this->_postprocessor = new Chrome_Filter_Chain_Postprocessor();

        // confugre database default connection
        {
            $defaultConnection = new Chrome_Database_Connection_Mysql();
            $defaultConnection->setConnectionOptions(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $dbRegistry = Chrome_Database_Registry_Connection::getInstance();
            $dbRegistry->addConnection(Chrome_Database_Facade::DEFAULT_CONNECTION, $defaultConnection);
        }

        // setting up authentication, authorisation service
        {
            $handler = new Chrome_Exception_Handler_Authentication();

            $authentication = Chrome_Authentication::getInstance();
            $authentication->setExceptionHandler($handler);

            $db = new Chrome_Authentication_Chain_Database(new Chrome_Model_Authentication_Database());
            $cookie = new Chrome_Authentication_Chain_Cookie();
            $session = new Chrome_Authentication_Chain_Session();

            // set authentication chains in the right order
            // the first chain should be session, because its the fastest one
            // the last should be the slowest, thats the db
            $authentication->addChain($session)->addChain($cookie)->addChain($db);

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

        // distinct which request is sent
        $request = Chrome_Request::getInstance();
        // set up the available request handler
        {
            // watch out for the right order you add those handlers,
            // the more stricter handlers are the first, which get added, the less stricter are the last
            // the last one should _always_ return true in canHandleRequest
            //$request->addRequestObject();
            $request->addRequestObject(new Chrome_Request_Handler_AJAX());
            // this handler is always capable of handling a request, so it always returns true in canHandleRequest
            $request->addRequestObject(new Chrome_Request_Handler_HTTP());
        }

        $requestHandler = $request->getRequest();
        $this->_request = $request->getRequestDataObject();

        // enable route matching
        {
            //import(array('Chrome_Route_Static', 'Chrome_Route_Dynamic') );
            // matches static routes
            new Chrome_Route_Static(Chrome_Model_Route_Static::getInstance());
            // matches dynamic created routes
            new Chrome_Route_Dynamic(Chrome_Model_Route_Dynamic::getInstance());
            // matches routes to administration site
            new Chrome_Route_Administration(Chrome_Model_Route_Administration::getInstance());
        }
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
            $resource = Chrome_Router::getInstance()->setExceptionHandler(new Chrome_Exception_Handler_Router())->route(new
                Chrome_URI(), $this->_request);

            // create controller class and set exception handler
            $this->_controller = $resource->initClass(Chrome_Request::getInstance()->getRequest());
            $this->_controller->setExceptionHandler(new Chrome_Exception_Handler_Default());

            $this->handleRequest();
        }
        catch (Chrome_Exception $e) {
            $handler = new Chrome_Exception_Handler_FrontController();
            $handler->exception($e);
        }
    }

    /**
     * Chrome_Front_Controller::handleRequest()
     *
     * @return void
     */
    public function handleRequest()
    {
        $this->_response = $this->_controller->getResponse();

        $this->_preprocessor->processFilters($this->_request, $this->_response);

        $this->_controller->execute();

        $this->_postprocessor->processFilters($this->_request, $this->_response);

        $this->_response->flush();
    }
}
