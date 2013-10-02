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

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Model_Database_Statement_Interface
{
    public function getStatement($key);
}

class Chrome_Database_Interface_Model extends Chrome_Database_Interface_Abstract
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
        $this->clear();
        $this->_checkModel();
        try
        {
            $this->_query = $this->_model->getStatement($key);
        } catch(Chrome_Exception $e)
        {
            throw new Chrome_Exception_Database('Exception while getting sql statement for key "' . $key . '"!', null, $e);
        }
        return $this;
    }

    protected function _checkModel()
    {
        // use default one
        if($this->_model === null)
        {
            throw new Chrome_Exception('No model set, which contains the stored queries');
        }
    }
}
class Chrome_Model_Database_Statement extends Chrome_Model_Cache_Abstract implements Chrome_Model_Database_Statement_Interface
{
    private static $_caches = array();
    const DEFAULT_NAMESPACE = 'core';

    public function __construct($namespace, $database)
    {
        if(!is_string($namespace) or !is_string($database))
        {
            throw new Chrome_InvalidArgumentException('$namespace and $database must be of type string!');
        }

        $this->_namespace = $namespace;
        $this->_database = $database;

        if(isset(self::$_caches[$this->_database][$this->_namespace]))
        {
            $this->_cache = self::$_caches[$this->_database][$this->_namespace];
        } else
        {
            parent::__construct($this);
            self::$_caches[$this->_database][$this->_namespace] = $this->_cache;
        }
    }

    public static function create($databaseObject, $namespace = null)
    {
        if($namespace === null)
        {
            $namespace = self::DEFAULT_NAMESPACE;
        }

        $database = null;

        if($databaseObject instanceof Chrome_Database_Connection_Interface) {
            $database = $databaseConnection->getDatabaseName();
        } elseif($databaseObject instanceof Chrome_Database_Factory_Interface)
        {
            $database = $databaseObject->getConnectionRegistry()->getConnectionObject(Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION)->getDatabaseName();
        } else {
            throw new Chrome_InvalidArgumentException('$databaseObject must be of instance Chrome_Database_Connection_Interface or Chrome_Database_Factory_Interface');
        }

        return new self($namespace, $database);
    }

    public static function clearCaches()
    {
        self::$_caches = array();
    }

    protected function _setUpCache()
    {
        $this->_cacheOption = new Chrome_Cache_Option_Json();
        $this->_cacheOption->setCacheFile(RESOURCE . 'database/' . strtolower($this->_database) . '/' . strtolower($this->_namespace) . '.json');
        $this->_cacheInterface = 'Json';
    }

    public function getStatement($key)
    {
        $statement = $this->_cache->get($key);

        // could not get statement
        if($statement === null)
        {
            throw new Chrome_Exception('Could not retrieve sql statement for key "' . $key . '"!');
        }

        return $statement;
    }
}

/*
class Chrome_Model_Database_Statement extends Chrome_Model_Cache_Abstract implements Chrome_Model_Database_Statement_Interface
{

    private static $_instances = array();
    protected $_namespace = null;
    protected $_database = null;
    const DEFAULT_NAMESPACE = 'core';
    const DEFAULT_DATABASE = CHROME_DATABASE;

    public function __construct($namespace, $database)
    {
        $this->_namespace = $namespace;

        if($database instanceof Chrome_Database_Connection_Interface)
        {
            $this->_database = strtolower($database->getDefaultAdapterSuffix());
        } else
        {
            $this->_database = $database;
        }

        parent::__construct($this);
    }

    public static function getInstance($namespace = null, $database = null)
    {
        if($namespace === null)
        {
            $namespace = self::DEFAULT_NAMESPACE;
        }

        if($database instanceof Chrome_Database_Connection_Interface)
        {
            $database = strtolower($database->getDefaultAdapterSuffix());
        } else
        {
            $database = $database;
        }

        if($database === null)
        {
            $database = self::DEFAULT_DATABASE;
        }

        if(!isset(self::$_instances[$database][$namespace]))
        {

            self::$_instances[$database][$namespace] = new self($namespace, $database);
        }

        return self::$_instances[$database][$namespace];
    }

    protected function _setUpCache()
    {
        $this->_cacheOption = new Chrome_Cache_Option_Json();
        $this->_cacheOption->setCacheFile(RESOURCE . 'database/' . strtolower($this->_database) . '/' . strtolower($this->_namespace) . '.json');
        $this->_cacheInterface = 'Json';
    }

    public function getStatement($key)
    {
        $statement = $this->_cache->get($key);

        // could not get statement
        if($statement === null)
        {
            throw new Chrome_Exception('Could not retrieve sql statement for key "' . $key . '"!');
        }

        return $statement;
    }
}
*/