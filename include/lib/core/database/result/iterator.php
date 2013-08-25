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

if(CHROME_PHP !== true)
    die();
class Chrome_Database_Result_Iterator extends Chrome_Database_Result_Abstract implements Iterator
{
    protected $_adapter = null;

    protected $_iteratorPointer = 0;

    protected $_iteratorArray = array();

    protected $_iteratorMaxPosition = -1;

    public function __sleep()
    {
        $this->_adapter = new Chrome_Database_Adapter_Cache($this);
        return array('_adapter');
    }

    public function isEmpty()
    {
        return $this->_adapter->isEmpty();
    }

    public function hasNext()
    {
        return isset($this->_iteratorArray[$this->_iteratorPointer+1]);
    }

    public function getAffectedRows()
    {
        return $this->_adapter->getAffectedRows();
    }

    public function getNext()
    {
         $this->next();
         if($this->valid()) {
            return $this->current();
         }
         return null;
    }

    public function current()
    {
        return $this->_iteratorArray[$this->_iteratorPointer];
    }

    public function key()
    {
        return $this->_iteratorPointer;
    }

    public function next()
    {
        ++$this->_iteratorPointer;

        // if rewind was called, only add a next result if last position + 1 == current position
        if($this->_iteratorMaxPosition + 1 == $this->_iteratorPointer)
        {
            $data = $this->_adapter->getNext();

            if($data === false or $data === null)
            {
                return;
            }

            $this->_iteratorArray[$this->_iteratorPointer] = $data;

            $this->_iteratorMaxPosition = $this->_iteratorPointer;
        }

        #return $this->_iteratorArray[$this->_iteratorPointer];
    }

    public function rewind()
    {
        $this->_iteratorPointer = -1;
        // on first time, we need to call next() to start iterating
        if($this->_iteratorMaxPosition == -1)
        {
            $this->_iteratorPointer = -1;
            $this->next();
        }
    }

    public function valid()
    {
        return isset($this->_iteratorArray[$this->_iteratorPointer]);
    }
}
