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
 * Interface of a query result
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Result_Interface extends Chrome_Database_Adapter_Result_Interface
{
    /**
     * Sets a adapter which executed a query.
     * Note: the $adapter might not be an instance of Chrome_Database_Adapter_Abstract. So you
     * can add as an adapter an instance of Chrome_Database_Result_Interface. So your result object
     * can have as an adapter another result object (might be usefull).
     *
     *
     * @param Chrome_Database_Adapter_Result_Interface $adapter
     * @return void
     */
    public function setAdapter(Chrome_Database_Adapter_Result_Interface $adapter);

    /**
     * Returns the adapter set by {@see setAdapter())}
     *
     * @return Chrome_Database_Adapter_Result_Interface
     */
    public function getAdapter();

    /**
     * Returns true if the resultset has a next row. False else
     *
     * @return bool
     */
    public function hasNext();

    /**
     * This should return a new instance of Chrome_Database_Result_Interface and should NOT
     * delete the current state of this object. So the user can still access data from this
     * result object while using the new one given by this method.
     *
     * @return Chrome_Database_Result_Interface
     */
    public function clear();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
abstract class Chrome_Database_Result_Abstract implements Chrome_Database_Result_Interface
{
    /**
     * instance of an adapter
     *
     * @var Chrome_Database_Adapter_Result_Interface
     */
    protected $_adapter = null;

    /**
     * Sets an adapter
     *
     * @param Chrome_Database_Adapter_Result_Interface $adapter
     * @return void
     */
    public function setAdapter(Chrome_Database_Adapter_Result_Interface $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Returns an adapter
     *
     * @return Chrome_Database_Adapter_Result_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Returns the last inserted id
     *
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->_adapter->getLastInsertId();
    }

    /**
     * Clears this result object by returning a new one. So the old one
     * can still be accessed.
     *
     * @return Chrome_Database_Result_Abstract
     */
    public function clear()
    {
        $class  = get_class($this);
        $return = new $class();
        return $return;
    }
}
