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

namespace Chrome\Database\Factory;

use \Chrome\Logger\Loggable_Interface;
use \Psr\Log\LoggerInterface;

/**
 * Interface for all database factories
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Factory_Interface extends Loggable_Interface
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
     * @param \Chrome\Database\Registry\Connection_Interface $connectionRegistry used to retrieve database connections
     * @param \Chrome\Database\Registry\Statement_Interface $statementRegistry used to save sent statements/queries
     * @return \Chrome\Database\Factory\Factory_Interface
     */
    public function __construct(\Chrome\Database\Registry\Connection_Interface $connectionRegistry, \Chrome\Database\Registry\Statement_Interface $statementRegistry);

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
     *      string: connection name stored in $connectionRegistry, given in constructor or {@link \Chrome\Database\Registry\Connection_Interface::DEFAULT_CONNECTION} for the default connection
     *      \Chrome\Database\Connection\Connection_Interface: an existing connection
     *
     * @param string $adapterName an adapter suffix, compatible with the given connectionName. The compatibility is not getting tested! If you dont know what you should
     *               choose as adapter suffix, take {@link DEFAULT_ADAPTER}
     *
     * @return \Chrome\Database\Facade\Facade_Interface initialized with the given parameters
     */
    public function buildInterface($interfaceName, $resultName, $connectionName = \Chrome\Database\Registry\Connection_Interface::DEFAULT_CONNECTION, $adapterName = self::DEFAULT_ADAPTER);

    /**
     * Creates a new database interface instance using the given database composition
     *
     * This returns an interface instance initialized with the required functionality. If $comp is not null, then the interface instance might
     * have more functionality than required in $requiredComp
     *
     * @param \Chrome\Database\Composition_Interface $requiredComp containing information about the required functionality of interface/result/connection/adapter
     * @param \Chrome\Database\Composition_Interface $comp [optional] containing information about which interface/adapter... should get used
     *
     * @return \Chrome\Database\Facade\Facade_Interface initialized with the given parameters
     */
    public function buildInterfaceViaComposition(\Chrome\Database\Composition_Interface $requiredComp, \Chrome\Database\Composition_Interface $comp = null);

    /**
     * Returns the connection registry which is used by this factory
     *
     * @return \Chrome\Database\Registry\Connection_Interface
     */
    public function getConnectionRegistry();

    /**
     * Sets a new connection registry
     *
     * @param \Chrome\Database\Registry\Connection_Interface $connectionRegistry
     * @return void
     */
    public function setConnectionRegistry(\Chrome\Database\Registry\Connection_Interface $connectionRegistry);

    /**
     * Sets a new statement registry
     *
     * @param \Chrome\Database\Registry\Statement_Interface $statementRegistry
     * @return void
     */
    public function setStatementRegistry(\Chrome\Database\Registry\Statement_Interface $statementRegistry);

    /**
     * Returns the statement registry which is used by this factory
     *
     * @return \Chrome\Database\Registry\Statement_Interface
     */
    public function getStatementRegistry();

    /**
     * Sets the default interface class.
     *
     * This interface class will be used if you call buildInterface with {@link DEFAULT_INTERFACE}
     *
     * @param string $interfaceName the name of an interface class E.g. \Chrome\Database\Facade\Simple
     * @return void
     */
    public function setDefaultInterface($interfaceName);

    /**
     * Sets the default result class.
     *
     * This interface class will be used if you call buildInterface with {@link DEFAULT_RESULT}
     *
     * @param string $resultName the name of an result class E.g. \Chrome\Database\Result\Assoc
     * @return void
     */
    public function setDefaultResult($resultName);

    /**
     * Returns the default interface class. NOT the suffix.
     *
     * Returns for default class name E.g. \Chrome\Database\Facade\Simple
     *
     * @return string default interface class
     */
    public function getDefaultInterfaceClass();

    /**
     * Returns the default result class. NOT the suffix.
     *
     * Returns for default class name E.g. \Chrome\Database\Result\Assoc
     *
     * @return string default result class
     */
    public function getDefaultResultClass();
}

