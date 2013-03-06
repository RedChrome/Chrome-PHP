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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [06.03.2013 21:00:52] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Factory_Interface
{
	const DEFAULT_ADAPTER   = '',
	      DEFAULT_RESULT    = '',
          DEFAULT_INTERFACE = '';

	public function __construct(Chrome_Database_Registry_Connection_Interface $connectionRegistry, Chrome_Database_Registry_Statement_Interface $statementRegistry);

	public function buildInterface($interfaceName, $resultName, $connectionName = Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION, $adapterName = self::DEFAULT_ADAPTER);

	public function buildInterfaceViaComposition(Chrome_Database_Composition_Interface $requiredcomp, Chrome_Database_Composition_Interface $comp = null);

	public function getConnectionRegistry();

	public function setConnectionRegistry(Chrome_Database_Registry_Connection_Interface $connectionRegistry);

	public function setStatementRegistry(Chrome_Database_Registry_Statement_Interface $statementRegistry);

	public function getStatementRegistry();

	public function setDefaultInterfaceSuffix($interfaceNameSuffix);

	public function setDefaultResultSuffix($resultNameSuffix);

	public function getDefaultInterfaceClass();

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
