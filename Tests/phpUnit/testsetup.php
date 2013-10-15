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
 * @subpackage Chrome.Test
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */

/**
 * load additional testing config
 */
require_once 'config.php';

/**
 * load chrome-php initializing file
 */
require_once 'include/chrome.php';
class Chrome_TestSetup
{
    protected $_errorConfig = null;
    protected $_databaseInitialized = false;
    protected $_applicationContext = null;

    public function getApplicationContext()
    {
        return $this->_applicationContext;
    }

    public function __construct()
    {
        require_once 'bootstrap.php';

        $this->_errorConfig = new Chrome_Exception_Configuration();
        $this->_errorConfig->setErrorHandler(new Chrome_Exception_Error_Handler_Default());
        $this->_errorConfig->setExceptionHandler(new Chrome_Exception_Handler_Console());

        $this->_applicationContext = new Chrome_Context_Application();
    }

    public function testDb()
    {
        if($this->_databaseInitialized === true)
        {
            return;
        }

        $this->_databaseInitialized = true;

        require_once PLUGIN . 'classloader/database.php';
        $classloader = new \Chrome\Classloader\Classloader();
        $classloader->setExceptionHandler($this->_errorConfig->getExceptionHandler());
        $classloader->setLogger(new \Psr\Log\NullLogger());
        $classloader->appendResolver(new \Chrome\Classloader\Resolver_Database());
        $autoloader = new \Chrome\Classloader\Autoloader($classloader);

        require_once LIB . 'core/database/database.php';

        $dbRegistry = new Chrome_Database_Registry_Connection();

        // configure default database connection
        try
        {
            $defaultConnectionClass = 'Chrome_Database_Connection_' . ucfirst(CHROME_DATABASE);
            $defaultConnection = new $defaultConnectionClass();
            $defaultConnection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
            //$defaultConnection->connect();

            $dbRegistry->addConnection(Chrome_Database_Registry_Connection::DEFAULT_CONNECTION, $defaultConnection, true);
        } catch(Exception $e)
        {
            $this->_errorConfig->getExceptionHandler()->exception($e);
        }

        $databaseFactory = new Chrome_Database_Factory($dbRegistry, new Chrome_Database_Registry_Statement());
        $databaseFactory->setLogger(new \Psr\Log\NullLogger());

        // $databaseFactory->setLogger(new Chrome_Logger_Database());

        if(TEST_DATABASE_CONNECTIONS == true)
        {

            // configure default database connection
            // remove "#" in those lines, to connect to db at once. Now it will only connect if needed
            $mysqlTestConnection = new Chrome_Database_Connection_Mysql();
            $mysqlTestConnection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
            // mysqlTestConnection->connect();
            $dbRegistry->addConnection('mysql_test', $mysqlTestConnection, true);

            $mysqliTestConnection = new Chrome_Database_Connection_Mysqli();
            $mysqliTestConnection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
            // mysqliTestConnection->connect();
            $dbRegistry->addConnection('mysqli_test', $mysqliTestConnection, true);

            if(defined('POSTGRESQL_HOST'))
            {
                $postgresqlTestConnection = new Chrome_Database_Connection_Postgresql();
                $postgresqlTestConnection->setConnectionOptions(POSTGRESQL_HOST, POSTGRESQL_USER, POSTGRESQL_PASS, POSTGRESQL_DB, POSTGRESQL_PORT, POSTGRESQL_SCHEMA);
                #$postgresqlTestConnection->connect();
                $dbRegistry->addConnection('postgresql_test', $postgresqlTestConnection);
                #$dbRegistry->addConnection(Chrome_Database_Registry_Connection::DEFAULT_CONNECTION, $postgresqlTestConnection, true);
            }
        }

        $modelContext = new Chrome_Context_Model();
        $modelContext->setDatabaseFactory($databaseFactory);
        $this->_applicationContext = new Chrome_Context_Application();
        $this->_applicationContext->setModelContext($modelContext);
        $this->_applicationContext->setClassloader($classloader);
    }

    public function testModules()
    {
        $this->testDb();

        $_tempServer = $_SERVER;
        $_tempGlobals = $GLOBALS;
        $_tempCookie = $_COOKIE;

        require_once 'Tests/include/application/test.php';

        $application = new Chrome_Application_Test(new Chrome_Exception_Handler_Console());
        $application->setModelContext($this->_applicationContext->getModelContext());
        $application->init();

        $modelContext = $this->_applicationContext->getModelContext();
        $context = $application->getApplicationContext();

        $this->_applicationContext = clone $context;
        $this->_applicationContext->setModelContext($modelContext);

        $this->_applicationContext->getModelContext()->getDatabaseFactory()->setLogger($this->_applicationContext->getLoggerRegistry()->get('database'));

        $_SERVER = $_tempServer;
        $GLOBALS = $_tempGlobals;
        $_COOKIE = $_tempCookie;
    }
}
