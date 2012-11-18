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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.11.2012 18:30:03] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

interface Chrome_Database_Interface_Interface
{
    public function __construct(Chrome_Database_Adapter_Interface $adapter, Chrome_Database_Result_Interface $result);

    public function getResult();

    public function getAdapter();

    public function execute(array $parameters = array());

    public function query($query);

    public function setParameters(array $array, $escape = true);

    public function setParameter($key, $value, $escape = true);

    public function escape($data);

    public function getStatement();

    public function clear();
}

abstract class Chrome_Database_Interface_Abstract implements Chrome_Database_Interface_Interface
{
    protected $_query = null;

    protected $_adapter = null;

    protected $_result = null;

    protected $_params = array();

    protected $_sentQuery = null;

    public function __construct(Chrome_Database_Adapter_Interface $adapter, Chrome_Database_Result_Interface $result)
    {
        $this->_adapter = $adapter;
        $this->_result = $result;
    }

    public function execute(array $parameters = array())
    {
        if(count($parameters) >= 1) {
            $this->setParameters($parameters, true);
        }

        if($this->_query !== null) {
            return $this->query($this->_query);
        } else {
            throw new Chrome_Exception('Cannot execute an sql statement if no statement was set!');
        }
    }

    public function query($query)
    {
        if($this->_sentQuery !== null) {
            $this->clear();
        }

        $query = $this->_prepareStatement($query);

        Chrome_Database_Registry_Statement::addStatement($query);

        $this->_adapter->query($query);

        $this->_sentQuery = $query;

        return $this->_result;
    }

    public function setParameters(array $array, $escape = true)
    {
        if($escape === true) {
            foreach($array as $key => $value) {
                $this->_params[$key] = $this->escape($value);
            }

        } else {
            $this->_params = array_merge($this->_params, $array);
        }
    }

    public function setParameter($key, $value, $escape = true)
    {
        $this->_params[$key] = ($escape === true) ? $this->escape($value) : $value;
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function escape($data)
    {
        return $this->_adapter->escape($data);
    }

    public function getStatement()
    {
        return $this->_sentQuery;
    }

    public function clear()
    {
        $this->_query = null;
        $this->_params = null;
        $this->_result = $this->_result->clear();
    }

    protected function _prepareStatement($statement)
    {
        // replace table prefix
        $statement = str_replace('cpp_', DB_PREFIX.'_' , $statement);

        $statement = str_replace('?', '%s', $statement);
        return vsprintf($statement, $this->_params);
    }
}
