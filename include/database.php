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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.11.2012 01:11:08] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

require_once LIB.'core/database_new/connection/mysql.php';

// configure default database connection
$defaultConnection = new Chrome_Database_Connection_Mysql();
$defaultConnection->setConnectionOptions(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$dbRegistry = Chrome_Database_Registry_Connection::getInstance();
$dbRegistry->addConnection(Chrome_Database_Facade::DEFAULT_CONNECTION, $defaultConnection);