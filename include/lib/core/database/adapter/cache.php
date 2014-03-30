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
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 */

/**
 * Adapter to cache result sets using Chrome_Cache_Interface
 *
 * E.g.
 * <code>
 * $cacheAdapter = new Chrome_Database_Adapter_Cache($result);
 * $result->setAdapter($cacheAdapter);
 *
 * // this will work now
 * $cache->cache($result);
 * </code>
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Chrome_Database_Adapter_Cache implements Chrome_Database_Adapter_Interface, Serializable
{
    protected $_data = array();

    protected $_numRows = 0;

    public function __construct(Chrome_Database_Result_Iterator $result)
    {
        $result->rewind();

        foreach($result as $data)
        {
            $this->_data[] = $data;
        }

        $result->rewind();

        $this->_numRows = count($this->_data);
    }

    public function serialize()
    {
        return serialize($this->_data);
    }

    public function unserialize($serialized)
    {
        $this->_data = unserialize($serialized);
        $this->_numRows = count($this->_data);
    }

    private function _notSupportedMethod()
    {
        throw new \Chrome\Exception('Adapter is just a cache. You cannot use db functionality from a cached object!');
    }

    public function query($query)
    {
        $this->_notSupportedMethod();
    }

    public function escape($data)
    {
        $this->_notSupportedMethod();
    }

    public function setConnection(\Chrome\Database\Connection\Connection_Interface $connection)
    {
        $this->_notSupportedMethod();
    }

    public function getConnection()
    {
        $this->_notSupportedMethod();
    }

    public function clear()
    {

    }

    public function prepareStatement($statement)
    {
        return $statement;
    }

    public function getErrorMessage()
    {
        $this->_notSupportedMethod();
    }

    public function getErrorCode()
    {
        $this->_notSupportedMethod();
    }

    public function getNext()
    {
        return array_shift($this->_data);
    }

    public function getAffectedRows()
    {
        return $this->_numRows;
    }

    public function isEmpty()
    {
        return $this->_numRows === 0;
    }

    public function getLastInsertId()
    {
        $this->_notSupportedMethod();
    }
}