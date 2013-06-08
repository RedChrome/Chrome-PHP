<?php
error_reporting(E_ALL);

require_once 'phpUnit/testsetup.php';

$testsetup = new Chrome_TestSetup();
$testsetup->testDb();

function applySQLQueries($query, Chrome_Database_Factory_Abstract $databaseFactory) {

    echo 'executing queries...'."\n";

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

applySQLQueries(file_get_contents('Tests/sql/product.sql'), $databaseFactory);
applySQLQueries(file_get_contents('Tests/sql/deltaProductTest.sql'), $databaseFactory);

