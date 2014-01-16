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
namespace Chrome\Helper\User;

class Email implements Email_Interface
{
    protected $_userModel = null;
    protected $_registrationModel = null;

    public function __construct(\Chrome\Model\User\User_Interface $userModel, \Chrome\Model\User\Registration_Interface $registrationModel)
    {
        $this->_userModel = $userModel;
        $this->_registrationModel = $registrationModel;
    }

    public function emailIsUsed($email)
    {
        if($this->_userModel->hasEmail($email) OR $this->_registrationModel->hasEmail($email)) {
            return true;
        }

        return false;
    }
}