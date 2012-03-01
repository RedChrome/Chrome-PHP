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
 * @subpackage Chrome.Response
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 14:43:47] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */ 
class Chrome_Response_HTTP extends Chrome_Response_Abstract
{
	private static $_instance = null;

	private $_status	= '200 OK';
	private $_headers 	= array();
	private $_body 		= '';

    public function __destruct()
    {
        $this->flush();
    }

 	public function setStatus($status)
	{
		$this->_status = $status;
 	}

	public function addHeader($name, $value)
	{
		$this->_headers[$name] = $value;
	}

	public function write($string)
	{
		$this->_body .= $string;
	}

	public function flush()
	{
	    if(!headers_sent()) {
    		header('HTTP/1.0 '.$this->_status);
            
            if(!empty($this->_headers))
        		foreach($this->_headers AS $key => $value) {
        			header($key.': '.$value);
        		}
        }

		echo $this->_body;
		$this->_headers = null;
		$this->_body = null;
	}

	public function clear() {
		$this->_body = null;
	}

 	public function getBody() {
 		return $this->_body;
 	}

 	public function setBody($string) {
 		$this->_body = $string;
 	}

	public static function getInstance() {
		if(self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}