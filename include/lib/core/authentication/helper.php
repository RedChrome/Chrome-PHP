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
 * @subpackage Chrome.Authentication
 */

namespace Chrome\Helper\Authentication;

interface Creation_Interface
{
    public function createAuthentication($password, $passwordSalt);
}

class Creation implements Creation_Interface
{
    protected $_auth = null;

    public function __construct(\Chrome\Authentication\Authentication_Interface $auth)
    {
        $this->_auth = $auth;
    }

    public function createAuthentication($identity, $password, $passwordSalt)
    {
        $createResource = new \Chrome\Authentication\Resource\Create_Database($identity, $password, $passwordSalt);

        $this->_auth->createAuthentication($createResource);

        return $createResource->getID();
    }
}
