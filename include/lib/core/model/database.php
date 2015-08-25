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
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */

namespace Chrome\Model;

abstract class AbstractDatabase extends \Chrome\Model\AbstractModel
{
    protected $_dbAdapter = \Chrome\Database\Factory\Factory_Interface::DEFAULT_ADAPTER;
    protected $_dbInterface = \Chrome\Database\Factory\Factory_Interface::DEFAULT_INTERFACE;
    protected $_dbResult = \Chrome\Database\Factory\Factory_Interface::DEFAULT_RESULT;
    protected $_dbConnection = \Chrome\Database\Registry\Connection_Interface::DEFAULT_CONNECTION;

    private $_dbInterfaceInstance = null;

    protected $_dbFactory = null;

    /**
     * @param \Chrome\Context\Model_Interface $modelContext
     */
    public function __construct(\Chrome\Database\Factory\Factory_Interface $databaseFactory)
    {
        $this->_dbFactory = $databaseFactory;
    }

    public function setAdapter($adapter)
    {
        $this->_dbAdapter = $adapter;
    }

    public function setConnection($connection)
    {
        $this->_dbConnection = $connection;
    }

    protected function _connect()
    {
        $this->_setDatabaseOptions();

        $this->_dbInterfaceInstance = $this->_dbFactory->buildInterface($this->_dbInterface, $this->_dbResult, $this->_dbConnection, $this->_dbAdapter);

        return $this->_dbInterfaceInstance;
    }

    /**
     * Gets a new db interface instance
     *
     * If $clear is set to true, then the interface will get cleared.
     *
     * @param string $clear
     * @return \Chrome\Database\Facade\Facade_Interface
     */
    protected function _getDBInterface($clear = true)
    {
        if($this->_dbInterfaceInstance === null)
        {
            $this->_connect();
            // if the interface was created the first time, we dont need to call clear
        } elseif($clear === true)
        {
            $this->_dbInterfaceInstance->clear();
        }

        return $this->_dbInterfaceInstance;
    }

    /**
     * Put here your db connection settings
     * e.g.
     * $this->_dbInterface = 'simple'
     */
    protected function _setDatabaseOptions()
    {
    }
}

abstract class AbstractDatabaseStatement extends \Chrome\Model\AbstractDatabase
{
    protected $_dbStatementModel = null;

    protected $_dbInterface = '\Chrome\Database\Facade\Model';

    public function __construct(\Chrome\Database\Factory\Factory_Interface $factory, \Chrome\Model\Database\Statement_Interface $statementModel)
    {
        parent::__construct($factory);
        $this->_dbStatementModel = $statementModel;
    }

    protected function _connect()
    {
        $interfaceInstance = parent::_connect();

        // always true unless $_dbInterface is changed
        if($interfaceInstance instanceof \Chrome\Database\Facade\Model_Interface) {
            $interfaceInstance->setModel($this->_dbStatementModel);
        }
    }

    /**
     * @return Chrome\Database\Facade\Model_Interface
     */
    protected function _getDBInterface($clear = true)
    {
        // will always return Chrome\Database\Facade\Model_Interface if $_dbInterface is not changed
        // just for code completion, do not remove this
        return parent::_getDBInterface($clear);
    }
}
