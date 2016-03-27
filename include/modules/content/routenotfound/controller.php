<?php

namespace Chrome\Controller;

use \Chrome\Controller\AbstractModule;

class RouteNotFound extends AbstractModule
{
    protected function _execute()
    {
        $this->_view = $this->_applicationContext->getDiContainer()->get('\Chrome\View\RouteNotFound\RouteNotFound');

        if($this->_applicationContext->getResponse() instanceof \Chrome\Response\HTTP) {

            $this->_applicationContext->getResponse()->setStatus('404 Not Found');
        }
    }
}
