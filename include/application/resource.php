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
require_once LIB.'core/URI.php';

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
        return null;
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    public function init()
    {
        $viewContext = new Chrome_Context_View();
        $this->_modelContext = new Chrome_Context_Model();
        $this->_applicationContext = new Chrome_Context_Application();

        $this->_applicationContext->setViewContext($viewContext);
        $this->_applicationContext->setModelContext($this->_modelContext);

        // distinct which request is sent
        $requestFactory = new Chrome_Request_Factory();
        // set up the available request handler

        require_once LIB . 'core/request/request/http.php';
        require_once LIB . 'core/response/response/http.php';

        $requestFactory->addRequestObject(new Chrome_Request_Handler_HTTP());

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

    public function execute()
    {
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
