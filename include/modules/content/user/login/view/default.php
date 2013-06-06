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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.06.2013 13:56:48] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_User_Login_Default extends Chrome_View_Strategy_Abstract
{
    public function alreadyLoggedIn() {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Default_AlreadyLoggedIn', $this->_controller);
    }

    public function successfullyLoggedIn() {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Default_successfullyLoggedIn', $this->_controller);
    }

    public function formNotValid() {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Default_FormNotValid', $this->_controller);
    }

    public function showForm() {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Default_ShowForm', $this->_controller);
    }

    public function errorWhileLoggingIn() {
        $this->_views[] = $this->_viewContext->getFactory()->build(' hrome_View_User_Default_WrongPassword', $this->_controller);
    }
}

class Chrome_View_User_Default_AlreadyLoggedIn extends Chrome_View_Abstract {
    public function render() {

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/already_logged_in');
        $template->assign('LANG', new Chrome_Language('modules/content/user/login'));
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();
        //return 'you`re already logged in!';
    }
}

class Chrome_View_User_Default_successfullyLoggedIn extends Chrome_View_Abstract {
    public function render() {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/already_logged_in');
        return $template->render();

    }
}

class Chrome_View_User_Default_FormNotValid extends Chrome_View_Abstract {
    public function render() {
        return 'form was not valid!';
    }
}

class Chrome_View_User_Default_ShowForm extends Chrome_View_Abstract {
    public function render() {
        $lang = new Chrome_Language('modules/content/user/login');

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/form_log_in');
        $template->assign('LANG', $lang);
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();
    }
}

class Chrome_View_User_Default_WrongPassword extends Chrome_View_Abstract{
    public function render() {
        $lang = new Chrome_Language('modules/content/user/login');

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/form_log_in');
        $template->assign('LANG', $lang);
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();
    }
}