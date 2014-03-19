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
 */

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_User_Login_Page extends \Chrome\Controller\ModuleAbstract
{

    protected function _initialize()
    {
        $this->_require = array('file' => array(CONTENT . 'user/login/include.php', CONTENT . 'user/login/view/default.php'));
    }

    protected function _execute()
    {
        $this->_form = Chrome_Form_Login::getInstance($this->_applicationContext);

        // the login form, will be the first one in Content
        $viewForm = Chrome_View_Form_Login::getInstance($this->_form, $this->_applicationContext->getViewContext());
        $viewForm->setElementFactory($this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Element\Factory\Default'));
        $this->_view = new Chrome_View_Form_Renderer_Template_Login_Content($viewForm);
    }
}