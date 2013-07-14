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
 * @subpackage Chrome.User
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 19:03:12] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
   die();

/**
 * Class for controlling login box
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Box_Login extends Chrome_Controller_Module_Abstract
{
    /**
     * initialize the controller
     *
     * @return void
     */
    protected function _initialize()
    {
        // just load some files... model, view and form(include.php)
        $this->_require = array('file' => array(CONTENT.'user/login/model.php', VIEW.'box/login/view.php', CONTENT.'user/login/include.php'));
    }

    /**
     * Run the Controller
     *
     * @return void
     */
    protected function _execute()
    {
        // setting up

        $this->_model = new Chrome_Model_Login($this->_applicationContext, null);
        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_Box_Login', $this);

        // if the user is logged in, then show the user menu
        if($this->_model->isLoggedIn() === true) {

            $this->_view->showUserMenu();

        // else create the form and display it
        } else {
            $this->_form = Chrome_Form_Login::getInstance($this->_applicationContext);

            // form was sent
            if($this->_form->isSent()) {
                // here we can do what we want
            }

            $this->_view->showLoginForm();
        }

    }
}
