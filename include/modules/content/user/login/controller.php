<?php

class Chrome_Controller_Content_Login extends Chrome_Controller_Content_Abstract
{
	protected function _initialize() {
	}


    protected function _execute()
    {
        if(isset($this->_GET['request'])) {
            $request = $this->_GET['request'];
        } else if(isset($this->_POST['request'])) {
            $request  =$this->_POST['request'];
        } else {
            $request = '';
        }

        switch($request) {

            case 'ajax': {
                require_once 'controller/ajax.php';
                $controller = new Chrome_Controller_Content_Login_AJAX();
                break;
            }

            default: {
                 require_once 'controller/default.php';
                 $controller = new Chrome_Controller_Content_Login_Default();
            }

        }

        $controller->execute();
    }

    public function getResponse() {

        /**
         * not good, but it works ;)
         */
        if(isset($_GET['request'])) {
            $request = $_GET['request'];
        } else if(isset($_POST['request'])) {
            $request  =$_POST['request'];
        } else {
            $request = '';
        }

        switch($request) {

            case 'ajax': {
                Chrome_Response::setResponseClass('ajax');
                break;
            }

            default: {
                // do nothing special
            }

        }

        return parent::getResponse();
    }
}