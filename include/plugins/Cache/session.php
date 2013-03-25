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
 * @subpackage Chrome.Cache
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.03.2013 01:49:18] --> $
 */

interface Chrome_Cache_Option_Session_Interface extends Chrome_Cache_Option_Interface
{
    // todo finish this interface
}

/**
 *
 * @package CHROME-PHP
 * @subpackag Chrome.Cache
 */
class Chrome_Cache_Session implements Chrome_Cache_Interface
{
	protected $_session;

	protected $_namespace = null;

	public function __construct(Chrome_Session_Interface $session, $namespace)
	{
		$this->_namespace = $namespace;
		$this->_session = $session;
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
		// do nothing
	}

	public function has($name)
	{
		$cache =  $this->_session[$this->_namespace];

		return (isset($cache[$key]));
	}

    public function remove($name) {
        $cache = $this->_session[$this->_namespace];

        unset($cache[$name]);
        $this->_session[$this->_namespace] = $cache;
    }
}
