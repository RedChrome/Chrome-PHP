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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [28.02.2012 12:31:00] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login_AJAX extends Chrome_Controller_Content_Abstract
{
	protected function _initialize() {

        // this is important!! This encodes the rendered data from the views with json
	    $this->filter['postprocessor'][] = new Chrome_Filter_JSON();

        $this->require = array('file' => array(CONTENT.'user/login/include.php', CONTENT.'user/login/view/ajax.php', CONTENT.'user/login/model.php'));
	}

    protected function _execute()
    {
        $this->form = Chrome_Form_Login::getInstance();
        // after the user has sent this form, it is not immediately deleted
        // so the user may send another login?

        $this->view = new Chrome_View_User_Login_Ajax($this);

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
                // todo: set proper exception handler, so that it is dispalyed as json
                $e->show($e);
            }
        }
        $this->view->render();
    }
}