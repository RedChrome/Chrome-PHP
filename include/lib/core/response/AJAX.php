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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.10.2012 00:18:25] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_AJAX extends Chrome_Response_Abstract
{
	private static $_instance = null;

	private $_body 		= array();

    public function __destruct()
    {
        $this->flush();
    }

	public function write($string)
	{
		$this->_body = array_merge($string, $this->_body);
	}

	public function flush()
	{
		$this->_printHeaders();

        // indeed $_body is an array, but if were using AJAX, then
        // there should be an output filter, which converts this array
        // in an string (maybe json?)
        if(is_array($this->_body)) {
            var_dump($this->_body);
        } else {
            echo $this->_body;
        }

		$this->_headers = array();
		$this->_body = null;
	}

	public function clear()
    {
		$this->_body = null;
	}

 	public function getBody()
    {
 		return $this->_body;
 	}

 	public function setBody($string)
    {
 		$this->_body = $string;
 	}

	public static function getInstance()
    {
		if(self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}