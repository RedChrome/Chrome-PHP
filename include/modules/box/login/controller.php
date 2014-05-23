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
 * @subpackage Chrome.Controller
 */

namespace Chrome\Controller\Box;

use \Chrome\Controller\AbstractModule;

/**
 * Class for controlling login box
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Login extends AbstractModule
{
    /**
     * initialize the controller
     *
     * @return void
     */
    protected function _initialize()
    {
        // just load some files... view
        $this->_require = array('file' => array(VIEW.'box/login/view.php', MODULE.'content/user/login/include.php'));
    }

    /**
     * Run the Controller
     *
     * @return void
     */
    protected function _execute()
    {
        // setting up

        //$this->_model = new Chrome_Model_Login($this->_applicationContext, null);
        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_Box_Login', $this);

        $login = $this->_applicationContext->getDiContainer()->get('\Chrome\Interactor\User\Login_Interface');
        #$login = new \Chrome\Interactor\User\Login($this->_applicationContext->getAuthentication());

        // if the user is logged in, then show the user menu
        if($login->isLoggedIn() === true) {

            $this->_view->showUserMenu();

        // else create the form and display it
        } else {

            $this->_form = \Chrome_Form_Login::getInstance($this->_applicationContext);

            $this->_view->showLoginForm($this->_form, $this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Element\Factory\Default'));
        }

    }
}
