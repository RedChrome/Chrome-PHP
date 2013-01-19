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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [28.12.2012 18:00:13] --> $
 * @author     Alexander Book
 */
require_once 'testsetup.php';

require_once LIB.'core/database/database.php';
require_once LIB.'core/database/connection/mysqli.php';
Chrome_Database_Facade::setDefaultConnection('testingConnection');

// configure default database connection
try {
    $defaultConnection = new Chrome_Database_Connection_Mysqli();
    $defaultConnection->setConnectionOptions('localhost', 'test', '', 'chrome_2_test');
    $defaultConnection->connect();
} catch(Chrome_Exception $e) {
    die($e->show($e));
} catch(Exception $e) {
    die(var_dump($e));
}

$dbRegistry = Chrome_Database_Registry_Connection::getInstance();
$dbRegistry->addConnection(Chrome_Database_Facade::getDefaultConnection(), $defaultConnection, true);

if(TEST_DATABASE_CONNECTIONS === true) {

    Chrome_Database_Facade::requireClass('connection', 'mysql');
    $mysqlTestConnection = new Chrome_Database_Connection_Mysql();
    $mysqlTestConnection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
    $mysqlTestConnection->connect();
    $dbRegistry->addConnection('mysql_test', $mysqlTestConnection, true);

    Chrome_Database_Facade::requireClass('connection', 'mysqli');
    $mysqliTestConnection = new Chrome_Database_Connection_Mysqli();
    $mysqliTestConnection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
    $mysqliTestConnection->connect();
    $dbRegistry->addConnection('mysqli_test', $mysqliTestConnection, true);
}
