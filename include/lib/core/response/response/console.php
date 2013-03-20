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
 * @subpackage Chrome.Response
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.03.2013 12:38:45] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_Handler_Console implements Chrome_Response_Handler_Interface
{
	protected $_request = null;

	public function __construct(Chrome_Request_Handler_Interface $requestHandler)
	{
		$this->_request = $requestHandler;
	}

	public function canHandle()
	{
        return ($this->_request instanceof Chrome_Request_Handler_Console);
	}

	public function getResponse()
	{
		return new Chrome_Response_Console();
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_Console implements Chrome_Response_Interface
{
    protected $_body = '';

    public function write($mixed) {
        $this->_body = $mixed;
    }

	public function flush() {
	   echo $this->_body;
       $this->clear();
	}

	public function clear() {
	   $this->_body = '';
	}

	public function setBody($mixed) {
	   $this->_body = $mixed;
	}

	public function getBody() {
	   return $this->_body;
	}
}
