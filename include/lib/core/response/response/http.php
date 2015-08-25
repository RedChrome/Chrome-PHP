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
 */

namespace Chrome\Response\Handler;

use \Chrome\Response\Handler_Interface;
use \Chrome\Response\Response_Interface;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class HTTPHandler implements Handler_Interface
{
    protected $_request = null;

    public function __construct(\Chrome\Request\Handler_Interface $requestHandler)
    {
        $this->_request = $requestHandler;
    }

    public function canHandle()
    {
        return ($this->_request instanceof \Chrome\Request\Handler\HTTPHandler);
    }

    public function getResponse()
    {
        return new \Chrome\Response\HTTP($this->_request->getRequestData()->getSERVERData('SERVER_PROTOCOL'));
    }
}

namespace Chrome\Response;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface HTTPResponse_Interface extends Response_Interface
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
class HTTP implements HTTPResponse_Interface
{
    protected $_status = '200 OK';
    protected $_headers = array('Content-Type' => 'text/html', 'X-Powered-By' => 'PHP');
    protected $_body = '';
    protected $_serverProtocol = '';

    protected $_supportedProtocols = array('HTTP/1.1', 'HTTP/1.0');

    public function __construct($serverProtocol)
    {
        if(!in_array($serverProtocol, $this->_supportedProtocols)) {
            $this->_status = '505 HTTP Version not supported';
            $this->_serverProtocol = $this->_supportedProtocols[0];
        } else {
            $this->_serverProtocol = $serverProtocol;
        }
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
