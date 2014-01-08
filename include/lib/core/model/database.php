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

abstract class Chrome_Model_Database_Abstract extends Chrome_Model_Abstract
{
    protected $_dbAdapter = Chrome_Database_Factory_Interface::DEFAULT_ADAPTER;
    protected $_dbInterface = Chrome_Database_Factory_Interface::DEFAULT_INTERFACE;
    protected $_dbResult = Chrome_Database_Factory_Interface::DEFAULT_RESULT;
    protected $_dbConnection = Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION;

    private $_dbInterfaceInstance = null;

    protected $_dbFactory = null;

    /**
     * @param Chrome_Context_Model_Interface $modelContext
     */
    public function __construct(Chrome_Database_Factory_Interface $databaseFactory)
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
     * @return Chrome_Database_Interface_Interface
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

abstract class Chrome_Model_Database_Statement_Abstract extends Chrome_Model_Database_Abstract
{
    protected $_dbStatementModel = null;

    protected $_dbInterface = 'model';

    public function __construct(Chrome_Database_Factory_Interface $factory, Chrome_Model_Database_Statement_Interface $statementModel)
    {
        parent::__construct($factory);
        $this->_dbStatementModel = $statementModel;
    }

    protected function _connect()
    {
        $interfaceInstance = parent::_connect();

        if($interfaceInstance instanceof Chrome_Database_Interface_Model_Interface) {
            $interfaceInstance->setModel($this->_dbStatementModel);
        }
    }
}
