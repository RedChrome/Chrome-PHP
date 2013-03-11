<?php

require_once 'data.php';

class Chrome_Request_Handler_Dummy implements Chrome_Request_Handler_Interface
{
    private $_reqData;

	public function canHandleRequest() {
	   return true;
	}

	public function getRequestData() {
        return $this->_reqData;
	}

    public function __construct(Chrome_Cookie_Interface $cookie, Chrome_Session_Interface $session) {

    }

    public function setRequestData(Chrome_Request_Data_Interface $data) {
        $this->_reqData = $data;
    }
}