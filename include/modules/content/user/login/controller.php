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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2012 14:53:02] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login extends Chrome_Controller_Content_Abstract
{
	protected function _initialize()
	{
	}


	protected function _execute()
	{
		if( Chrome_Request::getInstance()->getRequest() instanceof Chrome_Request_Handler_AJAX ) {
			require_once 'controller/ajax.php';
			$controller = new Chrome_Controller_Content_Login_AJAX();
		} else {
			require_once 'controller/default.php';
			$controller = new Chrome_Controller_Content_Login_Default();
		}


		/*
		if(isset($this->_GET['request'])) {
		$request = $this->_GET['request'];
		} else if(isset($this->_POST['request'])) {
		$request  =$this->_POST['request'];
		} else {
		$request = '';
		}

		switch($request) {

		case 'ajax': {
		require_once 'controller/ajax.php';
		$controller = new Chrome_Controller_Content_Login_AJAX();
		break;
		}

		default: {
		require_once 'controller/default.php';
		$controller = new Chrome_Controller_Content_Login_Default();
		}

		}*/

		$controller->execute();
	}

	public function getResponse()
	{
        if(Chrome_Request::getInstance()->getRequest() instanceof Chrome_Request_Handler_AJAX) {
            Chrome_Response::setResponseClass( 'ajax' );
        }

        return parent::getResponse();
        /*

		/**
		 * not good, but it works ;)
		 *
		if( isset( $_GET['request'] ) ) {
			$request = $_GET['request'];
		} else
			if( isset( $_POST['request'] ) ) {
				$request = $_POST['request'];
			} else {
				$request = '';
			}

			switch( $request ) {

				case 'ajax':
					{
						Chrome_Response::setResponseClass( 'ajax' );
						break;
					}

				default:
					{
						// do nothing special
					}

			}

		return parent::getResponse();
        */
	}
}
