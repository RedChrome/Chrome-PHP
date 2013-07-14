<?php
error_reporting(E_ALL);

require_once 'phpUnit/testsetup.php';

<<<<<<< Updated upstream
$testsetup = new Chrome_TestSetup();
$testsetup->testDb();
=======
    echo 'executing queries...'."\n";

    global $modelContext;
>>>>>>> Stashed changes

function applySQLQueries($query, Chrome_Database_Factory_Abstract $databaseFactory)
{
    if($query == false) {
        die('Query string is empty');
    }

    $queries = explode(";\r\n", $query); // use \n on windows systems
<<<<<<< Updated upstream
    $db = $databaseFactory->buildInterface('simple', 'assoc');
=======
    $db = $modelContext->getDatabaseFactory()->buildInterface('simple', 'assoc');
>>>>>>> Stashed changes

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
