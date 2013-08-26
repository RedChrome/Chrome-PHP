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

if(CHROME_PHP !== true)
    die();

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
class Chrome_Database_Adapter_Cache implements Chrome_Database_Adapter_Interface
{
    protected $_data = array();

    protected $_numRows = 0;

    public function __construct(Chrome_Database_Result_Iterator $result)
    {
        $result->rewind();
        while($result->hasNext()) {
            $this->_data[] = $result->getNext();
        }
    }

    public function __sleep()
    {
        return array('_data');
    }

    public function __wakeup()
    {
        $this->_numRows = count($this->_data);
    }

    private function _notSupportedMethod()
    {
        throw new Chrome_Exception();
    }

    public function query($query)
    {
        $this->_notSupportedMethod();
    }

    public function escape($data)
    {
        $this->_notSupportedMethod();
    }

    public function setConnection(Chrome_Database_Connection_Interface $connection)
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