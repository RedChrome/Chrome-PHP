<?php

class Chrome_Model_User_DB extends Chrome_Model_DB_Abstract
{
    private static $_instance = null;

    protected $_token;

    protected function __construct() {
        if($this->_escaper === null)
            $this->_escaper = Chrome_DB_Interface_Factory::factory('interface')->initDefaultConnection();
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getUserIDByToken($token) {

        $dbObj = Chrome_DB_Interface_Factory::factory('interface')
					->select(array('id'))
					->from('user')
					->where('token = "'.$this->_escape(base64_decode($token)).'"')
					->limit(0, 1)
					->execute();

        $data = $dbObj->next();

        return $data['id'];
    }

    public function logUserIn($email, $password) {

        $dbObj = Chrome_DB_Interface_Factory::factory('interface')
                    ->select(array('password', 'pw_salt'))
                    ->from('user')
                    ->where('email = "'.$this->_escape($email).'"')
                    ->limit(0, 1)
                    ->execute();

        $data = $dbObj->next();

        // wrong email address
        if(!isset($data['password'])) {
            return false;
        }

        $hash = Chrome_Hash::getInstance();

        $hashedPassword = $hash->hash($password, $data['pw_salt']);

        // wrong password
        if($hashedPassword != $data['password']) {
            return false;
        }

        $token = $hash->randomChars(32);

        $this->_token = base64_encode($token);

        $dbObj->clear()
                ->update('user')
                ->set(array('token' => $this->_escape($token), 'llogin' => CHROME_TIME, 'ip' => $_SERVER['REMOTE_ADDR']))
                ->where('email = "'.$this->_escape($email).'"')
                ->limit(0, 1)
                ->execute();

        return true;
    }

    public function getUserToken() {
        return $this->_token;
    }


    public function renewUserToken($id) {

        $id = (int) $id;

        $token = Chrome_Hash::getInstance()->randomChars(32);

        $this->_token = base64_encode($token);

        $dbObj = Chrome_DB_Interface_Factory::factory('interface')
                ->update('user')
                ->set(array('token' => $this->_escape($token), 'llogin' => CHROME_TIME, 'ip' => $_SERVER['REMOTE_ADDR']))
                ->where('id = "'.$id.'"')
                ->limit(0, 1)
                ->execute();

        $cookie = Chrome_Cookie::getInstance();
    }

    public function getUserNameByID($id) {

        $id = (int) $id;

        $dbObj = Chrome_DB_Interface_Factory::factory('interface')
            ->select('name')
            ->from('user')
            ->where('id = "'.$id.'"')
            ->limit(0, 1)
            ->execute();

        $data = $dbObj->next();

        return $data['name'];
    }

    public function getUserNameByEmail($email) {

        $dbObj = Chrome_DB_Interface_Factory::factory('interface')
            ->select('name')
            ->from('user')
            ->where('email = "'.$this->_escape($email).'"')
            ->limit(0, 1)
            ->execute();

        $data = $dbObj->next();

        return $data['name'];
    }
}

class Chrome_Model_User extends Chrome_Model_Decorator_Abstract
{
    private static $_instance = null;

    private $_getUserNameByIDCache = array();

    private $_getUserNameByEmailCache = array();

    private $_languageObj = null;

    public static function getInstance() {
        if(self::$_instance == null) {
            self::$_instance = new self(Chrome_Model_User_DB::getInstance());
        }

        return self::$_instance;
    }

    public function getUserNameByID($id) {

        if(!isset($this->_getUserNameByIDCache[$id])) {
            $this->_getUserNameByIDCache[$id] = $this->_decorator->getUserNameByID($id);
        }

        return $this->_getUserNameByIDCache[$id];
    }

    public function getUserNameByEmail($email) {
        if(!isset($this->_getUserNameByEmailCache[$email])) {
            $this->_getUserNameByEmailCache[$email] = $this->_decorator->_getUserNameByEmailCache($id);
        }

        return $this->_getUserNameByEmailCache[$email];
    }


    public function getLanguageObject() {

        if($this->_languageObj === null) {
            $this->_languageObj = new Chrome_Language('classes/user/user');
        }

        return $this->_languageObj;
    }
}