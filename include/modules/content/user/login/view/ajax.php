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
 * @package CHROME-PHP
 * @subpackage Chrome.User
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.06.2013 13:59:02] --> $
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 *
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

    protected function _setUp()
    {
        $this->setAjaxEnvironment();
    }

    public function render()
    {
        return $this->_views[0]->render();
    }

    public function alreadyLoggedIn()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Ajax_AlreadyLoggedIn', $this->_controller);
    }

    public function successfullyLoggedIn()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Ajax_SuccessfullyLoggedIn', $this->_controller);
    }

    public function formNotValid()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Ajax_FormNotValid', $this->_controller);
    }

    public function showForm()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Ajax_ShowForm', $this->_controller);
    }

    public function errorWhileLoggingIn()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Ajax_WrongPassword', $this->_controller);
    }
}
class Chrome_View_User_Ajax_AlreadyLoggedIn extends Chrome_View_Abstract
{

    public function render()
    {
        return array('success' => false,
                    'message' => 'Login failed:<br>Cannot re-login! You\'re already logged in!',
                    'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}
class Chrome_View_User_Ajax_SuccessfullyLoggedIn extends Chrome_View_Abstract
{

    public function render()
    {
        return array('success' => true, 'message' => 'Login successfull:<br>Logged In!', 'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}
class Chrome_View_User_Ajax_FormNotValid extends Chrome_View_Abstract
{

    public function render()
    {
        return array('success' => false,
                    'token' => $this->_controller->getForm()->getElement('login')->getOptions(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_TOKEN),
                    'message' => 'Login failed:<br>Form was malformed',
                    'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}
class Chrome_View_User_Ajax_ShowForm extends Chrome_View_Abstract
{

    public function render()
    {
        return array('success' => false,
                    'message' => 'Login failed:<br>Form is invalid, reload!' . var_export($this->_controller->getForm()->getErrors(), true),
                    'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}
class Chrome_View_User_Ajax_WrongPassword extends Chrome_View_Abstract
{

    public function render()
    {
        return array('success' => false,
                    'message' => 'Login failed:<br>Wrong password and/or username!',
                    'reloadDelay' => Chrome_View_User_Login_Ajax::CHROME_VIEW_USER_LOGIN_AJAX_MESSAGE_DELAY);
    }
}
