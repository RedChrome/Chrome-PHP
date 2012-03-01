<?php

/**
 * CHROME-PHP CMS
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
 * @subpackage Chrome.DB.Adapter
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.02.2012 00:32:53] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.DB.Adapter
 */
abstract class Chrome_DB_Adapter_Abstract
{
	/**
	 * Contains all methods, which aren't allowed to get called by _callMethod
	 *
	 * @var array
	 */
	private static $__forbiddenMethods = array('__construct'=>1, '__call'=>1, '__callStatic'=>1, '_callMethod'=>1, '_setAdapter'=>1, '_loadAdapter'=>1, '_initAdapter'=>1, '_setSQLType'=>1);

	/**
	 * Contains all initialized interfaces
	 *
	 * @var array
	 */
	private static $_interfaces = array();

	/**
	 * Contains all initialized adapters
	 *
	 * @var array
	 */
	private static $_adapters = array();

	/**
	 * All SQL-Statements sent to database
	 *
	 * @var array
	 */
	private static $_sqlStatements = array();

	/**
	 * Instance of Chrome_DB_Registry
	 *
	 * @var Chrome_DB_Registry instance
	 */
	protected static $_registryInstance;

	/**
	 * Connection to database
	 *
	 * @var resource
	 */
	protected $_connection = null;

	/**
	 * Current connection ID, needed for getting connection from Chrome_DB_Registry
	 *
	 * @var int
	 */
	 protected $_connectionID = null;

	 /**
	 * Default connection ID
	 *
	 * @var int
	 */
	protected static $_defaultConnectionID = null;

	/**
	 * Which SQL query will be send? e.g. select, update, instert etc..
	 *
	 * @var string
	 */
	protected $_SQLType = array();

	/**
	 * Constructor, <br>
	 * sets @see self::$_registryInstance to an instance of Chrome_DB_Registry
	 *
	 * @return void
	 */
	protected function __construct()
	{
		if(self::$_registryInstance === null) {
			self::$_registryInstance = Chrome_DB_Registry::getInstance();
		}
	}

	/**
	 * Registers an interface, set adapter AND instance for this interface
	 *
	 * @param Chrome_DB_Interface_Abstract $obj Instance of an Interface to register
	 * @return void
	 */
	public static function registerInterface(Chrome_DB_Interface_Abstract &$obj)
	{
		// get Adapter name
		// e.g. MySQL
		$adapter = $obj->getAdapter();

		// check wheter adapter is loaded
		if(!self::_isLoadedAdapter($adapter)) {
			// load adapter
			self::_loadAdapter($adapter);
		}

		// set interface with instance AND adapter
		self::$_interfaces[$obj->getID()] = array('instance' => &$obj, 'adapter' => $adapter);
	}

	/**
	 * Magic method of PHP
	 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
	 *
	 * @param string $method name of the method
	 * @param array $arguments arguments, called by method
	 * @return void
	 */
	public static function __callStatic($method, $arguments)
	{
		// 1. argument must be an instance of Chrome_DB_Interface_Abstract
		if(!($arguments[0] instanceof Chrome_DB_Interface_Abstract)) {
			throw new Chrome_Exception('Cannot call method ' . $method . ' without an instance of Chrome_DB_Interface_Abstract! First argument must be an instance of Chrome_DB_Interface_Abstract OR a child class!', 1);
		}

		// get ID of Interface
		$interfaceID = $arguments[0]->getID();

		if($method == 'execute') {

			// get instance of adapter
			$adapterInstance = &self::$_adapters[self::$_interfaces[$interfaceID]['adapter']]['instance'];

   			if($adapterInstance->getConnectionID() === null) {
   				self::_initAdapter(self::$_interfaces[$interfaceID]['adapter']);
   			}

			// set connection for adapter
			self::$_registryInstance->setConnectionForAdapter($adapterInstance);

		}

		// calls the method with the arguments
		$return = self::_callMethod($method, $arguments, $interfaceID);

		if($method == 'execute') {
 	        // add the sql statement
			self::$_sqlStatements[] = $adapterInstance->_getStatement($arguments[0]);
		}

		return $return;
	}

	/**
	 *
	 * Magic method of PHP <br>
	 * Wrapper for self::__callStatic, workaround for PHP < 5.3
	 *
	 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
	 *
	 * @param string $method
	 * @param array $arguments
	 * @return void
	 */
	public function __call($method, $arguments)
	{
		return self::__callStatic($method, $arguments);
	}

