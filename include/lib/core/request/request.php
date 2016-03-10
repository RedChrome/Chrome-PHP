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
 */

namespace Chrome\Request;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
interface RequestContext_Interface
{
    /**
     * Returns the request data object
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest();

    /**
     * Sets a request object.
     *
     * @param \Psr\Http\Message\ServerRequestInterface the new request
     */
    public function setRequest(\Psr\Http\Message\ServerRequestInterface $request);

    /**
     * @return \Chrome\Request\Session_Interface
     */
    public function getSession();

    /**
     * @return \Chrome\Request\Cookie_Interface
     */
    public function getCookie();
}

class Context implements RequestContext_Interface
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $_request = null;

    /**
     * @var \Chrome\Request\Cookie_Interface
     */
    protected $_cookie  = null;

    /**
     * @var \Chrome\Request\Session_Interface
     */
    protected $_session = null;

    public function __construct(\Psr\Http\Message\ServerRequestInterface $request, \Chrome\Request\Cookie_Interface $cookie = null, \Chrome\Request\Session_Interface $session = null)
    {
        $this->_request = $request;
        $this->_cookie  = $cookie;
        $this->_session = $session;
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public function setRequest(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $this->_request = $request;
    }

    public function getCookie()
    {
        return $this->_cookie;
    }

    public function getSession()
    {
        return $this->_session;
    }
}

require_once 'cookie.php';
require_once 'session.php';