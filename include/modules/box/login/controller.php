<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.User
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.02.2012 18:56:57] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
   die();

/**
 * Class for controlling login box and user menu
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Box_Login extends Chrome_Controller_Box_Abstract
{
    /**
     * initialize the controller
     *
     * @return void
     */
    protected function _initialize()
    {
        // just load some files... model, view and form(include.php)
        $this->require = array('file' => array(CONTENT.'user/login/model.php', VIEW.'box/login/view.php', CONTENT.'user/login/include.php'));
    }

    /**
     * Run the Controller
     *
     * @return void
     */
    protected function _execute()
    {
        // setting up

        $this->model = new Chrome_Model_Login(null);
        $this->view = new Chrome_View_Box_Login($this);
        $this->design = Chrome_Design_Composite_Left_Box::getInstance()->getComposite();

        // if the user is logged in, then show the user menu
        if($this->model->isLoggedIn() === true) {

            $this->view->showUserMenu();

        // else create the form and display it
        } else {
            $this->form = Chrome_Form_Login::getInstance();

            // form was sent
            if($this->form->isSent()) {
                // here we can do what we want
            }

            $this->view->showLoginForm();
        }

        // create the output
        $this->view->render();
    }
}
