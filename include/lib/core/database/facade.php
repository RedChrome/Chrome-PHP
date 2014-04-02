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

//TODO: enable right_handler support

namespace Chrome\Database\Facade;

use \Chrome\Logger\Loggable_Interface;
use \Psr\Log\LoggerInterface;

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
interface Facade_Interface extends Loggable_Interface
{
    /**
     * Constructor
     *
     * @param \Chrome\Database\Adapter\Adapter_Interface $adapter
     * @param \Chrome\Database\Result\Result_Interface $result
     * @param \Chrome\Database\Registry\Statement_Interface $statementRegistry
     *
     * @return \Chrome\Database\Facade\Facade_Interface
     */
    public function __construct(\Chrome\Database\Adapter\Adapter_Interface $adapter, \Chrome\Database\Result\Result_Interface $result, \Chrome\Database\Registry\Statement_Interface $statementRegistry);

    /**
     * Returns the result class, set in constructor
     *
     * @return \Chrome\Database\Result\Result_Interface
     */
    public function getResult();

    /**
     * Returns the adapter class, set in constructor
     *
     * @return \Chrome\Database\Adapter\Adapter_Interface
     */
    public function getAdapter();

    /**
     * Returns the statement registry, set in constructor
     *
     * @return \Chrome\Database\Registry\Statement_Interface
     */
    public function getStatementRegistry();

    /**
     * Executes a given query with the given parameters. All parameters are getting escaped.
     *
     * Special chars in $query:
     *  '?': acts like a placeholder for a parameter. To use '?' in a query, without a replacement use '\?'
     *      Examples:
     *          'SELECT * FROM test WHERE cond = ?' -> valid, ? gets replaced by first parameter
     *          'SELECT * FROM test WHERE cond = \?' -> valid, \? gets replaced by ?, so you can use a ? in your query
     *          'SELECT * FROM test WHERE cond LIKE "test\?"', a real example ;)
     *
     *  '?{$anyInteger}': acts like a placeholder for a parameter, given with $anyInteger which is the index of the parameter.
     *                    $anyInteger must not be empty.
     *      Examples:
     *          'SELECT * FROM test WHERE cond = ?{}' -> invalid, well it just gets not replaced ;)
     *          'SELECT * FROM test WHERE cond = ?{1}' -> valid
     *          'SELECT * FROM test WHERE cond = ?{132}' -> valid ( then you have at least 132 parameters...)
     *  'cpp_': (chrome php prefix) get replaced to current table prefix. (Only cpp_ gets replaced, cpp stays the same..)
     *      Example:
     *          'SELECT * FROM cpp_test' get replaced to: 'SELECT * FROM cp1_test'
     *
     * This method throws a database exception if you try to use ? with ?{} together. Do not use them together!
     *  Example:
     *      'SELECT * FROM cpp_test WHERE cond1 = ? AND cond2 = ?{1}' -> throws an exception
     *      'SELECT * FROM cpp_test WHERE cond1 = ?{1} AND cond2 = ?{1}' works fine
     *      'SELECT * FROM cpp_test WHERE cond1 = ? AND cond2 = ?' works fine
     *
     * Note that if you call this method without an empty array as parameters, then there will be no replacement of ? and ?{}. Of course,
     * the strings cpp and \? get correct replaced. So in this case you can use ? and ?{} together, but not recommended!
     *  Example:
     *      query('SELECT * FROM cpp_test WHERE cond1 = ? OR cond2 LIKE "?{1} \?" ', array())
     *          -> actual query: 'SELECT * FROM cp1_test WHERE cond1 = ? OR cond2 LIKE "?{1} ?"
     *
     * @param string $query a query
     * @param array $parameters containing the parameters in numerical order to replace '?' in query string. every parameter get escaped
     * @return \Chrome\Database\Result\Result_Interface the result class containing the answer for $query
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
     * Returns the raw query without a replaceing of '?', '?{int*}' or anything else.
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
     * @return \Chrome\Database\Facade\Facade_Interface
     */
    public function clear();
}

/**
 * Interface for all database interface classes which shall allow to set another database interface class
 * as dercorable.
 * Usefull to combine multiple database interfaces to a single one and use all the functionality from those
 * database interfaces.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Decorator_Interface extends Facade_Interface
{
    /**
     * Sets a database interface as an decorable to use its functionality
     *
     * @param \Chrome\Database\Facade\Facade_Interface $obj
     * @return void
     */
    public function setDecorable(Facade_Interface $obj);

    /**
     * Returns the database interface set by setDecorable
     *
     * @return \Chrome\Database\Facade\Facade_Interface
     */
    public function getDecorable();
}

abstract class AbstractFacade implements Facade_Interface
{
    protected $_query = null;

    protected $_adapter = null;

    protected $_result = null;

    protected $_params = array();

    protected $_sentQuery = null;

    protected $_statementRegistry = null;

    protected $_logger = null;

    public function __construct(\Chrome\Database\Adapter\Adapter_Interface $adapter, \Chrome\Database\Result\Result_Interface $result, \Chrome\Database\Registry\Statement_Interface $statementRegistry)
    {
        $this->_statementRegistry = $statementRegistry;
        $this->_adapter = $adapter;
        $this->_result = $result;
    }

    public function query($query, array $params = array())
    {
        try {
            if($this->_sentQuery !== null) {
                throw new \Chrome\DatabaseException('Did not called clear() before executing another query!');
            }

            if($query === null OR empty($query)) {
                throw new \Chrome\DatabaseException('Cannot execute an sql statement if no statement was set!');
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
        } catch(\Chrome\DatabaseException $e) {

            if($this->_logger !== null) {
                $this->_logger->error($e);
            }

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
        $this->_params    = array();
        $this->_sentQuery = null;
        $this->_adapter   = $this->_adapter->clear();
        $this->_result    = $this->_result->clear();
        $this->_result->setAdapter($this->_adapter);
        return $this;
    }

    protected function _prepareStatement($statement)
    {
        $statement = $this->_adapter->prepareStatement($statement);
        // do not allow to modifie the behavior of vsprintf manually...
        $statement = str_replace('%', '%%', $statement);

        if(count($this->_params) === 0) {
            return str_replace('\\?', '?', $statement);
        }

        $countFirst = 0;
        $countSecond = 0;

        // if \? is given, then we ignore it (int both reg exprs...). this can be achieved by: (?<!\\)
        // this replaces ?{int} where int must contain at least one integer.
        $statement = preg_replace('@(?<!\\\\)\?{(\d{1,})}@sU', '%\1$s', $statement, -1, $countFirst);
        // this replaces simple ?
        $statement = preg_replace('@(?<!\\\\)\?(?!{)@sU', '%s', $statement, -1, $countSecond);
        // now replace \? to ?, escapeing the ?...
        $statement = str_replace('\\?', '?', $statement);

        // vsprintf cant handle that..
        if($countFirst > 0 AND $countSecond > 0) {
            throw new \Chrome\DatabaseException('Cannot mix "?" with "?{int*}". Do not use them at the same time');
        }

        return vsprintf($statement, $this->_params);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        return $this->_logger;
    }
}

abstract class AbstractDecorator extends AbstractFacade implements \Chrome\Database\Facade\Decorator_Interface
{
    protected $_decorable = null;

    public function __call($methodName, $arguments)
    {
        if($this->_decorable !== null) {
            return call_user_func_array(array($this->_decorable, $methodName), $arguments);
        }
    }

    public function setDecorable(\Chrome\Database\Facade\Facade_Interface $obj)
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