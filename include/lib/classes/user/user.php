<?php

if(CHROME_PHP !== true)
    die();

require_once 'login.php';
require_once 'model.php';

class Chrome_User
{
    private static $_instance = null;

    protected $_ID = null;

    protected $_model = null;

    private function __construct()
    {
        $login = Chrome_User_Login::getInstance();

        if($login->isLoggedIn()) {
            $this->_ID = $login->getID();
        } else {
            $this->_ID = 0;
        }

        $this->_model = new Chrome_Model_User();

        $this->_model->setDecorator(Chrome_Model_User_DB::getInstance());

    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getID()
    {
        return $this->_ID;
    }

    public function getModel()
    {
        return $this->_model;
    }
}


/*
abstract class Chrome_User
{
protected $_id = null;

protected $_name = '';

protected $_nickname = '';

protected $_rights = null;

protected $_model = null;

public function __construct() {

}

public function getId() {
return $this->_id;
}

public function getName() {
return $this->_name;
}

public function getNickname() {
return $this->_nickname;
}

public function getRights() {
return $this->_rights;
}

public function getModel() {

if($this->_model !== null) {
return $this->_model;
}

if($this->_id !== null) {
$this->_model = new Chrome_User_Model($this->_id);
}

return null;
}
}*/