	/**
	 * Calls a method, with arguments with the adpater of the interface
	 *
	 * @param string $method name of the method
	 * @param array $arguments arguments of the method
	 * @param int $interfaceID ID of the interface
	 * @return void
	 */
	private static function _callMethod($method, $arguments, $interfaceID)
	{
		// check for valid methods...
		if(isset(self::$__forbiddenMethods['_' . $method])) {
			throw new Chrome_Exception('You cannot call this method "_' . $method . '"! This method is private OR protected!', 1);
		}

		$_method = $method;
		// make method to an internal method, otherwise method of this class would get called
		// but we want to call method of child class!
		$method = '_' . $method;

		// check wheter method exists in child class
		if(!method_exists(self::$_adapters[self::$_interfaces[$interfaceID]['adapter']]['instance'], $method)) {

			// check wheter method exists without a '_'
			if(method_exists(self::$_adapters[self::$_interfaces[$interfaceID]['adapter']]['instance'], $_method)) {
				// call this method
				return call_user_func_array(array(self::$_adapters[self::$_interfaces[$interfaceID]['adapter']]['instance'], $_method), $arguments);
			} else {
				throw new Chrome_Exception('Method "_' . $method . '" does not exist in class Chrome_DB_Adapter_Abstract!', 2);
			}
		} else {
			// calls this method
			return call_user_func_array(array(self::$_adapters[self::$_interfaces[$interfaceID]['adapter']]['instance'], $method), $arguments);
		}
	}

	/**
	 * Sets an adapter for an interface
	 *
	 * @param Chrome_DB_Interface_Abstract $obj Instance of the interface
	 * @param string                       $adapter name of the adapter e.g. MySQL
	 * @return bool true on success, false else
	 */
	public static function setAdapterForInterface(Chrome_DB_Interface_Abstract & $obj, $adapter)
	{
		// get ID of interface
		$interfaceID = $obj->getID();

		// is the interface already set?, if not you cannot change adapter!
		if(!isset(self::$_interfaces[$interfaceID])) {
			return false;
		}

		// sets the adapter
		self::_setAdapter($interfaceID, $adapter);

		return true;
	}

	/**
	 * Sets an adapter for an interface
	 *
	 * @param int    $interfaceID ID of the interface
	 * @param string $adapter name of the adapter
	 * @return void
	 */
	private static function _setAdapter($interfaceID, $adapter)
	{
		// set adapter
		self::$_adapters[$interfaceID]['adapter'] = $adapter;

		// load adapter, adapter was not used before
		if(self::_isLoadedAdapter($adapter)) {
			self::_loadAdapter($adapter);
		}
	}

	/**
	 * Loads an adapter
	 *
	 * @param string $adapter name of the adapter
	 * @return void
	 */
	private static function _loadAdapter($adapter)
	{
		// does the adapter exist
		if(!_isFile(LIB.'core/database/Adapter/'.$adapter.'.php'))  {
			throw new Chrome_Exception('Cannot load adapter '.$adapter.'! File does not exist in Chrome_DB_Adapter_Abstract::_loadAdapter()!', 3);
		}

		// include the adapter
		require_once LIB.'core/database/Adapter/'.$adapter.'.php';

		// select class name, is the prefix 'Chrome' set?, check without autoloading
		//if(class_exists('Chrome_DB_Adapter_' . $adapter, false)) {
        // all classes beginn with Chrome
			$class = 'Chrome_DB_Adapter_' . $adapter;
		//} else if(class_exists('DB_Adapter_' . $adapter, false)) {
		//	$class = 'DB_Adapter_' . $adapter;
		//} else {
			// no class found
		//	throw new Chrome_Exception('Cannot find class (Chrome_)DB_Adapter_' . $adapter . ' in Chrome_DB_Adapter_Abstract::_loadAdapter()!', 4);
		//}

		// sets the adapter
		self::$_adapters[$adapter] = array('class' => $class, 'instance' => call_user_func(array($class, 'getInstance')));

		// configure the adapter
		//self::_initAdapter($adapter);
	}

    /**
     * Initializes the default connection to database
     *
     * Mainly used to escape data before a connection is established
     *
     * @return void
     */
    public static function initDefaultConnection() {

        // default connection already established
        if(self::$_defaultConnectionID !== null) {
            // get adapter instance
            $adapterInstance = self::$_adapters[CHROME_DATABASE]['instance'];
            // set connection
            $adapterInstance->setConnectionID(self::$_defaultConnectionID);
            self::$_registryInstance->setConnectionForAdapter($adapterInstance);
            return;

        }

        // initialize adapter
        self::_initAdapter(CHROME_DATABASE);
        // set default connection id
        self::setDefaultConnectionID(self::$_adapters[CHROME_DATABASE]['instance']->getConnectionID());
    }

