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

$sqlScriptDir = 'tests/sql/';

$testsetup = new \Test\Chrome\TestSetup();
$testsetup->testDb();

function applySqlQueries($query, \Chrome\Database\Facade\Multiple $db)
{
    if($query == false)
    {
        die('Query string is empty');
    }

    $db->queries($query);

    echo PHP_EOL;
}

function getAvailableSqlScripts($databaseName)
{
    if(!is_dir($databaseName)) {
        die('No sql scripts found in dir '.$databaseName);
    }

    $files = scandir($databaseName, 0);
    $sqlScripts = array();

    foreach($files as $fileName)
    {
        $file = new \Chrome\File($fileName);
        if($file->hasExtension('sql'))
        {
            $sqlScripts[] = $fileName;
        }
    }

    return $sqlScripts;
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
        echo 'Could not find connection name "' . $_SERVER['argv'][1] . '"' . PHP_EOL.PHP_EOL;
        echo 'Registered connection names:' . PHP_EOL;
        foreach($connectionRegistry->getConnections() as $connectionName)
        {
            echo ' - ' . $connectionName . PHP_EOL;
        }
        echo PHP_EOL;
        exit();
    }
}

$db = $databaseFactory->buildInterface('\Chrome\Database\Facade\Multiple', '\Chrome\Database\Result\Assoc', $connection);
$suffix = $connectionRegistry->getConnectionObject($connection)->getDatabaseName();
$scripts = getAvailableSqlScripts($sqlScriptDir.$suffix);
$sqlScriptDir = $sqlScriptDir.$suffix.'/';

foreach($scripts as $sqlScript)
{
    echo 'applying '.$sqlScriptDir.$sqlScript.' using connection "' . $connection . '"';
    applySqlQueries(file_get_contents($sqlScriptDir.$sqlScript), $db);
}