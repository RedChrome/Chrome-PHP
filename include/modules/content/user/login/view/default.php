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
 * @subpackage Chrome.Content.User
 */

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Content.User
 */
class Chrome_View_User_Login_Default extends Chrome_View_Strategy_Abstract
{

    public function alreadyLoggedIn()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Default_AlreadyLoggedIn', $this->_controller);
    }

    public function successfullyLoggedIn()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Default_SuccessfullyLoggedIn', $this->_controller);
    }

    public function formNotValid()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_User_Default_FormNotValid', $this->_controller);
    }

    public function showForm(Chrome_View_Form_Element_Factory_Interface $viewFormElementFactory)
    {
        $viewForm = Chrome_View_Form_Login::getInstance($this->_controller->getForm(), $this->_viewContext);
        $viewForm->setElementFactory($viewFormElementFactory);
        $this->_views[] = new Chrome_View_Form_Renderer_Template_Login_Content($viewForm);
    }

    public function errorWhileLoggingIn()
    {
        $viewForm = Chrome_View_Form_Login::getInstance($this->_controller->getForm(), $this->_viewContext);
        $viewForm->setElementFactory($viewFormElementFactory);
        $this->_views[] = new Chrome_View_Form_Renderer_Template_Login_Content($viewForm);
    }
}

class Chrome_View_User_Default_AlreadyLoggedIn extends Chrome_View_Abstract
{

    public function render()
    {
        $lang = $this->_viewContext->getLocalization()->getTranslate();

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/already_logged_in');
        $template->assign('LANG', $lang);
        #new Chrome_Language('modules/content/user/login'));
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();
    }
}

class Chrome_View_User_Default_SuccessfullyLoggedIn extends Chrome_View_Abstract
{

    public function render()
    {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/already_logged_in');
        return $template->render();
    }
}
class Chrome_View_User_Default_FormNotValid extends Chrome_View_Abstract
{
    public function render()
    {
        return 'form was not valid!';
    }
}

class Chrome_View_Form_Renderer_Template_Login_Content extends Chrome_View_Form_Renderer_Template_Abstract
{
    protected function _getTemplate()
    {
        $template = new Chrome_Template();

        $lang = $this->_viewContext->getLocalization()->getTranslate();

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/form_log_in');
        $template->assign('LANG', $lang);

        return $template;
    }
}
