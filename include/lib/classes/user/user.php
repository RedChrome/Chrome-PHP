<?php
if(CHROME_PHP !== true)
    die();

require_once 'login.php';
require_once 'model.php';
class Chrome_User
{
    private static $_instance = null;
    protected $_id = null;
    protected $_model = null;

    private function __construct()
    {
        $login = Chrome_User_Login::getInstance();

        if($login->isLoggedIn())
        {
            $this->_ID = $login->getID();
        } else
        {
            $this->_ID = 0;
        }

        $this->_model = new Chrome_Model_User();

        $this->_model->setDecorator(Chrome_Model_User_DB::getInstance());
    }

    public static function getInstance()
    {
        if(self::$_instance === null)
        {
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