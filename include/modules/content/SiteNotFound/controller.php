<?php

namespace Chrome\Controller;

require_once 'view.php';

class SiteNotFound extends \Chrome\Controller\ModuleAbstract
{

    protected function _initialize()
    {
        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_Content_SiteNotFound');
    }

    protected function _execute()
    {
        if($this->_applicationContext->getResponse() instanceof \Chrome\Response\Handler\HTTPResponse_Interface) {
            $this->_applicationContext->getResponse()->setStatus('404 Not Found');
        }
    }
}
