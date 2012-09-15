<?php

class Chrome_Model_Login extends Chrome_Model_Form_Abstract
{
    protected $_loginSuccess = false;

    public function __construct($form = null) {

        if($form != null) {
            $this->setForm($form);
        }
    }

    public function login() {

        try {

            $password = $this->_form->get('password');
            $identity = $this->_form->get('identity');
            $stayLoggedIn = $this->_form->getSentData('stay_loggedin');

            $authenticate = Chrome_Authentication::getInstance();

            $authenticate->authenticate(new Chrome_Authentication_Resource_Database($identity, $password, $stayLoggedIn));

            $this->_loginSuccess = $authenticate->isUser();

        } catch(Chrome_Exception $e) {

            $this->_loginSuccess = false;
        }

    }

    public function successfullyLoggedIn() {
        return $this->_loginSuccess;
    }

    public function isLoggedIn() {
        return Chrome_User_Login::getInstance()->isLoggedIn();
    }
}