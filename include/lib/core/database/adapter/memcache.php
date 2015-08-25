<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Database
 */

namespace Chrome\Database\Adapter;

/**
 *
 * @todo add doc
 */
interface Memcache_Interface
{
    public function set($key, $value, $flag = true, $expire = 0);

    public function get($key);

    public function delete($key);

    public function clear();

    public function has($key);
}

/**
 * Default adapter for Memcache database
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Memcache extends AbstractAdapter implements Memcache_Interface
{
    public function isEmpty()
    {
        return null;
    }

    public function set($key, $value, $flag = true, $expire)
    {
        \memcache_set($this->_connection, $key, $value, $flag, $expire);
    }

    public function get($key)
    {
        return \memcache_get($this->_connection, $key);
    }

    public function delete($key)
    {
        return \memcache_delete($this->_connection, $key);
    }

    public function clear()
    {
        return \memcache_flush($this->_connection);
    }

    public function has($key)
    {
        // yeah, this is not really okay...
        // using $this->set('key', false) will yield a $this->has('key') === false
        // but, it should return true :/. Since this is just a cache, this should be
        // covered by the called itself...
        // return memcache_get($this->_connection, $key) !== false;


        $flag = false;
        \memcache_get($this->_connection, $key, $flag);

        // if the item was found, then the flag will be altered!
        return $flag !== false;
    }

    public function query($query)
    {
        return null;
    }

    public function getNext()
    {
        return false;
    }

    public function escape($data)
    {
        return null;
    }

    public function getAffectedRows()
    {
        return false;
    }

    public function getErrorCode()
    {
        return null;
    }

    public function getErrorMessage()
    {
        return null;
    }

    public function getLastInsertId()
    {
        return null;
    }
}