	/**
	 * Configure an adapter
	 *
	 * @param string $adapter name of the adapter
	 * @return void
	 */
	private static function _initAdapter($adapter)
	{
		// can only configure the adapter if its the default adapter
		if($adapter === CHROME_DATABASE) {

			// get instance
			$adapterInstance = self::$_adapters[$adapter]['instance'];

            // create connection AND save connection ID
            $connectionID = self::$_registryInstance->createConnection($adapterInstance, DB_HOST, DB_NAME, DB_USER, DB_PASS);

			// set connection ID
			$adapterInstance->setConnectionID($connectionID);
		}
	}

	/**
	 * Checks wheter the adapter is loaded OR not
	 *
	 * @param string $adapter
	 * @return bool true if adpater is loaded, false else
	 */
	protected static function _isLoadedAdapter($adapter)
	{
		return (isset(self::$_adapters[$adapter])) ? true : false;
	}

	/**
	 * Sets an connection
	 *
	 * @return void
	 */
	protected function _setConnection()
	{
		self::$_registryInstance->setConnectionForAdapter($this);
	}

	/**
	 * Sets a connection<br>
	 * This method gets called automatically, so _DONT_ call it by yourself!<br>
	 * Use instead {@see Chrome_DB_Adapter_Abstract::setConnection()}
	 *
	 * @deprecated
	 * @param Chrome_DB_Registry $obj instance of Chrome_DB_Registry
	 * @param resource           $connection connection to db
	 * @return void
	 */
	public function setConnectionByRegistry(Chrome_DB_Registry &$obj, $connection)
	{
		$this->_connection = $connection;
	}

	/**
	 * Sets a connection
	 *
	 * @param resource $connection connection to db
	 * @return void
	 */
	public function setConnection($connection)
	{
		// valid connection?
		if(!is_resource($connection)) {
			throw new Chrome_Exception('Cannot set connection! Given connection is not a valid resource!', 5);
		}
		$this->_connection = $connection;
	}

	/**
	 * Gets connection ID
	 *
	 * @return int connection ID
	 */
	public function getConnectionID()
	{
        return $this->_connectionID;
	}

	/**
	 * Sets a connection ID
	 *
	 * @param int $connectionID id of the connection, from Chrome_DB_Registry
	 * @return void
	 */
	public function setConnectionID($connectionID)
	{
		// valid connection ID
		if($connectionID < 0 OR !is_int($connectionID)) {
			throw new Chrome_Exception('No valid connection ID given in Chrome_DB_Adapter_Abstract::setConnectionID()!', 6, null, false);
		}
		// set connection id
		$this->_connectionID = $connectionID;
        $this->_setConnection();
	}

	/**
	 * Sets default connection ID
	 *
	 * @param int $connectionID id of the connection, from Chrome_DB_Registry
	 * @return void
	 */
	public static function setDefaultConnectionID($connectionID)
	{
		// valid connection ID?
		if($connectionID < 0 OR !is_int($connectionID)) {
			throw new Chrome_Exception('No valid connection ID given in Chrome_DB_Adapter_Abstract::setDefaultConnectionID()!', 7);
		}

		self::$_defaultConnectionID = $connectionID;
	}

	/**
	 * Gets default connection ID
	 *
	 * @return int default connection ID
	 */
	public static function getDefaultConnectionID()
	{
		return self::$_defaultConnectionID;
	}

	/**
	 * Resets connection
	 *
	 * @return void
	 */
	protected function _resetConnection()
	{
		// delete connection
		$this->_connection = null;
		// set connectionID for next query
		$this->_connectionID = self::$_defaultConnectionID;
	}

	/**
	 * Get all executed SQL statements
	 *
	 * @return array all executed SQL statements
	 */
	public static function getSQLQuerys()
	{
		return self::$_sqlStatements;
	}

