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

function applySQLQueries($query, Chrome_Database_Factory_Abstract $databaseFactory)
{
    if($query == false) {
        die('Query string is empty');
    }

    $queries = explode(";\r\n", $query); // use \n on windows systems
    $db = $databaseFactory->buildInterface('simple', 'assoc');

    foreach($queries as $_query) {

        if(trim($_query) == '') {
            continue;
        }


       $db->query($_query);
       $db->clear();
       echo '.';

    }
    echo "\n\n";
}

$databaseFactory = $testsetup->getApplicationContext()->getModelContext()->getDatabaseFactory();

echo 'applying product.sql:'."\n";
applySQLQueries(file_get_contents('Tests/sql/product.sql'), $databaseFactory);
echo 'applying deltaProductTest.sql:'."\n";
applySQLQueries(file_get_contents('Tests/sql/deltaProductTest.sql'), $databaseFactory);
echo 'done';
