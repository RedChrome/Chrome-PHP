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

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Model_Database_Statement_Interface
{
    public function getStatement($key);

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

interface Chrome_Database_Interface_Model_Interface
{
    public function loadQuery($key);

    public function execute(array $parameters = array());

    public function setModel(Chrome_Model_Database_Statement_Interface $model);
}

class Chrome_Database_Interface_Model extends Chrome_Database_Interface_Abstract implements Chrome_Database_Interface_Model_Interface
{
    protected $_model = null;

    /**
     * Executes a loaded query using the provieded parameters.
     * All parameters are getting escaped.
     *
     * @param array $parameters
     *        containing the parameters in numerical order to replace '?' in query string. every parameter get escaped
     * @return Chrome_Database_Result_Interface the result class containing the answer for the prepared query
     */
    public function execute(array $parameters = array())
    {
        if(count($parameters) >= 1)
        {
            $this->setParameters($parameters, true);
        }

        return $this->query($this->_query);
    }

    public function setModel(Chrome_Model_Database_Statement_Interface $model)
    {
        $this->_model = $model;
        return $this;
    }

    public function loadQuery($key)
    {
        if($this->_model === null)
        {
            throw new Chrome_Exception('No model set, which contains the stored queries');
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
        } catch(Chrome_Exception $e)
        {
            throw new Chrome_Exception_Database('Exception while getting sql statement for key "' . $key . '"!', null, $e);
        }
        return $this;
    }
}

class Chrome_Model_Database_Statement extends Chrome_Model_Cache_Abstract implements Chrome_Model_Database_Statement_Interface
{
    const DEFAULT_NAMESPACE = 'core';

    protected $_database = null;
    protected $_namespace = null;

    protected $_externalCache = null;

    public function __construct(\Chrome\Cache\Cache_Interface $cache, $namespace = null)
    {
        if(!is_string($namespace)) {
            $namespace = self::DEFAULT_NAMESPACE;
        }

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
        $options->setCacheFile(RESOURCE . 'database/' . strtolower($database) . '/' . strtolower($namespace) . '.json');
        return new \Chrome\Cache\File\Json($options);
    }

    public function setDatabaseName($databaseName)
    {
        if(!is_string($databaseName)) {
            throw new Chrome_InvalidArgumentException('Argument $databaseName must be of type string');
        }

        $this->_database = $databaseName;

        $this->_initCache();
    }

    public function setNamespace($namespace)
    {
        if(!is_string($namespace)) {
            throw new Chrome_InvalidArgumentException('Argument $namspace must be of type string');
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
            throw new Chrome_Exception('No database select. Use setDatabaseName before calling getStatement');
        }

        $statement = $this->_cache->get($key);

        // could not get statement
        if($statement === null)
        {
            throw new Chrome_Exception('Could not retrieve sql statement for key "' . $key . '" for database "'.$this->_database.'" and namespace "'.$this->_namespace.'"!');
        }

        return $statement;
    }
}