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

class Request implements Request_Interface
{
    const REGEX_NAME = '/\w*/i';

    protected $_config = null;

    public function __construct(\Chrome_Config_Interface $config)
    {
        $this->_config = $config;
    }

    public function isNicknameValid($name)
    {
        $name = (string) $name;

        $length = strlen($name);

        if(empty($name) OR $this->_config->getConfig('user', 'name_max_length') < $length OR $this->_config->getConfig('user', 'name_min_length') > $length) {
            return false;
        }

        if(preg_match(self::REGEX_NAME, $name, $matches) == false OR $matches[0] !== $name) {
            return false;
        }
    }

    public function isPasswordValid($password)
    {
        $password = (string) $password;

        $length = strlen($password);

        if(empty($password) OR $this->_config->getConfig('user', 'password_max_length') < $length OR $this->_config->getConfig('user', 'password_min_length') > $length) {
            return false;
        }

        // TODO: check whether the password is strong enough
    }
}