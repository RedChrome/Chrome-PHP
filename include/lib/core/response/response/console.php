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
class ConsoleHandler implements Handler_Interface
{
    protected $_request = null;

    public function __construct(\Chrome\Request\Handler_Interface $requestHandler)
    {
        $this->_request = $requestHandler;
    }

    public function canHandle()
    {
        return ($this->_request instanceof \Chrome\Request\Handler\ConsoleHandler);
    }

    public function getResponse()
    {
        return new Console();
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Console implements Response_Interface
{
    protected $_body = '';

    public function write($mixed)
    {
        $this->_body = $mixed;
    }

    public function flush()
    {
        echo $this->_body;
        $this->clear();
    }

    public function clear()
    {
        $this->_body = '';
    }

    public function setBody($mixed)
    {
        $this->_body = $mixed;
    }

    public function getBody()
    {
        return $this->_body;
    }
}
