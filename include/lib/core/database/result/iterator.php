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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [11.03.2013 14:20:31] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Result_Iterator extends Chrome_Database_Result_Abstract implements Iterator
{
    protected $_adapter = null;

    protected $_iteratorPointer = 0;
    protected $_iteratorArray = array();
    protected $_iteratorLastPosition = 0;

    public function isEmpty()
    {
        return $this->_adapter->isEmpty();
    }

    public function hasNext()
    {
        return $this->valid();
    }

    public function getAffectedRows()
    {
        return $this->_adapter->getAffectedRows();
    }

    public function getNext()
    {
        return $this->next();
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
        if($this->_iteratorLastPosition + 1 == $this->_iteratorPointer) {

            $this->_iteratorLastPosition                   = $this->_iteratorPointer;

            $this->_iteratorArray[$this->_iteratorPointer] = $this->_adapter->getNext();
        }

        return $this->current();
    }

    public function rewind()
    {
        $this->_iteratorPointer = 0;
        // on first time, we need to call next() to start iterating
        if($this->_iteratorLastPosition == 0) {
            $this->next();
        }
    }

    public function valid()
    {
        return (isset($this->_iteratorArray[$this->_iteratorPointer]) AND $this->_iteratorArray[$this->_iteratorPointer] !== false AND $this->_iteratorArray[$this->_iteratorPointer] !== null);
    }
}
