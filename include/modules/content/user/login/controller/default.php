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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.03.2013 16:09:26] --> $
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
	    $this->_require = array('file' => array(CONTENT.'user/login/include.php', CONTENT.'user/login/view/default.php', CONTENT.'user/login/model.php'));
	}

    protected function _execute()
    {
        $this->_form = Chrome_Form_Login::getInstance($this->_requestHandler);

        $this->_view = new Chrome_View_User_Login_Default($this);

        $this->_model = new Chrome_Model_Login($this->_applicationContext, $this->_form);

        if($this->_model->isLoggedIn() == true) {
            $this->_view->alreadyLoggedIn();
        } else {

            try {
                if($this->_form->isSent()) {

                    if($this->_form->isValid()) {

                        // try to log in
                        $this->_model->login();

                        if($this->_model->successfullyLoggedIn() === true) {
                            $this->_view->successfullyLoggedIn();
                        } else {
                            $this->_view->errorWhileLoggingIn();
                        }

                    } else {
                        $this->_form->delete();
                        $this->_form->create();
                        $this->_view->formNotValid();
                    }

                } else {
                    $this->_view->showForm();
                }
            } catch(Chrome_Exception $e) {
                $e->show($e);
            }
        }
    }
}