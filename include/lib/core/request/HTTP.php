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
 * @subpackage Chrome.Request
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 14:44:59] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */ 
class Chrome_Request_HTTP extends Chrome_Request_Abstract
{
    private $_parameters;

    private $_GET;

    private $_POST;

    private $_HEADER;

    private $_FILES;

    private $_COOKIE;
    
    private $_data;

    public function __construct()
    {
        $this->setParams();
    }

    public function setParams()
    {
        $this->_parameters = &$_REQUEST;
        $this->_GET = &$_GET;
        $this->_POST = &$_POST;
        $this->_HEADER = &$_SERVER;
        $this->_FILES = &$_FILES;
        $this->_COOKIE = &$_COOKIE;
        
        $this->_data = array('GET' => &$this->_GET, 'POST' => &$this->_POST, 'HEADER' => &$this->_HEADER, 'FILES' => &$this->_FILES, 'COOKIE' => &$this->_COOKIE);
    }

    public function getPrameterNames()
    {
        return array_keys($this->_parameters);
    }

    public function issetParameter($name)
    {
        return isset($this->_parameters[$name]);
    }

    public function getParameter($name)
    {
        return isset($this->_parameters[$name]) ? $this->_parameters[$name] : null;
    }

    public function issetHeader($name)
    {
        return isset($this->_HEADER[$name]);
    }

    public function getHeader($name)
    {
        return isset($this->_HEADER[$name]) ? $this->_HEADER[$name] : null;
    }

    public function getGET($name)
    {
        return isset($this->_GET[$name]) ? $this->_GET[$name] : null;
    }

    public function getPOST($name)
    {
        return isset($this->_POST[$name]) ? $this->_POST[$name] : null;
    }

    public function issetGET($name)
    {
        return isset($this->_GET[$name]);
    }

    public function issetPOST($name)
    {
        return isset($this->_POST[$name]);
    }

    public function &getGETParameter()
    {
        return $this->_GET;
    }

    public function &getPOSTParameter()
    {
        return $this->_POST;
    }

    public function setPOSTParameter($name, $data)
    {
        $this->_POST[$name] = $data;
    }

    public function setGETParameter($name, $data)
    {
        $this->_GET[$name] = $data;
    }

    public function &getParameters()
    {
        return $this->_data;
    }
}