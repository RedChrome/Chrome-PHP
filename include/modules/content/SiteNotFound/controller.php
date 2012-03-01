<?php

require_once 'view.php';

class Chrome_Controller_SiteNotFound extends Chrome_Controller_Content_Abstract
{
    protected function _initialize()
    {
        $this->view = new Chrome_View_Content_SiteNotFound($this);
    }

    protected function _execute()
    {
        $this->view->render();
    }
}
