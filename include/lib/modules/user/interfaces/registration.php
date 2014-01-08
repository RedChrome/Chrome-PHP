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
namespace Chrome\Model\User;

interface Registration_Interface
{
    public function hasEmail($email);

    public function discardRegistrationRequestByActivationKey($activationKey);

    /**
     * @param string $activationKey
     * @return \Chrome\Model\User\Registration\Request_Interface
     */
    public function getRegistrationRequestByActivationKey($activationKey);
}

namespace Chrome\Model\User\Registration;

interface Request_Interface
{
    public function getEmail();

    public function getName();

    public function getId();

    public function getPassword();

    public function getPasswordSalt();

    public function getActivationKey();

    public function getTime();
}

class Request
{
    protected $_email = '';

    protected $_name = '';

    protected $_id = null;

    protected $_password = '';

    protected $_passwordSalt = '';

    protected $_activationKey = '';

    public function getTime()
    {
        return $this->_time;
    }

    public function setTime($time)
    {
        $this->_time = (int) $time;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = (string) $email;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword($password)
    {
        $this->_password = (string) $password;
        return $this;
    }

    public function getPasswordSalt()
    {
        return $this->_passwordSalt;
    }

    public function setPasswordSalt($passwordSalt)
    {
        $this->_passwordSalt = (string) $passwordSalt;
        return $this;
    }

    public function getActivationKey()
    {
        return $this->_activationKey;
    }

    public function setActivationKey($activationKey)
    {
        $this->_activationKey = (string) $activationKey;
        return $this;
    }
}