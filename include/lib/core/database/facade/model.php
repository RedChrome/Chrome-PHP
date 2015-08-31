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

namespace Chrome\Model\Database;

/**
 * An interface to retrieve datbase statements
 *
 * A database statement is grouped by the database name and the namespace.
 * Since different databases have different statements (sql is not consistently used), those statements may vary.
 * To avoid collisions between the $key's, there is another parameter, the namespace. This must be used to group
 * the statements from different modules.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Statement_Interface
{
    /**
     * Tries to retrieve a statement which is associated with a $key
     *
     * If there was no statement found with the given $key, then a Exception is thrown.
     * If namespace or database name were not set, another Exception is thrown
     *
     * @throws \Chrome\Exception
     * @param string $key
     * @return string
     */
    public function getStatement($key);

    /**
     * Sets the namespace.
     *
     * A set of statements can get grouped into a namespace to avoid collisions with other modules.
     *
     * @param string $namespace
     */
    public function setNamespace($namespace);

    /**
     * Sets the database name of the used database
     *
     * Since some databases use other sql commands, we have to use for every database
     * other sql-queries. This method will help to identify the correct sql-queries.
     *
     * @param string $connectionName
     */
    public function setDatabaseName($databaseNmae);
}


/**
 * A implementation of \Chrome\Model\Database\Statement_Interface using a cache to retrieve statements.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class JsonStatement extends \Chrome\Model\AbstractCache implements Statement_Interface
{
    const DEFAULT_NAMESPACE = 'core';

    protected $_database = null;
    protected $_namespace = null;

    protected $_directory = null;
    protected $_externalCache = null;

    public function __construct(\Chrome\Cache\Cache_Interface $cache, \Chrome\Directory_Interface $cacheDir, $namespace = null)
    {
        if(!is_string($namespace)) {
            $namespace = self::DEFAULT_NAMESPACE;
        }

        $this->_directory = $cacheDir;

        $this->_externalCache = $cache;

        $this->setNamespace($namespace);
    }

    protected function _initCache()
    {
        // only init cache, if all needed params are set.
        if($this->_database === null OR $this->_namespace === null) {
            return;
        }

        if(($this->_cache = $this->_externalCache->get($this->_database.'/'.$this->_namespace)) === null) {
            // use parent constructor to create a cache, this will be available in $this->_cache.
            //parent::__construct($this);
            $this->_cache = $this->_createCache($this->_database, $this->_namespace);
            $this->_externalCache->set($this->_database.'/'.$this->_namespace, $this->_cache);
        }
    }

    protected function _createCache($database, $namespace)
    {
        $options = new \Chrome\Cache\Option\File\Json();
        $options->setCacheFile($this->_directory->file(strtolower($database) . \Chrome\Directory_Interface::SEPARATOR . strtolower($namespace) . '.json', true));
        return new \Chrome\Cache\File\Json($options);
    }

    public function setDatabaseName($databaseName)
    {
        if(!is_string($databaseName)) {
            throw new \Chrome\InvalidArgumentException('Argument $databaseName must be of type string');
        }

        $this->_database = $databaseName;

        $this->_initCache();
    }

    public function setNamespace($namespace)
    {
        if(!is_string($namespace)) {
            throw new \Chrome\InvalidArgumentException('Argument $namspace must be of type string');
        }

        $this->_namespace = $namespace;

        $this->_initCache();
    }

    public function clearCache()
    {
        $this->_caches = array();
    }

    public function getStatement($key)
    {
        if($this->_cache === null) {
            throw new \Chrome\Exception('No database select. Use setDatabaseName before calling getStatement');
        }

        $statement = $this->_cache->get($key);

        // could not get statement
        if($statement === null)
        {
            throw new \Chrome\Exception('Could not retrieve sql statement for key "' . $key . '" for database "'.$this->_database.'" and namespace "'.$this->_namespace.'"!');
        }

        return $statement;
    }
}

namespace Chrome\Database\Facade;

/**
 * An interface to execute statements, given by an external statement provider (aka model)
 *
 * Use loadQuery, to load a statement via the model. Then execute this statement with additions parameters
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Model_Interface
{
    /**
     * Loads a query from the model.
     *
     * Throws an exception if the statement could not get loaded by the model
     *
     * @throws \Chrome\Exception
     * @param string $key
     * @return void
     */
    public function loadQuery($key);

    /**
     * Executes a loaded query using the provided parameters.
     * All parameters are getting escaped.
     *
     * @param array $parameters
     *        containing the parameters in numerical order to replace '?' in query string. every parameter gets escaped
     *
     * @return \Chrome\Database\Result\Result_Interface the result class containing the answer for the prepared query
     */
    public function execute(array $parameters = array());

    /**
     * Sets the model class which provides the statements
     *
     * @param \Chrome\Model\Database\Statement_Interface $model
     */
    public function setModel(\Chrome\Model\Database\Statement_Interface $model);
}

/**
 * A basic implementation of \Chrome\Database\Facade\Model_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Model extends AbstractFacade implements Model_Interface
{
    protected $_model = null;

    public function execute(array $parameters = array())
    {
        if(count($parameters) >= 1)
        {
            $this->setParameters($parameters, true);
        }

        return $this->query($this->_query);
    }

    public function setModel(\Chrome\Model\Database\Statement_Interface $model)
    {
        $this->_model = $model;
        return $this;
    }

    public function loadQuery($key)
    {
        if($this->_model === null)
        {
            throw new \Chrome\Exception('No model set, which contains the stored queries');
        }

        // only clear, if there was a query sent before..
        if($this->_query !== null) {
            $this->clear();
        } else {
            // we have to inform the model which database we're using, since the model will determine
            // the sql query depending of the database
            $this->_model->setDatabaseName($this->_adapter->getConnection()->getDatabaseName());
        }

        try
        {
            $this->_query = $this->_model->getStatement($key);
        } catch(\Chrome\Exception $e)
        {
            throw new \Chrome\Exception\Database('Exception while getting sql statement for key "' . $key . '"!', null, $e);
        }
        return $this;
    }
}
