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

class Chrome_Database_Result_Iterator extends Chrome_Database_Result_Abstract implements Iterator, Serializable
{
    protected $_adapter = null;

    protected $_iteratorPointer = 0;

    protected $_iteratorArray = array();

    protected $_iteratorMaxPosition = -1;

    protected $_rewinded = false;

    public function serialize()
    {
        $this->_adapter = new Chrome_Database_Adapter_Cache($this);
        return serialize($this->_adapter);
    }

    public function unserialize($serialized)
    {
        $this->_adapter = unserialize($serialized);
    }

    public function isEmpty()
    {
        return $this->_adapter->isEmpty();
    }

    public function getAffectedRows()
    {
        return $this->_adapter->getAffectedRows();
    }

    public function rewind()
    {
        $this->_rewinded = true;

        if($this->_iteratorMaxPosition === -1)
        {
            $this->_iteratorPointer = -1;
            $this->next();
        }

        $this->_iteratorPointer = 0;
    }

    public function hasNext()
    {
        if($this->_iteratorMaxPosition > $this->_iteratorPointer + 1) {
            return true;
        }

        $this->next();
        $hasNext = $this->valid();
        --$this->_iteratorPointer;
        return $hasNext;
    }

    public function getNext()
    {
        if($this->_rewinded === true AND $this->_iteratorPointer === 0) {
            $this->_iteratorPointer = -1;
        }

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
        $this->_rewinded = false;

        if($this->_iteratorMaxPosition > $this->_iteratorPointer) {
            ++$this->_iteratorPointer;
        } else {

            ++$this->_iteratorMaxPosition;
            $this->_iteratorPointer = $this->_iteratorMaxPosition;

            $data = $this->_adapter->getNext();

            if($data === false or $data === null)
            {
                return;
            }

            $this->_iteratorArray[$this->_iteratorMaxPosition] = $data;
        }
    }

    public function valid()
    {
        return isset($this->_iteratorArray[$this->_iteratorPointer]);
    }
}