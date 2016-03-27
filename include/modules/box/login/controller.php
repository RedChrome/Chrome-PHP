<?php

/**
 * CHROME-PHP CMS
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
 * @package    CHROME-PHP
 * @subpackage Chrome.Controller.Box
 */

namespace Chrome\Controller\Box;

use \Chrome\Controller\AbstractModule;

class Login extends AbstractModule
{
    /**
     * Run the Controller
     *
     * @return void
     */
    protected function _execute()
    {
        $this->_view = $this->_applicationContext->getDiContainer()->get('\Chrome\View\User\UserMenu');

        $login = $this->_applicationContext->getDiContainer()->get('\Chrome\Interactor\User\Login');
        #$login = new \Chrome\Interactor\User\Login($this->_applicationContext->getAuthentication());

        // if the user is logged in, then show the user menu
        if($login->isLoggedIn() === true) {
            $this->_view->displayUserMenu();
        // else show the login form
        } else {
            // we dont have to care about the form, since all the action
            // will be sent to the content/user/login/controller.
            $this->_view->displayLogin();
        }

    }
}
