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
