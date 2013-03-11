<?php

class Chrome_Request_Data_Dummy implements Chrome_Request_Data_Interface
{
	public $_GET, $_POST, $_SERVER, $_REQUEST, $_ENV, $_FILES;

    public function __construct(Chrome_Cookie_Interface $cookie, Chrome_Session_Interface $session)
	{
	    $this->_cookie = $cookie;
        $this->_session = $session;
	}

	public function getData()
	{
		return array(
			'SERVER' => $this->_SERVER,
			'GET' => $this->_GET,
			'POST' => $this->_POST,
			'FILES' => $this->_FILES,
			'REQUEST' => $this->_REQUEST,
			'ENV' => $this->_ENV);
	}

	public function getGET($key = null)
	{
		if($key === null) {
			return $this->_GET;
		} else
			if(isset($this->_GET[$key])) {
				return $this->_GET[$key];
			}
	}


	public function getPOST($key = null) {
	   if($key === null) {
			return $this->_POST;
		} else
			if(isset($this->_POST[$key])) {
				return $this->_POST[$key];
			}
	}

	public function getSERVER($key = null) {
	   if($key === null) {
			return $this->_SERVER;
		} else
			if(isset($this->_SERVER[$key])) {
				return $this->_SERVER[$key];
			}
	}

	public function getFILES($key = null) {
	   if($key === null) {
			return $this->_FILES;
		} else
			if(isset($this->_FILES[$key])) {
				return $this->_FILES[$key];
			}
	}

	public function getREQUEST($key = null) {
	   if($key === null) {
			return $this->_REQUEST;
		} else
			if(isset($this->_REQUEST[$key])) {
				return $this->_REQUEST[$key];
			}
	}

	public function getENV($key = null) {
	   if($key === null) {
			return $this->_ENV;
		} else
			if(isset($this->_ENV[$key])) {
				return $this->_ENV[$key];
			}
	}

	public function setGET(array $array) {

	}

	public function setPOST(array $array) {

	}

	public function setFILES(array $array) {

	}

	public function setENV(array $array) {

	}

	public function setSERVER(array $array) {

	}

	/**
	 * @return Chrome_Session_Interface
	 */
	public function getSession() {
	   return $this->_session;
	}

	/**
	 * @return Chrome_Cookie_Interface
	 */
	public function getCookie() {
	   return $this->_cookie;
	}


}
