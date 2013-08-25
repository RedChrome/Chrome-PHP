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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [30.03.2013 13:09:30] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_Handler_HTTP implements Chrome_Response_Handler_Interface
{
    protected $_request = null;

    public function __construct(Chrome_Request_Handler_Interface $requestHandler)
    {
        $this->_request = $requestHandler;
    }

    public function canHandle()
    {
        return ($this->_request instanceof Chrome_Request_Handler_HTTP);
    }

    public function getResponse()
    {
        return new Chrome_Response_HTTP($this->_request->getRequestData()->getSERVERData('HTTP_PROTOCOL'));
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface Chrome_Response_HTTP_Interface extends Chrome_Response_Interface
{
    public function setStatus($status);

    public function getStatus();

    public function addHeader($name, $value);

    public function getHeader($name);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_HTTP implements Chrome_Response_Interface
{
    protected $_status = '200 OK';
    protected $_headers = array('Content-Type' => 'text/html');
    protected $_body = '';
    protected $_serverProtocol = '';

    public function __construct($serverProtocol)
    {
        $this->_serverProtocol = $serverProtocol;
    }

    public function __destruct()
    {
        $this->flush();
    }

    public function write($string)
    {
        $this->_body .= $string;
    }

    public function flush()
    {
        $this->_printHeaders();

        echo $this->_body;
        $this->_headers = null;
        $this->_body = null;
    }

    public function clear()
    {
        $this->_body = null;
    }

    protected function _printHeaders()
    {
        if(!headers_sent()) {
            header($this->_serverProtocol.' '.$this->_status);

            if(empty($this->_headers)) {
                return;
            }

            foreach($this->_headers as $key => $value) {
                header($key.': '.$value);
            }
        }
    }

    public function addHeader($name, $value)
    {
        $this->_headers[$name] = $value;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function getHeader($name)
    {
        if(isset($this->_headers[$name])) {
            return $this->_headers[$name];
        }

        return null;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setBody($string)
    {
        $this->_body = $string;
    }
}
