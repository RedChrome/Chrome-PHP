<?php

require_once 'view.php';

class Chrome_Controller_News extends Chrome_Controller_Content_Abstract
{
    protected function _initialize()
    {
        $this->_view = new Chrome_View_Content_News($this);
    }

    protected function _execute()
    {
        switch(Chrome_Request::getInstance()->getRequestDataObject()->getGET('action')) {

            case 'show': {
                echo 'show';
                break;
            }

            default: {
                echo 'default';
            }

        }

        $this->_view->render($this);
    }
}
