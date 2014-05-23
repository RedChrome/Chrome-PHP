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
namespace Chrome\Helper\User\AuthenticationResolver;

use Chrome\Helper\User\AuthenticationResolver_Interface;

require_once LIB.'modules/user/interfaces/helpers.php';

class Email implements AuthenticationResolver_Interface
{
    protected $_userModel = null;

    public function __construct(\Chrome\Model\User\User_Interface $user)
    {
        $this->_userModel = $user;
    }

    public function resolveIdentity($identity)
    {
        return $this->_userModel->getAuthenticationIdByEmail($identity);
    }
}