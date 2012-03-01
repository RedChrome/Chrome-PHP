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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [17.02.2012 00:38:38] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();


/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_Box_Login extends Chrome_View_Abstract
{
    /**
     * preConstructor
     * @return void
     */
    protected function _preConstruct()
    {
        // add the .js file for ajax support
        $this->addJS('javascript/modules/box/login.js');
    }

    /**
     * @return void
     */
    public function render()
    {
        // override parent, do nothing
    }

    /**
     * sets the actual rendered view
     *
     * @return void
     */
    public function showUserMenu()
    {
        // add view
        $this->_controller->getDesign()->addView(new Chrome_View_Box_LoggedIn($this->_controller));
    }

    /**
     * @return void
     */
    public function showLoginForm()
    {
        // add view
        $this->_controller->getDesign()->addView(new Chrome_View_Box_Form_Login($this->_controller));
    }
}

/**
 *
 * This is the View for the User Menu
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_Box_LoggedIn extends Chrome_View_Abstract
{
    public function _postConstruct()
    {
        $this->setViewTitle('User Menu');
    }

    public function render()
    {
        return 'Eingeloggt...<br>Hier kommt dann das User Menu hin ;)';
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_Box_Form_Login extends Chrome_View_Abstract
{
    public function _postConstruct()
    {
        // set the title of the login box
        $this->setViewTitle('Login');
    }

    public function render()
    {
        // create template with the form
        $template = new Chrome_Template();
        $template->assignTemplate('modules/box/login/form_log_in');
        // assigning form and language
        $template->assign('FORM', $this->_controller->getForm());
        $template->assign('LANG', new Chrome_Language('modules/content/user/login'));
        // return the rendered template
        return $template->render();
    }
}
