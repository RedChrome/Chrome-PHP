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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [11.03.2013 12:15:55] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * Interface for all database factories
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Factory_Interface
{
    /**
     * Use this if you want to build an interface with any default adapter/result/interface
     *
     * The default interface and result can get set by {@link setDefaultInterfaceSuffix} and {@link setDefaultResultSuffix}.
     * The default adapter cannot get set, because the connection determines which adapter is the default one.
     * The default connection can get set in the connection registry.
     *
     * @var string
     */
	const DEFAULT_ADAPTER   = '',
	      DEFAULT_RESULT    = '',
          DEFAULT_INTERFACE = '';

    /**
     * Constructor
     *
     * @param Chrome_Database_Registry_Connection_Interface $connectionRegistry used to retrieve database connections
     * @param Chrome_Database_Registry_Statement_Interface $statementRegistry used to save sent statements/queries
     * @return Chrome_Database_Factory_Interface
     */
	public function __construct(Chrome_Database_Registry_Connection_Interface $connectionRegistry, Chrome_Database_Registry_Statement_Interface $statementRegistry);

    /**
     * Creates a new database interface instance using the given parameters
     *
     * Note: default connection and default adapter are compatible. Every result/interface is compatible with the adapter/connection.
     *
     * @param string $interfaceName suffix of an database interface class or {@link DEFAULT_INTERFACE} to use a default interface
     * @param mixed $resultName
     *      array:  containing suffix of database result classes. The numerical order in important: The last item has access to the adapter class, the
     *              next-to-last has access to the last etc... the first item has access to the second...
     *      string: suffix of a database result class or {@link DEFAULT_RESULT} to use the default result class
     *
     * @param mixed $connectionName
     *      string: connection name stored in $connectionRegistry, given in constructor or {@link Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION} for the default connection
     *      Chrome_Database_Connection_Interface: an existing connection
     *
     * @param string $adapterName an adapter suffix, compatible with the given connectionName. The compatibility is not getting tested! If you dont know what you should
     *               choose as adapter suffix, take {@link DEFAULT_ADAPTER}
     *
     * @return Chrome_Database_Interface_Interface initialized with the given parameters
     */
	public function buildInterface($interfaceName, $resultName, $connectionName = Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION, $adapterName = self::DEFAULT_ADAPTER);

    /**
     * Creates a new database interface instance using the given database composition
     *
     * This returns an interface instance initialized with the required functionality. If $comp is not null, then the interface instance might
     * have more functionality than required in $requiredComp
     *
     * @param Chrome_Database_Composition_Interface $requiredComp containing information about the required functionality of interface/result/connection/adapter
     * @param Chrome_Database_Composition_Interface $comp [optional] containing information about which interface/adapter... should get used
     *
     * @return Chrome_Database_Interface_Interface initialized with the given parameters
     */
	public function buildInterfaceViaComposition(Chrome_Database_Composition_Interface $requiredComp, Chrome_Database_Composition_Interface $comp = null);

    /**
     * Returns the connection registry which is used by this factory
     *
     * @return Chrome_Database_Registry_Connection_Interface
     */
	public function getConnectionRegistry();

    /**
     * Sets a new connection registry
     *
     * @param Chrome_Database_Registry_Connection_Interface $connectionRegistry
     * @return void
     */
	public function setConnectionRegistry(Chrome_Database_Registry_Connection_Interface $connectionRegistry);

    /**
     * Sets a new statement registry
     *
     * @param Chrome_Database_Registry_Statement_Interface $statementRegistry
     * @return void
     */
	public function setStatementRegistry(Chrome_Database_Registry_Statement_Interface $statementRegistry);

    /**
     * Returns the statement registry which is used by this factory
     *
     * @return Chrome_Database_Registry_Statement_Interface
     */
	public function getStatementRegistry();

    /**
     * Sets the default interface class.
     *
     * This interface class will be used if you call buildInterface with {@link DEFAULT_INTERFACE}
     *
     * @param string $interfaceNameSuffix the suffix of an interface class E.g. 'simple' for Chrome_Database_Interface_Simple
     * @return void
     */
	public function setDefaultInterfaceSuffix($interfaceNameSuffix);

    /**
     * Sets the default result class.
     *
     * This interface class will be used if you call buildInterface with {@link DEFAULT_RESULT}
     *
     * @param string $resultNameSuffix the suffix of an result class E.g. 'assoc' for Chrome_Database_Result_Assoc
     * @return void
     */
	public function setDefaultResultSuffix($resultNameSuffix);

    /**
     * Returns the default interface class. NOT the suffix.
     *
     * Returns for default suffix 'simple' the class name 'Chrome_Database_Interface_Simple'
     *
     * @return string default interface class
     */
	public function getDefaultInterfaceClass();

    /**
     * Returns the default result class. NOT the suffix.
     *
     * Returns for default suffix 'assoc' the class name 'Chrome_Database_Result_Assoc'
     *
     * @return string default result class
     */
	public function getDefaultResultClass();
}


