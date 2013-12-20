<?php

namespace \Chrome\Validator\User;

class Email extends \Chrome_Validator
{
    protected $_appContext = null;

    public function __construct(Chrome_Context_Application_Interface $appContext)
    {
        $this->_appContext = $appContext;
    }

    protected function _validate()
    {
        if(!$this->_validateByValidator(new Chrome_Validator_Email_Default())) {
            return false;
        }

        if(!$this->_validateByValidator(new Chrome_Validator_Email_Blacklist($this->_appContext->getConfig()))) {
            return false;
        }

        return true;
    }
}

namespace \Chrome\Validator\User\Registration;

class Email extends \Chrome_Validator
{
    protected $_appContext = null;

    public function __construct(Chrome_Context_Application_Interface $appContext)
    {
        $this->_appContext = $appContext;
    }

    protected function _validate()
    {
        if(!$this->_validateByValidator(new Email($this->_appContext))) {
            return false;
        }

        $emailExists = new \Chrome_Validator_Email_Exists($interface);

        if(!$this->_validateByValidator($emailExists, false)) {
            return false;
        }
    }
}