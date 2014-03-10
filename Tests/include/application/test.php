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
 * @package   CHROME-PHP
 * @subpackage Chrome.Test
 */
require_once APPLICATION . 'default.php';

class Chrome_Application_Test extends Chrome_Application_Default
{
    protected $_model = null;

    protected function _initDatabase()
    {
        $this->_applicationContext->setModelContext($this->_model);
        $this->_modelContext = $this->_model;
        // overwrite this method, thus there is no database connection to product database created
    }

    public function setModelContext(Chrome_Context_Model_Interface $modelContext)
    {
        $this->_model = $modelContext;
    }

    protected function _initRequestAndResponse()
    {
        // distinct which request is sent
        $requestFactory = new Chrome_Request_Factory();
        // set up the available request handler

        $hash = new \Chrome\Hash\Hash();
        $requestFactory->addRequestObject(new Chrome_Request_Handler_Console($hash));

        $reqHandler = $requestFactory->getRequest();
        $this->_applicationContext->setRequestHandler($requestFactory->getRequest());

        $responseFactory = new Chrome_Response_Factory();

        $responseFactory->addResponseHandler(new Chrome_Response_Handler_Console($reqHandler));

        $response = $responseFactory->getResponse();
        $this->_applicationContext->setResponse($response);
    }

    protected function _initDiContainer()
    {
        parent::_initDiContainer();
        $this->_diContainer->getHandler('closure')->add('\Chrome\Linker\Linker_Interface', function ($c) {
            return new \Chrome\Linker\Console\Linker($c->get('\Chrome\Resource\Model_Interface'));
        });
    }
}