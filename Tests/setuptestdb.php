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

error_reporting(E_ALL);

require_once 'phpUnit/testsetup.php';

$testsetup = new Chrome_TestSetup();
$testsetup->testDb();

function applySQLQueries($query, Chrome_Database_Interface_Interface $db)
{
    if($query == false)
    {
        die('Query string is empty');
    }

    $queries = explode(';' . PHP_EOL, $query);

    foreach($queries as $_query)
    {
        if(trim($_query) == '')
        {
            continue;
        }

        $db->query($_query);
        $db->clear();
        echo '.';
    }
    echo PHP_EOL.PHP_EOL;
}

$databaseFactory = $testsetup->getApplicationContext()->getModelContext()->getDatabaseFactory();
$connectionRegistry = $databaseFactory->getConnectionRegistry();
$connection = 'default';

if(isset($_SERVER['argv']) and isset($_SERVER['argv'][1]))
{
    if($connectionRegistry->isExisting($_SERVER['argv'][1]))
    {
        $connection = $_SERVER['argv'][1];
    } else
    {
        echo 'Could not find connection name "' . $_SERVER['argv'][1] . '"' . PHP_EOL;
        echo 'Using default connection "'.$connection.'"'.PHP_EOL;
        echo 'Available connection names:' . PHP_EOL;
        foreach($connectionRegistry->getConnections() as $connectionName)
        {
            echo ' - ' . $connectionName . PHP_EOL;
        }
        echo PHP_EOL;
    }
}

$db = $databaseFactory->buildInterface('simple', 'assoc', $connection);

echo 'applying product.sql using connection "' . $connection . '"' . PHP_EOL;
applySQLQueries(file_get_contents('Tests/sql/product.sql'), $db);
echo 'applying deltaProductTest.sql:' . PHP_EOL;
applySQLQueries(file_get_contents('Tests/sql/deltaProductTest.sql'), $db);
echo 'done';