/**
 * An implementation of the basic methods for a database factory
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
abstract class AbstractFactory implements Factory_Interface
{
    protected $_connectionRegistry = null;

    protected $_statementRegistry  = null;

    protected $_logger = null;

    public function __construct(\Chrome\Database\Registry\Connection_Interface $connectionRegistry, \Chrome\Database\Registry\Statement_Interface $statementRegistry)
    {
        $this->setConnectionRegistry($connectionRegistry);
        $this->setStatementRegistry($statementRegistry);
    }

    public function setConnectionRegistry(\Chrome\Database\Registry\Connection_Interface $connectionRegistry)
    {
        $this->_connectionRegistry = $connectionRegistry;
    }

    public function getConnectionRegistry()
    {
        return $this->_connectionRegistry;
    }

    public function setStatementRegistry(\Chrome\Database\Registry\Statement_Interface $statementRegistry)
    {
        $this->_statementRegistry = $statementRegistry;
    }

    public function getStatementRegistry()
    {
        return $this->_statementRegistry;
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

/**
 * A complete implementation of a database factory
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Factory extends AbstractFactory
{
    protected $_defaultInterface = '\Chrome\Database\Facade\Simple';

    protected $_defaultResult    = '\Chrome\Database\Result\Assoc';

    public function buildInterface($interfaceName, $resultName, $connectionName = \Chrome\Database\Registry\Connection_Interface::DEFAULT_CONNECTION, $adapterName = self::DEFAULT_ADAPTER)
    {
        try {
            $connection = $this->_getConnection($connectionName);

            // create adapter, set connection
            $adapter = $this->_createAdapter($adapterName, $connection);

            // create result using adapter
            $result = $this->_createResult($resultName, $adapter);

            // create interface with adapter and result
            $interface = $this->_createInterface($interfaceName, $result, $adapter);

            if($this->_logger !== null) {
                $interface->setLogger($this->_logger);
            }

            return $interface;
        } catch(\Chrome\Exception $e) {
            if($this->_logger !== null) {
                $this->_logger->error($e);
            }
            throw $e;
        }
    }

    protected function _createAdapter($adapterName, \Chrome\Database\Connection\Connection_Interface $connection)
    {
        if($adapterName === self::DEFAULT_ADAPTER or $adapterName === null) {
            $adapterName = $connection->getDefaultAdapter();
        }

        return new $adapterName($connection);
    }

    protected function _createResult($resultName, \Chrome\Database\Adapter\Adapter_Interface $adapter)
    {
        if($resultName === self::DEFAULT_RESULT or $resultName === null) {
            $resultName = array($this->_defaultResult);
        } elseif(!is_array($resultName)) {
            $resultName = array($resultName);
        }

        $result = $adapter;

        foreach(array_reverse($resultName) as $resultClass) {
            //$resultClass = self::requireClass('result', $value);
            //$resultClass = 'Chrome_Database_Result_'.ucfirst($value);
            $newResult = new $resultClass();
            $newResult->setAdapter($result);
            $result = $newResult;
        }

        return $result;
    }

    protected function _createInterface($interfaceName, \Chrome\Database\Result\Result_Interface $result, \Chrome\Database\Adapter\Adapter_Interface $adapter)
    {
        if($interfaceName === self::DEFAULT_INTERFACE or $interfaceName === null) {
            $interfaceName = $this->_defaultInterface;
        }

        return new $interfaceName($adapter, $result, $this->_statementRegistry);
    }

    protected function _getConnection($connectionName)
    {
        if($connectionName instanceof \Chrome\Database\Connection\Connection_Interface) {
            return $connectionName;
        }

        if($connectionName === null) {
            $connectionName = \Chrome\Database\Registry\Connection_Interface::DEFAULT_CONNECTION;
        }

        return $this->_connectionRegistry->getConnectionObject($connectionName);
    }

    public function buildInterfaceViaComposition(\Chrome\Database\Composition_Interface $requiredcomp, \Chrome\Database\Composition_Interface $comp = null)
    {
        if($comp === null) {
            $composition = $requiredcomp;
        } else {
            $composition = $requiredcomp->merge($requiredcomp, $comp);
        }

        $connection = ($composition->getConnection() === null) ? \Chrome\Database\Registry\Connection_Interface::DEFAULT_CONNECTION : $composition->getConnection();

        return $this->buildInterface($composition->getInterface(), $composition->getResult(), $connection, $composition->getAdapter());
    }

    public function setDefaultInterface($interfaceName)
    {
        $this->_defaultInterface = $interfaceName;
    }

    public function setDefaultResult($resultName)
    {
        $this->_defaultResult = $resultName;
    }

    public function getDefaultInterfaceClass()
    {
        return $this->_defaultInterface;
    }

    public function getDefaultResultClass()
    {
        return $this->_defaultResult;
    }
}
