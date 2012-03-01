<?php

interface Chrome_User_Login_Interface
{
    public static function getInstance();

    public function isLoggedIn();

    public function checkIsLoggedIn();

    public function login($email, $password);

    public function logout();

    public function getID();
}

class Chrome_User_Login implements Chrome_User_Login_Interface
{
    private static $_instance = null;

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @return bool
     */
    public function login($id, $credential, $autoLogin = false) {

        $authenticate = Chrome_Authentication::getInstance();

        try {
            $authenticate->authenticate(new Chrome_Authentication_Resource_Database($id, $credential, $autoLogin));
        } catch(Chrome_Exception $e) {

        }
    }

    public function isLoggedIn() {



        $authenticate = Chrome_Authentication::getInstance();

        if($authenticate->isAuthenticated() == false) {
            return false;
        }

        $id = $authenticate->getAuthenticationID();

        // guest
        if($id != 0) {
            return true;
        }

        return false;

    }

    public function checkIsLoggedIn() {
        throw new Chrome_Exception('Not impelmented yet');
    }

    public function logout() {
        throw new Chrome_Exception('Not impelmented yet');
    }

    /**
     * @deprecated
     */
    public function getID() {
        throw new Chrome_Exception('Not impelmented yet');
    }




}










/*
class Chrome_User_Login implements Chrome_User_Login_Interface
{
    const CHROME_USER_LOGIN_COOKIE_NAMESPACE = 'CHROME_USER';

    private static $_instance = null;

    protected $_isLoggedIn = null;

    protected $_ID = null;

    protected $_model = null;

    private function __construct() {

        $this->_model = Chrome_Model_User::getInstance();

        if(!$this->isLoggedIn()) {
            $this->_ID = 0;
        }
    }

    public static function getInstance() {
        if(self::$_instance === null ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function isLoggedIn() {
        if($this->_isLoggedIn === null) {
            $this->checkIsLoggedIn();
        }

        return $this->_isLoggedIn;
    }

    public function checkIsLoggedIn() {

        $cookie = Chrome_Cookie::getInstance();

        $cookieContent = $cookie[self::CHROME_USER_LOGIN_COOKIE_NAMESPACE];

        if($cookieContent == null OR empty($cookieContent)) {
            $this->_isLoggedIn = false;
            return false;
        }

        $ID = $this->_model->getUserIDByToken($cookieContent);

        if($ID == null) {
            $this->_isLoggedIn = false;
            return false;
        }

        $session = Chrome_Session::getInstance();

        $sessionContent = $session[self::CHROME_USER_LOGIN_COOKIE_NAMESPACE];

        if($sessionContent == null OR empty($sessionContent)) {
            $this->renewToken($ID);
        } elseif($sessionContent != $cookieContent) {
            $this->_isLoggedIn = false;
            return false;
        }

        $this->_ID = $ID;

        $this->_isLoggedIn = true;
        return true;

    }

    public function login($email, $password) {

        if($this->_model->logUserIn($email, $password) === true) {

            $token = $this->_model->getUserToken();

            $cookie = Chrome_Cookie::getInstance();
            $cookie->setCookie(self::CHROME_USER_LOGIN_COOKIE_NAMESPACE, $token, Chrome_Cookie::CHROME_COOKIE_COOKIE_NO_EXPIRE, '/', '', false, true);

            Chrome_Session::set(self::CHROME_USER_LOGIN_COOKIE_NAMESPACE, $token);

            return true;
        } else {
            return false;
        }
    }

    public function renewToken($id = null) {

        $_id = null;

        if($id == null) {
            $_id = ($this->_ID !== null) ? $this->_ID : null;
        } else {
            $_id = $id;
        }

        if($_id == null) {
            return false;
        }

        $this->_model->renewUserToken($_id);

        $cookie = Chrome_Cookie::getInstance();
        $cookie->setCookie(self::CHROME_USER_LOGIN_COOKIE_NAMESPACE, $this->_model->getUserToken(), Chrome_Cookie::CHROME_COOKIE_COOKIE_NO_EXPIRE, '/', '', false, true);

    }

    public function logout() {
        $cookie->setCookie(self::CHROME_USER_LOGIN_COOKIE_NAMESPACE, null);
        Chrome_Session::set(self::CHROME_USER_LOGIN_COOKIE_NAMESPACE, null);
        $this->_isLoggedIn = false;
    }

    public function getID() {
        return $this->_ID;
    }
}*/