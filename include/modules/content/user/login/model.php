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
            $credential = $this->_form->get('credential');
            $stayLoggedIn = $this->_form->getSentData('stay_loggedin');


            Chrome_User_Login::getInstance()->login($credential, $password, $stayLoggedIn);

            $this->_loginSuccess = Chrome_User_Login::getInstance()->isLoggedIn();

        } catch(Chrome_Exception $e) {

            // we received neither password or credential
            // so we cannot continue
            // => login failed

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