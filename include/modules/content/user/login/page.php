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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 19:03:44] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_User_Login_Page extends Chrome_Controller_Module_Abstract
{
    protected function _initialize() {
        $this->_require = array('file' => array(CONTENT.'user/login/include.php', CONTENT.'user/login/view/default.php'));
	}

    protected function _execute() {

        $this->_form = Chrome_Form_Login::getInstance($this->_applicationContext);

        // the login form, will be the first one in Content
        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_User_Default_ShowForm', $this);
    }
}