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
 * load error & exception classes
 */
require_once LIB.'core/error/error.php';

/**
 * load file_system class for fast isFile & isDir functions
 */
require_once LIB.'core/file_system/file_system.php';

/**
 * load Chrome_Hash for easy hashing
 */
require_once LIB.'core/hash/hash.php';

/**
 * load request factory
 */
require_once LIB.'core/request/request.php';

/**
 * load response factory
 */
require_once LIB.'core/response/response.php';

/**
 * load URI class
 */
require_once LIB.'core/uri.php';

/**
 * load application interfaces
 */
require_once LIB.'core/application.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */
class Chrome_Application_Resource implements Chrome_Application_Interface
{
    /**
     * Contains the application class
     *
     * @var string
     */
    protected $_appClass = '';

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
     * @var \Chrome\Exception\Handler_Interface
     */
    private $_exceptionHandler = null;

    /**
     *
     * @var \Chrome\Exception\Configuration_Interface
     */
    private $_exceptionConfiguration = null;

    /**
     * Chrome_Front_Controller::getController()
     *
     * @return Chrome_Controller_Abstract
     */
    public function getController()
    {
        return null;
    }

    public function __construct(\Chrome\Exception\Handler_Interface $handler = null)
    {
        if($handler === null) {
            require_once LIB.'exception/default.php';
            $handler = new \Chrome\Exception\Handler\DefaultHandler();
        }

        $this->_exceptionHandler = $handler;
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    public function init()
    {
        $this->_exceptionConfiguration = new \Chrome\Exception\Configuration();
        $this->_exceptionConfiguration->setExceptionHandler($this->_exceptionHandler);
        $this->_exceptionConfiguration->setErrorHandler(new Chrome\Exception\Handler\DefaultErrorHandler());

        $viewContext = new Chrome_Context_View();
        $this->_modelContext = new Chrome_Context_Model();
        $this->_applicationContext = new Chrome_Context_Application();

        $this->_applicationContext->setViewContext($viewContext);
        $this->_applicationContext->setModelContext($this->_modelContext);

        // distinct which request is sent
        $requestFactory = new \Chrome\Request\Factory();
        // set up the available request handler

        require_once LIB . 'core/request/request/http.php';
        require_once LIB . 'core/response/response/http.php';

        $requestFactory->addRequestObject(new \Chrome\Request\Handler\HTTPHandler(new Chrome\Hash\Hash()));

        $reqHandler = $requestFactory->getRequest();
        $requestData = $requestFactory->getRequestDataObject();

        $this->_applicationContext->setRequestHandler($reqHandler);
        $session = $requestData->getSession();
        $cookie = $requestData->getCookie();

        $responseFactory = new Chrome_Response_Factory();

        $responseFactory->addResponseHandler(new Chrome_Response_Handler_HTTP($reqHandler));

        $response = $responseFactory->getResponse();
        $this->_applicationContext->setResponse($response);
    }

    public function setApplication($appClass)
    {
        $this->_appClass = $appClass;
    }

    public function execute()
    {
        try {
            if(!class_exists($this->_appClass, false)) {
                throw new \Chrome\Exception('Could not find application class '.$this->_appClass);
            }

            $class = new $this->_appClass($this);

            $class->execute();

        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    /**
     * setExceptionHandler()
     *
     * @param mixed $obj
     * @return void
     */
    public function setExceptionHandler(\Chrome\Exception\Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    /**
     * getExceptionHandler()
     *
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

    public function getExceptionConfiguration()
    {
        return $this->_exceptionConfiguration;
    }

    public function getDiContainer()
    {
        return null;
    }
}
