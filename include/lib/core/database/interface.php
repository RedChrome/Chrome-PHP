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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [06.03.2013 20:55:06] --> $
 * @link       http://chrome-php.de
 */

//TODO: enable right_handler support

if(CHROME_PHP !== true) die();

interface Chrome_Database_Interface_Interface
{
    public function __construct(Chrome_Database_Adapter_Interface $adapter, Chrome_Database_Result_Interface $result, Chrome_Database_Registry_Statement_Interface $statementRegistry);

    public function getResult();

    public function getAdapter();

    public function execute(array $parameters = array());

    public function query($query, array $params = array());

    public function setParameters(array $array, $escape = true);

    public function setParameter($key, $value, $escape = true);

    public function escape($data);

    public function getStatement();

    public function getQuery();

    public function clear();
}

abstract class Chrome_Database_Interface_Abstract implements Chrome_Database_Interface_Interface
{
    protected $_query = null;

    protected $_adapter = null;

    protected $_result = null;

    protected $_params = array();

    protected $_sentQuery = null;

    protected $_statementRegistry = null;

    public function __construct(Chrome_Database_Adapter_Interface $adapter, Chrome_Database_Result_Interface $result, Chrome_Database_Registry_Statement_Interface $statementRegistry)
    {
        $this->_statementRegistry = $statementRegistry;
        $this->_adapter = $adapter;
        $this->_result = $result;
    }

    public function execute(array $parameters = array())
    {
        if(count($parameters) >= 1) {
            $this->setParameters($parameters, true);
        }

        return $this->query($this->_query);
    }

    public function query($query, array $params = array())
    {
        try {
            if($this->_sentQuery !== null) {
                throw new Chrome_Exception_Database('Did not called clear() before executing another query!');
            }

            if($query === null OR empty($query)) {
                throw new Chrome_Exception_Database('Cannot execute an sql statement if no statement was set!');
            }

            $this->_query = $query;

            if(count($params) > 0) {
                $this->setParameters($params, true);
            }

            $query = $this->_prepareStatement($query);

            $this->_statementRegistry->addStatement($query);

            $this->_adapter->query($query);

            $this->_sentQuery = $query;

            return $this->_result;
        } catch(Chrome_Exception_Database $e) {
            Chrome_Log::logException($e, E_ERROR, new Chrome_Logger_Database());
            throw $e;
        }
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

        return $this;
    }

    public function setParameter($key, $value, $escape = true)
    {
        $this->_params[$key] = ($escape === true) ? $this->escape($value) : $value;
        return $this;
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
        return $this->_query;
    }

    public function getQuery()
    {
        return $this->_sentQuery;
    }

    public function clear()
    {
        $this->_query     = null;
        $this->_params    = null;
        $this->_sentQuery = null;
        $this->_adapter   = $this->_adapter->clear();
        $this->_result    = $this->_result->clear();
        $this->_result->setAdapter($this->_adapter);
        return $this;
    }

    protected function _prepareStatement($statement)
    {
        // replace table prefix
        $statement = str_replace('cpp_', DB_PREFIX . '_', $statement);

        $statement = str_replace('?', '%s', $statement);

        // Note: you have to escape % (if your using queries: select * form test where val LIKE "test%") with %
        // so the query would look like: select * from test where val LIKE "test%%"
        return vsprintf($statement, $this->_params);
    }
}
