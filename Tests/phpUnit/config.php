<?php
$_SERVER['REQUEST_URI'] = '/root/CHROME_2/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla Firefox 5.0';
$_SERVER['SCRIPT_NAME'] = 'index.php';
$_SERVER['SERVER_NAME'] = "localhost";

define('TEST_DATABASE_CONNECTIONS', true);

if(getenv('TRAVIS') !== false)
{
    define('MYSQL_HOST', '127.0.0.1');
    define('MYSQL_USER', 'travis');
    define('MYSQL_PASS', '');
    define('MYSQL_DB', 'chrome_2_test');
} else
{
    define('MYSQL_HOST', 'localhost');
    define('MYSQL_USER', 'test');
    define('MYSQL_PASS', '');
    define('MYSQL_DB', 'chrome_2_test');
    define('MYSQL_PORT', 3306);
}

var_dump(getenv('TRAVIS'), MYSQL_USER);