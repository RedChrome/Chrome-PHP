<?php

namespace Chrome\Controller;

use \Chrome\Controller\AbstractModule;

require_once 'view.php';

class RouteNotFound extends AbstractModule
{
    protected function _initialize()
    {
        $this->_view = $this->_applicationContext->getDiContainer()->get('\Chrome\View\RouteNotFound\RouteNotFound');
    }

    protected function _execute()
    {
        if($this->_applicationContext->getResponse() instanceof \Chrome\Response\HTTP) {

            $this->_applicationContext->getResponse()->setStatus('404 Not Found');
        }
    }
}
