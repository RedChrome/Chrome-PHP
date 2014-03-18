<?php

class Chrome_Request_Data_Dummy implements \Chrome\Request\Data_Interface
{
    public $_GET, $_POST, $_SERVER, $_REQUEST, $_ENV, $_FILES, $_COOKIEData;

    public $_session, $_cookie;

    public function __construct(\Chrome\Request\Cookie_Interface $cookie = null, \Chrome\Request\Session_Interface $session = null)
    {
        if($cookie === null) {
           $cookie = new \Test\Chrome\Request\Cookie\Dummy();
        }

        if($session === null) {
            $session = new \Test\Chrome\Request\Session\Dummy();
        }

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
            'ENV' => $this->_ENV,
            'COOKIE' => $this);
    }

    public function getGETData($key = null)
    {
        if($key === null) {
            return $this->_GET;
        } else
            if(isset($this->_GET[$key])) {
                return $this->_GET[$key];
            }
    }


    public function getPOSTData($key = null) {
       if($key === null) {
            return $this->_POST;
        } else
            if(isset($this->_POST[$key])) {
                return $this->_POST[$key];
            }
    }

    public function getSERVERData($key = null) {
       if($key === null) {
            return $this->_SERVER;
        } else
            if(isset($this->_SERVER[$key])) {
                return $this->_SERVER[$key];
            }
    }

    public function getFILESData($key = null) {
       if($key === null) {
            return $this->_FILES;
        } else
            if(isset($this->_FILES[$key])) {
                return $this->_FILES[$key];
            }
    }

    public function getREQUESTData($key = null) {
       if($key === null) {
            return $this->_REQUEST;
        } else
            if(isset($this->_REQUEST[$key])) {
                return $this->_REQUEST[$key];
            }
    }

    public function getENVData($key = null) {
       if($key === null) {
            return $this->_ENV;
        } else
            if(isset($this->_ENV[$key])) {
                return $this->_ENV[$key];
            }
    }

    public function getCOOKIEData($key = null) {
        if($key === null) {
            return $this->_COOKIEData;
        } else
            if(isset($this->_COOKIEData[$key])) {
                return $this->_COOKIEData[$key];
            }
    }

    public function setGETData(array $array) {

    }

    public function setPOSTData(array $array) {

    }

    public function setFILESData(array $array) {

    }

    public function setENVData(array $array) {

    }

    public function setSERVERData(array $array) {

    }

    public function setCOOKIEData(array $array) {

    }

    /**
     * @return \Chrome\Request\Session_Interface
     */
    public function getSession() {
       return $this->_session;
    }

    /**
     * @return \Chrome\Request\Cookie_Interface
     */
    public function getCookie() {
       return $this->_cookie;
    }


}
