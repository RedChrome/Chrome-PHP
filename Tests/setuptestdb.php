<?php

error_reporting(E_ALL);
// setting up, unimportant, just to get no notices from php
$_SERVER['REQUEST_URI'] = '/root/CHROME_2/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla Firefox 5.0';
$_SERVER['SCRIPT_NAME'] = 'index.php';
$_SERVER['SERVER_NAME'] = "localhost";

require_once 'testsetupdb.php';
require_once PLUGIN.'Log/database.php';

function applySQLQueries($query) {

    global $databaseContext;

    if($query == false) {
        die('Query string is empty');
    }

    $queries = explode(";\r\n", $query); // use \n on windows systems
    $db = $databaseContext->getDatabaseFactory()->buildInterface('simple', 'assoc');

    foreach($queries as $_query) {

        if(trim($_query) == '') {
            continue;
        }


       $db->query($_query);
       $db->clear();

    }
}

applySQLQueries(file_get_contents('Tests/sql/product.sql'));
applySQLQueries(file_get_contents('Tests/sql/deltaProductTest.sql'));

