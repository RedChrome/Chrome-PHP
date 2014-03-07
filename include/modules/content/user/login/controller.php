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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.06.2013 14:26:58] --> $
 * @author     Alexander Book
 */

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login extends Chrome_Controller_Module_Abstract
{
    protected $_controller;

    public function __construct(Chrome_Context_Application_Interface $appContext, \Chrome\Interactor\User\Login $interactor)
    {
        parent::__construct($appContext);
        $this->_interactor = $interactor;
    }

    protected function _initialize()
    {
    }

    protected function _execute()
    {
        if($this->_applicationContext->getResponse() instanceof Chrome_Response_JSON) {
            require_once 'controller/ajax.php';
            $this->_controller = new Chrome_Controller_Content_Login_AJAX($this->_applicationContext, $this->_interactor);
            $this->_controller->setExceptionHandler(new Chrome_Exception_Handler_JSON());
        } else {
            require_once 'controller/default.php';
            $this->_controller = new Chrome_Controller_Content_Login_Default($this->_applicationContext, $this->_interactor);
            $this->_controller->setExceptionHandler($this->_exceptionHandler);
        }

        $this->_controller->execute();
    }

    public function getView()
    {
        return $this->_controller->getView();
    }
}
