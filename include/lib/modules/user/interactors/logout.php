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
 * @subpackage Chrome.Interactor.User
 */

namespace Chrome\Interactor\User;

use Chrome\Redirection\Redirection_Interface;

class Logout implements \Chrome\Interactor\Interactor_Interface
{
    protected $_login = null;
    protected $_redirect = null;

    public function __construct(Login $login, Redirection_Interface $redirect)
    {
        $this->_login = $login;
        $this->_redirect = $redirect;
    }

    public function doLogout()
    {
        $this->_login->logout();

        // redirect
        $this->_redirect->redirectToPreviousPage();
    }
}