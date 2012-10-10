<?php

// setting up, unimportant, just to get no notices from php
$_SERVER['REQUEST_URI'] = '/root/CHROME_2/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla Firefox 5.0';
$_SERVER['SCRIPT_NAME'] = 'index.php';
$_SERVER['SERVER_NAME'] = "localhost";

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