abstract class Chrome_Database_Factory_Abstract implements Chrome_Database_Factory_Interface
{
	protected $_connectionRegistry = null;

	protected $_statementRegistry  = null;

	public function __construct(Chrome_Database_Registry_Connection_Interface $connectionRegistry, Chrome_Database_Registry_Statement_Interface $statementRegistry)
	{
		$this->setConnectionRegistry($connectionRegistry);
		$this->setStatementRegistry($statementRegistry);
	}

	public function setConnectionRegistry(Chrome_Database_Registry_Connection_Interface $connectionRegistry)
	{
		$this->_connectionRegistry = $connectionRegistry;
	}

	public function getConnectionRegistry()
	{
		return $this->_connectionRegistry;
	}

	public function setStatementRegistry(Chrome_Database_Registry_Statement_Interface $statementRegistry)
	{
		$this->_statementRegistry = $statementRegistry;
	}

	public function getStatementRegistry()
	{
		return $this->_statementRegistry;
	}
}

class Chrome_Database_Factory extends Chrome_Database_Factory_Abstract
{
	protected $_defaultInterface = 'simple';

	protected $_defaultResult    = 'assoc';

	public function buildInterface($interfaceName, $resultName, $connectionName = Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION, $adapterName = self::DEFAULT_ADAPTER)
	{
		$connection = $this->_getConnection($connectionName);

		// create adapter, set connection
		$adapter = $this->_createAdapter($adapterName, $connection);

		// create result using adapter
		$result = $this->_createResult($resultName, $adapter);

		// create interface with adapter and result
		$interface = $this->_createInterface($interfaceName, $result, $adapter);

		return $interface;
	}

	protected function _createAdapter($adapterName, Chrome_Database_Connection_Interface $connection)
	{
		if($adapterName === self::DEFAULT_ADAPTER or $adapterName === null) {
			$adapterName = $connection->getDefaultAdapterSuffix();
		}

		$adapterClass = 'Chrome_Database_Adapter_'.ucfirst($adapterName);

		return new $adapterClass($connection);
	}

	protected function _createResult($resultName, Chrome_Database_Adapter_Interface $adapter)
	{
		if($resultName === self::DEFAULT_RESULT or $resultName === null) {
			$resultName = array($this->_defaultResult);
		} elseif(!is_array($resultName)) {
			$resultName = array($resultName);
		}

		$result = $adapter;

		foreach(array_reverse($resultName) as $value) {
			//$resultClass = self::requireClass('result', $value);
			$resultClass = 'Chrome_Database_Result_'.ucfirst($value);
			$newResult = new $resultClass();
			$newResult->setAdapter($result);
			$result = $newResult;
		}

		return $result;
	}

	protected function _createInterface($interfaceName, Chrome_Database_Result_Interface $result, Chrome_Database_Adapter_Interface $adapter)
	{
		if($interfaceName === self::DEFAULT_INTERFACE or $interfaceName === null) {
			$interfaceName = $this->_defaultInterface;
		}

		$interfaceClass = 'Chrome_Database_Interface_'.ucfirst($interfaceName);

		return new $interfaceClass($adapter, $result, $this->_statementRegistry);
	}

	protected function _getConnection($connectionName)
	{
		if($connectionName instanceof Chrome_Database_Connection_Interface) {
			return $connectionName;
		}

		if($connectionName === null) {
			$connectionName = Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION;
		}

		return $this->_connectionRegistry->getConnectionObject($connectionName);
	}

	public function buildInterfaceViaComposition(Chrome_Database_Composition_Interface $requiredcomp, Chrome_Database_Composition_Interface $comp = null)
	{
		if($comp === null) {
			$composition = $requiredcomp;
		} else {
			$composition = $requiredcomp->merge($requiredcomp, $comp);
		}

		$connection = ($composition->getConnection() === null) ? Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION : $composition->getConnection();

		return $this->buildInterface($composition->getInterface(), $composition->getResult(), $connection, $composition->getAdapter());
	}

	public function setDefaultInterfaceSuffix($interfaceNameSuffix)
	{
		$this->_defaultInterface = $interfaceNameSuffix;
	}

	public function setDefaultResultSuffix($resultNameSuffix)
	{
		$this->_defaultResult = $resultNameSuffix;
	}

	public function getDefaultInterfaceClass()
	{
		return 'Chrome_Database_Interface_'.ucfirst($this->_defaultInterface);
	}

	public function getDefaultResultClass()
	{
		return 'Chrome_Database_Result_'.ucfirst($this->_defaultResult);
	}
}