	/**
	 * Connect to a database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj Instance of the interface, needed to create a connection
	 * @param string                       $server name of the server, e.g. localhost
	 * @param string                       $database name of the database, e.g. chrome
	 * @param string                       $user name of the user
	 * @param string                       $pass password
	 * @return bool true
	 */
	public static function connect(Chrome_DB_Interface_Abstract & $obj, $server, $database, $user, $pass)
	{
		// get id of the interface
		$interfaceID = $obj->getID();

		// get instance of the adapter, needed to create a connection
		@$adapterInstance = self::$_adapters[self::$_interfaces[$interfaceID]['adapter']]['instance'];
		// if its empty, create the adapter
		if(empty($adapterInstance)) {
			$adapterInstance = call_user_func(array(self::$_adapters[$adapter]['class'], 'getInstance'));
		}

		try {
            // create connection AND get connection ID
			$connectionID = self::$_registryInstance->createConnection($adapterInstance, $server, $database, $user, $pass);
		} catch(Chrome_Exception_Database $e) {
            throw new Chrome_Exception_Database($e->getMessage(), $e->getCode(), $e);
        }
		// set connection ID
		$adapterInstance->setConnectionID($connectionID);
		// if default connection ID already set, do not renew default connection ID
		if($adapterInstance->getDefaultConnectionID() === null)
			$adapterInstance->setDefaultConnectionID($connectionID);

		return true;
	}

	/**
	 *
	 * Selects something from database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param array/string                 $select what you want to select from db e.g. array('id', 'user') OR just 'user'
	 * @param string                       $distinct [optional] available types: ALL, DISTINCT, DISTINCTROW
	 * @param bool                         $highPriority [optional] if true the query will be high priority
	 * @param string                       $sqlCache [optional] available types: SQL_CACHE, SQL_NO_CACHE
	 * @throws Chrome_Exception
	 * @return void
	 */
	protected function _select(Chrome_DB_Interface_Abstract & $obj = null, $select, $distinct = null, $highPriority = false, $sqlCache = 'SQL_CACHE')
	{
		$interfaceID = $obj->getID();
		$this->_setSQLType($obj, 'select');
		$this->_statementOption[$interfaceID]['select'] = array('select'=>$select, 'distinct'=>$distinct, 'highPriority'=>$highPriority, 'sqlCache'=>$sqlCache);
	}

	/**
	 *
	 * Inserts something into database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param string                       $priority [optional] available types: LOW_PRIORITY, DELAYED, HIGH_PRIORITY
	 * @param bool                         $ignore [optional] ignore warnings on error?
	 * @throws Chrome_Exception
	 * @return void
	 */
	protected function _insert(Chrome_DB_Interface_Abstract & $obj = null, $priority = null, $ignore = false)
	{
		$interfaceID = $obj->getID();
		$this->_setSQLType($obj, 'insert');
		$this->_statementOption[$interfaceID]['insert'] = array('into'=>$into, 'priority'=>$priority, 'ignore'=>$ignore);
	}

	/**
	 *
	 * Updates a row in database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param string                       $table which table?
	 * @param bool                         $lowPriority [optional] if true: query has low priority
	 * @param bool                         $ignore [optional] if true: ignores errors in query
	 * @param bool                         $addPrefix [optional] if true: adds a table prefix to $table
	 * @throws Chrome_Exception
	 * @return void
	 */
	protected function _update(Chrome_DB_Interface_Abstract & $obj = null, $table, $lowPriority = false, $ignore = false, $addPrefix = true)
	{
		$interfaceID = $obj->getID();
		$this->_setSQLType($obj, 'update');
		$this->_statementOption[$interfaceID]['update'] = array('table'=>$table, 'lowPriority'=>$lowPriority, 'ignore'=>$ignore);
	}

	/**
	 *
	 * Deletes a row from database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param bool                         $lowPriority [optional] if true: query has low priority
	 * @param bool                         $quick [optional] if true: may speed up some queries on MyISAM
	 * @param bool                         $ignore [optional] if true: ignores errors
	 * @throws Chrome_Exception
	 * @return void
	 */
	protected function _delete(Chrome_DB_Interface_Abstract & $obj = null, $lowPriority = false, $quick = false, $ignore = false)
	{
		$interfaceID = $obj->getID();
		$this->_setSQLType($obj, 'delete');
		$this->_statementOption[$interfaceID]['delete'] = array('lowPriority'=>$lowPriority, 'quick'=>$quick, 'ignore'=>$ignore);
	}

	/**
	 *
	 * Replaces a row
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param bool                         $priority [optional] available types: LOW_PRIORITY, DELAYED
	 * @throws Chrome_Exception
	 * @return void
	 */
	protected function _replace(Chrome_DB_Interface_Abstract & $obj = null, $priority = null)
	{
		$interfaceID = $obj->getID();
		$this->_setSQLType($obj, 'replace');
		$this->_statementOption[$interfaceID]['replace'] = array('table'=>$table, 'priority'=>$priority);
	}

