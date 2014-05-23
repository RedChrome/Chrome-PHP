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

use \Chrome\Helper\User\AuthenticationResolver_Interface;

class Login implements \Chrome\Interactor\Interactor_Interface
{
    protected $_auth = null;

    protected $_resolver = null;

    public function __construct(\Chrome\Authentication\Authentication_Interface $auth, AuthenticationResolver_Interface $resolver)
    {
        $this->_auth = $auth;
        $this->_resolver = $resolver;
    }

    public function login($identity, $credential, $autologin)
    {
        $id = $this->_resolver->resolveIdentity($identity);

        $authResource = new \Chrome\Authentication\Resource\Database($id, $credential, $autologin);

        $this->_auth->authenticate($authResource);
    }

    public function logout()
    {
        $this->_auth->deAuthenticate();
    }

    public function isLoggedIn()
    {
        $authContainer = $this->_auth->getAuthenticationDataContainer();

        if($authContainer === null) {
            return false;
        } else {
            return $authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER);
        }
    }
}
