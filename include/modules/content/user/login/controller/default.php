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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 14:18:06] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login_Default extends Chrome_Controller_Content_Abstract
{
	protected function _initialize() {
	    $this->require = array('file' => array(CONTENT.'user/login/include.php', CONTENT.'user/login/view/default.php', CONTENT.'user/login/model.php'));
	}

    protected function _execute()
    {
        $this->form = Chrome_Form_Login::getInstance();

        $this->view = new Chrome_View_User_Login_Default($this);

        $this->model = new Chrome_Model_Login($this->form);

        if($this->model->isLoggedIn() == true) {
            $this->view->alreadyLoggedIn();
        } else {

            try {
                if($this->form->isSent()) {

                    if($this->form->isValid()) {

                        // try to log in
                        $this->model->login();

                        if($this->model->successfullyLoggedIn() === true) {
                            $this->view->successfullyLoggedIn();
                        } else {
                            $this->view->errorWhileLoggingIn();
                        }

                    } else {
                        $this->form->delete();
                        $this->form->create();
                        $this->view->formNotValid();
                    }

                } else {
                    $this->view->showForm();
                }
            } catch(Chrome_Exception $e) {
                $e->show($e);
            }
        }
        $this->view->render($this);
    }
}