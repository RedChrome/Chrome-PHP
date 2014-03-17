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
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */

namespace Chrome\Cache\Option;

/**
 * An option interface for the session cache
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
interface Session_Interface extends Option_Interface
{
    /**
     * Sets the namespace for session. must be non-empty
     *
     * @param string $namespace
     */
    public function setNamespace($namespace);

    /**
     * @return string
     */
    public function getNamespace();

    /**
     * Sets the session instance
     *
     * @param Chrome_Session_Interface $session
     */
    public function setSession(\Chrome_Session_Interface $session);

    /**
     * Returns the session instance
     *
     * @return Chrome_Session_Interface
     */
    public function getSession();
}

/**
 * Default implementation of the option session interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Session implements Session_Interface
{
    protected $_namespace = '';

    protected $_session = null;

    public function __construct(\Chrome_Session_Interface $session, $namespace)
    {
        $this->setSession($session);
        $this->setNamespace($namespace);
    }

    public function setNamespace($namespace)
    {
        if(!is_string($namespace) and !empty($namespace))
        {
            throw new \Chrome\InvalidArgumentException('Argument $namespace must be a non-empty string');
        }

        $this->_namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }

    public function setSession(\Chrome_Session_Interface $session)
    {
        $this->_session = $session;
    }

    public function getSession()
    {
        return $this->_session;
    }
}

namespace Chrome\Cache;

/**
 * A cache using the user's session
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Chrome_Cache_Session implements Cache_Interface
{
    protected $_session;

    protected $_namespace = null;

    public function __construct(\Chrome\Cache\Option\Session_Interface $option)
    {
        $this->_namespace = $option->getNamespace();
        $this->_session = $option->getSession();
        $this->_session[$namespace] = array();
    }

    public function clear()
    {
        unset($this->_session[$this->_namespace]);
    }

    public function set($key, $data)
    {
        $this->_session[$this->_namespace] = array_merge($this->_session[$this->_namespace], array($key => $data));
    }

    public function get($key)
    {
        $cache = $this->_session[$this->_namespace];

        return (isset($cache[$key])) ? $cache[$key] : null;
    }

    public function flush()
    {
        // do nothing, every cache entry is saved instantly
    }

    public function has($key)
    {
        $cache = $this->_session[$this->_namespace];

        return (isset($cache[$key]));
    }

    public function remove($name)
    {
        $cache = $this->_session[$this->_namespace];

        unset($cache[$name]);
        $this->_session[$this->_namespace] = $cache;
    }
}