	/**
	 *
	 * Truncates a table
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param string                       $table table
	 * @param bool                         $addPrefix [optional] adds a table prefix to $table
	 * @throws Chrome_Exception
	 * @return void
	 */
	protected function _truncate(Chrome_DB_Interface_Abstract & $obj = null, $table, $addPrefix = true)
	{
		$interfaceID = $obj->getID();
		$this->_setSQLType($obj, 'truncate');
		$this->_statementOption[$interfaceID]['truncate'] = array('table'=>$table);
	}

	protected function _from(Chrome_DB_Interface_Abstract & $obj = null, $from, $addPrefix = true)
	{
		$interfaceID = $obj->getID();
		$this->_statementOption[$interfaceID]['from'] = array('from'=>$from);
	}

	protected function _into(Chrome_DB_Interface_Abstract & $obj = null, $table, array $tableStructure = null, $addPrefix = true)
	{
		$interfaceID = $obj->getID();
		$this->_statementOption[$interfaceID]['into'] = array('table'=>$table, 'tableStructure'=>$tableStructure);
	}

	protected function _where(Chrome_DB_Interface_Abstract & $obj = null, $condition)
	{
		$interfaceID = $obj->getID();
		$this->_statementOption[$interfaceID]['where'] = array('condition'=>$condition);
	}

	protected function _groupBy(Chrome_DB_Interface_Abstract & $obj = null, $group, $groupType = 'ASC')
	{
	}

	protected function _having(Chrome_DB_Interface_Abstract & $obj = null, $condition)
	{
		$interfaceID = $obj->getID();
		$this->_statementOption[$interfaceID]['having'] = array('condition'=>$condition);
	}

	protected function _orderBy(Chrome_DB_Interface_Abstract & $obj = null, $field, $orderTyp = 'ASC')
	{
	}

	protected function _limit(Chrome_DB_Interface_Abstract & $obj = null, $offset = null, $rowCount = null)
	{
	}

	protected function _values(Chrome_DB_Interface_Abstract & $obj = null, array $values)
	{
	}

	protected function _set(Chrome_DB_Interface_Abstract & $obj = null, array $set)
	{
	}

    protected function _hasRight(Chrome_DB_Interface_Abstract & $obj = null, $colomnName, $groupID = null)
    {
        $interfaceID = $obj->getID();
        $this->_statementOption[$interfaceID]['hasRight'] = array('colomnName' => $colomnName, 'groupID' => $groupID);
    }

	/**
	 *
	 * Escapes a string, using database connection
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param string                       $string
	 * @return escaped string
	 */
	protected function _escape(Chrome_DB_Interface_Abstract & $obj = null, $string)
	{
	}

	/**
	 *
	 * Prepares a query to send it to database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @throws Chrome_DB_Exception
	 * @return void
	 */
	abstract protected function _prepare(Chrome_DB_Interface_Abstract & $obj = null);

	/**
	 *
	 * Sends the prepared query to database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @return void
	 */
	protected function _execute(Chrome_DB_Interface_Abstract & $obj = null)
	{
	}

	/**
	 *
	 * Gets the prepared statement
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @return query statement
	 */
	protected function _getStatement(Chrome_DB_Interface_Abstract & $obj = null)
	{

	}

	protected function _setSQLType(Chrome_DB_Interface_Abstract & $obj = null, $type)
	{
	    /*
            If no connection is established until now,
            create the default connection
        */
        if($this->_connectionID === null) {
            $this->initDefaultConnection();
        }

		$interfaceID = $obj->getID();
		if(isset($this->_SQLType[$interfaceID])) {
			throw new Chrome_Exception('SQL Type already set!');
		}

		$this->_SQLType[$interfaceID] = $type;
	}

	/**
	 *
	 * Cleans up a query, so you can reuse the interface again
	 *
	 * @return void
	 */
	protected function _clean()
	{
		$this->_SQLType = null;
	}

	/**
	 * Wrapper method for _clean
	 *
	 */
	protected function _clear()
	{
		$this->_clean();
	}

	/**
	 *
	 * Sends a query to database
	 *
	 * @param Chrome_DB_Interface_Abstract $obj [optional]
	 * @param string                       $query query
	 * @return void
	 */
	protected function _query(Chrome_DB_Interface_Abstract & $obj = null, $query)
	{

	}
}