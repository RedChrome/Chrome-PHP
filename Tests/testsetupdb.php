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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.03.2013 16:24:35] --> $
 * @author     Alexander Book
 */
require_once 'testsetup.php';

require_once LIB.'core/database/database.php';

new Chrome_Database_Loader();

// configure default database connection
try {
    $defaultConnectionClass = 'Chrome_Database_Connection_'.ucfirst(CHROME_DATABASE);
    $defaultConnection = new $defaultConnectionClass();
    $defaultConnection->setConnectionOptions('localhost', 'test', '', 'chrome_2_test');
    $defaultConnection->connect();
} catch(Chrome_Exception $e) {
    die($e->show($e));
} catch(Exception $e) {
    die(var_dump($e));
}

$dbRegistry = new Chrome_Database_Registry_Connection();
$dbRegistry->addConnection(Chrome_Database_Registry_Connection::DEFAULT_CONNECTION, $defaultConnection, true);

$databaseFactory = new Chrome_Database_Factory($dbRegistry, new Chrome_Database_Registry_Statement());

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

        public function __construct($name = NULL, array $data = array(), $dataName = '') {

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