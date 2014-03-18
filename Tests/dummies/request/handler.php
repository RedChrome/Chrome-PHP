<?php

require_once 'data.php';

class Chrome_Request_Handler_Dummy implements \Chrome\Request\Handler_Interface
{
    private $_reqData;

    public function canHandleRequest() {
       return true;
    }

    public function getRequestData() {
        if($this->_reqData === null) {
           $this->_reqData = new \Test\Chrome\Request\DummyData();
        }

        return $this->_reqData;
    }

    public function setRequestData(\Chrome\Request\Data_Interface $data) {
        $this->_reqData = $data;
    }
}