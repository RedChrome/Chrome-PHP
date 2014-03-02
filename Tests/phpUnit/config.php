<?php

define('TEST_DATABASE_CONNECTIONS', true);
define('CHROME_CLASSLOADER_ABSOLUTE_FILE_LOADING', false);

if(getenv('TRAVIS') == true)
{
    define('MYSQL_HOST', '127.0.0.1');
    define('MYSQL_USER', 'travis');
    define('MYSQL_PASS', '');
    define('MYSQL_DB', 'chrome_2_test');
} else
{
    define('MYSQL_HOST', '127.0.0.1');
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
