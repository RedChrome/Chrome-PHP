<?php

require_once 'testsetupmodules.php';


$query = file_get_contents('Tests/db.sql');

if($query == false) {
    die();
}

$interface = Chrome_DB_Interface_Factory::factory();
$registry = Chrome_DB_Registry::getInstance();
$con = $registry->getConnection($interface->getConnectionID());

$result = mysql_query($query, $con);

$queries = explode(';', $query);

foreach($queries as $_query) {

    if(trim($_query) == '') {
        continue;
    }

    $interface->query($_query);

}