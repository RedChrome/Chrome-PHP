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
 * @subpackage Chrome.Request
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [17.09.2012 22:42:15] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
class Chrome_Request_Handler_HTTP implements Chrome_Request_Handler_Interface
{
    public function __construct() {

    }

	public function canHandleRequest()
	{
		return true;
	}

	public function getRequestData()
	{
		return Chrome_Request_Data_HTTP::getInstance();
	}
}

class Chrome_Request_Data_HTTP extends Chrome_Request_Data_Abstract
{
	private static $_instance;
    //todo: handle special chars such as ä
	protected function __construct()
	{
        parent::__construct();
	}

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}
