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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.03.2013 16:02:40] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login extends Chrome_Controller_Module_Abstract
{
    protected $_controller;

	protected function _initialize()
	{
	}

	protected function _execute()
	{
		if( $this->_requestHandler instanceof Chrome_Request_Handler_AJAX ) {
			require_once 'controller/ajax.php';
			$this->_controller = new Chrome_Controller_Content_Login_AJAX($this->_applicationContext);
            $this->_controller->setExceptionHandler(new Chrome_Exception_Handler_JSON());
		} else {
			require_once 'controller/default.php';
			$this->_controller = new Chrome_Controller_Content_Login_Default($this->_applicationContext);
		}

		$this->_controller->execute();
	}

	public function getResponse()
	{
        if($this->_requestHandler instanceof Chrome_Request_Handler_AJAX) {
            Chrome_Response::setResponseClass( 'ajax' );
        }

        return parent::getResponse();
	}

    public function addViews(Chrome_Design_Renderable_Container_List_Interface $list)
    {
        $this->_controller->addViews($list);
    }
}
