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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.04.2013 19:11:34] --> $
 * @author     Alexander Book
 */
if(!defined('CHROME_TEST_ENVIRONMENT')) {
    define('CHROME_TEST_ENVIRONMENT', 2);
}

require_once 'testsetup.php';

// autoloader
global $errorConfig;
require_once PLUGIN.'Log/null.php';
require_once PLUGIN.'Log/database.php';
require_once PLUGIN.'Require/database.php';
$autoloader = new Chrome_Require_Autoloader();
$autoloader->setExceptionHandler($errorConfig->getExceptionHandler());
$autoloader->setLogger(new Chrome_Logger_Null());
$autoloader->appendAutoloader(new Chrome_Require_Loader_Database());

require_once LIB.'core/database/database.php';

// configure default database connection
try {
    $defaultConnectionClass = 'Chrome_Database_Connection_'.ucfirst(CHROME_DATABASE);
    $defaultConnection = new $defaultConnectionClass();
    $defaultConnection->setConnectionOptions('localhost', 'test', '', 'chrome_2_test');
    $defaultConnection->connect();
} catch(Exception $e) {
    die(var_dump($e));
}

$dbRegistry = new Chrome_Database_Registry_Connection();
$dbRegistry->addConnection(Chrome_Database_Registry_Connection::DEFAULT_CONNECTION, $defaultConnection, true);

$databaseFactory = new Chrome_Database_Factory($dbRegistry, new Chrome_Database_Registry_Statement());
$databaseFactory->setLogger(new Chrome_Logger_Database());

if(TEST_DATABASE_CONNECTIONS === true) {

    $mysqlTestConnection = new Chrome_Database_Connection_Mysql();
    $mysqlTestConnection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
    $mysqlTestConnection->connect();
    $dbRegistry->addConnection('mysql_test', $mysqlTestConnection, true);

    $mysqliTestConnection = new Chrome_Database_Connection_Mysqli();
    $mysqliTestConnection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
    $mysqliTestConnection->connect();
    $dbRegistry->addConnection('mysqli_test', $mysqliTestConnection, true);
}

global $databaseContext;
$databaseContext = new Chrome_Application_Context();
$databaseContext->setDatabaseFactory($databaseFactory);

if(class_exists('PHPUnit_Framework_TestCase')) {

    abstract class Chrome_TestCase extends PHPUnit_Framework_TestCase
    {
        protected $_session, $_cookie, $_appContext;

        public function __construct($name = null, array $data = array(), $dataName = '') {

            global $databaseContext;
            global $applicationContext;

            if($applicationContext !== null) {

                $this->_appContext = $applicationContext;

                $this->_session = $this->_appContext->getRequestHandler()->getRequestData()->getSession();
                $this->_cookie  = $this->_appContext->getRequestHandler()->getRequestData()->getCookie();
            } else {
                $appContext = new Chrome_Application_Context();

                $appContext->setDatabaseFactory($databaseContext->getDatabaseFactory());

                $this->_appContext = $appContext;
            }


            parent::__construct($name, $data, $dataName);
        }
    }
}
