<?php

require_once 'data.php';

class Chrome_Request_Handler_Dummy implements Chrome_Request_Handler_Interface
{
    private $_reqData;

    public function canHandleRequest() {
       return true;
    }

    public function getRequestData() {
        if($this->_reqData === null) {
           $this->_reqData = new Chrome_Request_Data_Dummy();
        }

        return $this->_reqData;
    }

    public function setRequestData(Chrome_Request_Data_Interface $data) {
        $this->_reqData = $data;
    }
}