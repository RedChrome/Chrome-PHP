<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Module.User
 */
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