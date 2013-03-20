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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.03.2013 12:27:32] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

require_once 'http.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_Handler_JSON extends Chrome_Response_Handler_HTTP
{
	public function canHandle()
	{
	    if(parent::canHandle() === false) {
	       return false;
	    }

        $requestData = $this->_request->getRequestData();

		return (strtolower($requestData->getENVData('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest' OR strtolower($requestData->getGETData('respondAs')) === 'json');
	}

	public function getResponse()
	{
		return new Chrome_Response_JSON($this->_request->getRequestData()->getSERVERData('HTTP_PROTOCOL'));
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_JSON extends Chrome_Response_HTTP
{
    protected $_body = array();

	public function __destruct()
	{
        if(count($this->_body) > 0) {
		  $this->flush();
        }
	}

	public function write($array)
	{
	    if(!is_array($array)) {
	       $array = array($array);
	    }

		$this->_body = array_merge($array, $this->_body);
	}

	public function flush()
	{
		$this->_printHeaders();

        if(is_array($this->_body)) {
            echo json_encode($this->_body);
        } else {
            echo $this->_body;
        }

		$this->_headers = array();
		$this->_body = array();
	}

	public function clear()
    {
		$this->_body = array();
	}
}
