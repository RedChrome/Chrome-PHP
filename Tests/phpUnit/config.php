<?php
$_SERVER['REQUEST_URI'] = '/root/CHROME_2/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla Firefox 5.0';
$_SERVER['SCRIPT_NAME'] = 'index.php';
$_SERVER['SERVER_NAME'] = "localhost";

define('TEST_DATABASE_CONNECTIONS', true);

if(getenv('TRAVIS') == true)
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

    define('POSTGRESQL_HOST', 'localhost');
    define('POSTGRESQL_USER', 'test');
    define('POSTGRESQL_PASS', 'chrome');
    define('POSTGRESQL_DB', 'chrome_db');
    define('POSTGRESQL_SCHEMA', 'chrome');
    // 5433 -> 9.1, 5432 -> 9.2, 5434 -> 9.3
    define('POSTGRESQL_PORT', 5433);
}

function _skipDatabaseTest($class)
{
    $toSkipTests = array();
    return in_array($class, $toSkipTests);
}
