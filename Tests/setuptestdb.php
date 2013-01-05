<?php

// setting up, unimportant, just to get no notices from php
$_SERVER['REQUEST_URI'] = '/root/CHROME_2/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla Firefox 5.0';
$_SERVER['SCRIPT_NAME'] = 'index.php';
$_SERVER['SERVER_NAME'] = "localhost";

require_once 'testsetupdb.php';
require_once 'testsetup.php';
require_once 'testsetupmodules.php';


$query = file_get_contents('Tests/db.sql');

if($query == false) {
    die();
}

$queries = explode(';', $query);
$db = Chrome_Database_Facade::getInterface('simple', 'assoc');

foreach($queries as $_query) {

    if(trim($_query) == '') {
        continue;
    }

    $db->query($_query);
    $db->clear();
}