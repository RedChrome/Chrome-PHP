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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.11.2012 01:00:03] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Result_Assoc extends Chrome_Database_Result_Abstract implements ArrayAccess
{
    protected $_adapter = null;

    protected $_nextResult = null;

    protected $_currentResult = array();

    public function isEmpty()
    {
        return $this->_adapter->isEmpty();
    }

    public function hasNext()
    {
        $this->_nextResult = $this->getNext();
        return ($this->_nextResult !== false);
    }

    public function getNext()
    {
        if($this->_nextResult !== null) {
            $result = $this->_nextResult;
            $this->_nextResult = null;
            $this->_currentResult = $result;
            return $result;
        }

        $this->_currentResult = $this->_adapter->getNext();

        return $this->_currentResult;
    }

    public function setAdapter(Chrome_Database_Adapter_Result_Interface $adapter)
    {
        $this->_adapter = $adapter;
    }

    public function getAffectedRows()
    {
        return $this->_adapter->getAffectedRows();
    }


    /*
        ArrayAccess methods
    */
    public function offsetExists($offset) {
        return isset($this->_currentResult[$offset]);
    }

    public function offsetGet($offset) {
        return $this->_currentResult[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->_currentResult[$offset] = $value;
    }

    public function offsetUnset($offset) {
        $this->_currentResult[$offset] = null;
    }
}
