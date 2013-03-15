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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.03.2013 20:11:59] --> $
 * @link       http://chrome-php.de
 */

//TODO: enable right_handler support

if(CHROME_PHP !== true)
    die();

/**
 * Interface for all database interfaces
 *
 * Do not use setParameter/s() and query($query, array('containsParams')) or execute(array('containingParams')) together. Just
 * use one at the same time, because the merging of the parameters might return an unexpected numerical order.
 *
 * {@link execute()} and {@link query()} are escaping the given parameters. If you dont want to escape the parameters use setParameter/s.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Interface_Interface
{
    /**
     * Constructor
     *
     * @param Chrome_Database_Adapter_Interface $adapter
     * @param Chrome_Database_Result_Interface $result
     * @param Chrome_Database_Registry_Statement_Interface $statementRegistry
     *
     * @return Chrome_Database_Interface_Interface
     */
    public function __construct(Chrome_Database_Adapter_Interface $adapter, Chrome_Database_Result_Interface $result, Chrome_Database_Registry_Statement_Interface $statementRegistry);

    /**
     * Returns the result class, set in constructor
     *
     * @return Chrome_Database_Result_Interface
     */
    public function getResult();

    /**
     * Returns the adapter class, set in constructor
     *
     * @return Chrome_Database_Adapter_Interface
     */
    public function getAdapter();

    /**
     * Returns the statement registry, set in constructor
     *
     * @return Chrome_Database_Registry_Statement_Interface
     */
    public function getStatementRegistry();

    /**
     * Executes a given query with the given parameters. All parameters are getting escaped.
     *
     * @param string $query a query
     * @param array $parameters containing the parameters in numerical order to replace '?' in query string. every parameter get escaped
     * @return Chrome_Database_Result_Interface the result class containing the answer for $query
     */
    public function query($query, array $params = array());

    /**
     * Sets parameters for the next query
     *
     * The parameters are needed to replace '?' in a query string
     *
     * @param array $array containing parameters, numerically indexed.
     * @param bool  $escape true if the parameter gets escaped, false if you want the raw parameter
     * @return void
     */
    public function setParameters(array $array, $escape = true);

    /**
     * Sets a parameter for the next query
     *
     * The parameters are needed to replace '?' in a query string
     *
     * @param int $key      The index of the parameter
     * @param string $value The value of the parameter
     * @param bool $escape  true if the value gets escaped, false else
     * @return void
     */
    public function setParameter($key, $value, $escape = true);

    /**
     * Escapes a given string, using the current connection/adapter setting
     *
     * @param string $data data to escape
     * @return string the escaped data
     */
    public function escape($data);

    /**
     * Returns the raw query without a replaceing of '?' or anythign else.
     *
     * @return string the raw query
     */
    public function getStatement();

    /**
     * Returns the query, which was actual sent to the database. The query must have been executed to retrieve this value
     *
     * The query contains all replaced parameters and all other preparations
     *
     * @return string the sent query
     */
    public function getQuery();

    /**
     * Clears the interface to sent another query.
     *
     * This is needed to execute another query. The result instance of the old query will still work.
     *
     * @return Chrome_Database_Interface_Interface
     */
    public function clear();
}

interface Chrome_Database_Interface_Decorator_Interface extends Chrome_Database_Interface_Interface
{
    public function setDecorable(Chrome_Database_Interface_Interface $obj);

    public function getDecorable();
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

    public function getStatementRegistry()
    {
        return $this->_statementRegistry;
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

abstract class Chrome_Database_Interface_Decorator_Abstract extends Chrome_Database_Interface_Abstract implements Chrome_Database_Interface_Decorator_Interface
{
    protected $_decorable = null;

    public function __call($methodName, $arguments)
    {
        if($this->_decorable !== null) {
            return call_user_func_array(array($this->_decorable, $methodName), $arguments);
        }
    }

    public function setDecorable(Chrome_Database_Interface_Interface $obj)
    {
        $this->_decorable = $obj;
    }

    public function getDecorable()
    {
        return $this->_decorable;
    }

    public function clear()
    {
        if($this->_decorable !== null) {
            $this->_decorable->clear();
            $this->_adapter = $this->_decorable->getAdapter();
            $this->_result  = $this->_decorable->getResult();
        }

        $this->_params    = null;
        $this->_query     = null;
        $this->_sentQuery = null;
        return $this;
    }
}