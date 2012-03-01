<?php

require_once 'view.php';

class Chrome_Controller_News extends Chrome_Controller_Content_Abstract
{
    protected function _initialize()
    {
        $this->view = new Chrome_View_Content_News($this);
    }

    protected function _execute()
    {        
        
        
        switch($this->_GET['action']) {
            
            case 'show': {
                echo 'show';
                break;
            }
            
            default: {
                echo 'default';
            }
        }
        
        
      
        $this->view->render();
    }
}
