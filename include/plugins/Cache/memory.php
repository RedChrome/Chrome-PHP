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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */

namespace Chrome\Cache;

/**
 * A cache which only uses the internal php memory.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Memory implements Cache_Interface
{
    protected $_cache = array();

    public function set($key, $data)
    {
        $this->_cache[$key] = $data;
    }

    public function get($key)
    {
        return (isset($this->_cache[$key])) ? $this->_cache[$key] : null;
    }

    public function has($key)
    {
        return isset($this->_cache[$key]);
    }

    public function remove($key)
    {
        unset($this->_cache[$key]);
    }

    public function flush()
    {
        // do nothing
    }

    public function clear()
    {
        $this->_cache = array();
    }
}