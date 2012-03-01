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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.02.2012 19:32:25] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_User_Login_Ajax extends Chrome_View_Abstract
{
    /**
     * How long the messages are shown in the browser, in msec
     *
     * @var int
     */
    const CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY = 5000;

    protected function _preConstruct() {
        // the script has to know that this view handles ajax request. This sets an special style and enables json encoding of objects
        $this->setAjaxEnvironment();
    }
    public function alreadyLoggedIn() {
        Chrome_Design_Composite_Laconic::getInstance()->getComposite()->addView(new Chrome_View_User_Ajax_AlreadyLoggedIn($this->_controller));
    }

    public function successfullyLoggedIn() {
        Chrome_Design_Composite_Laconic::getInstance()->getComposite()->addView(new Chrome_View_User_Ajax_successfullyLoggedIn($this->_controller));
    }

    public function formNotValid() {
        Chrome_Design_Composite_Laconic::getInstance()->getComposite()->addView(new Chrome_View_User_Ajax_FormNotValid($this->_controller));
    }

    public function showForm() {
        Chrome_Design_Composite_Laconic::getInstance()->getComposite()->addView(new Chrome_View_User_Ajax_ShowForm($this->_controller));
    }

    public function errorWhileLoggingIn() {
        Chrome_Design_Composite_Laconic::getInstance()->getComposite()->addView(new Chrome_View_User_Ajax_WrongPassword($this->_controller));
    }
}

class Chrome_View_User_Ajax_AlreadyLoggedIn extends Chrome_View_Abstract {
    public function render() {
        return array('success' => false, 'message' => 'Login failed:<br>Cannot re-login! You\'re already logged in!', 'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}

class Chrome_View_User_Ajax_successfullyLoggedIn extends Chrome_View_Abstract {
    public function render() {
        return array('success' => true, 'message' => 'Login successfull:<br>Logged In!', 'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}

class Chrome_View_User_Ajax_FormNotValid extends Chrome_View_Abstract {
    public function render() {
        return array('success' => false, 'token' => $this->_controller->getForm()->getElement('login')->getOptions(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_TOKEN), 'message' => 'Login failed:<br>Form was malformed', 'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}

class Chrome_View_User_Ajax_ShowForm extends Chrome_View_Abstract {
    public function render() {
        return array('success' => false, 'message' => 'Login failed:<br>Form is invalid, reload!', 'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}

class Chrome_View_User_Ajax_WrongPassword extends Chrome_View_Abstract{

    public function render() {
        return array('success' => false, 'message' => 'Login failed:<br>Wrong password and/or username!', 'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}
