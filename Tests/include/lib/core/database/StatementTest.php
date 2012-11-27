<?php

require_once 'Tests/testsetup.php';

require_once LIB.'core/database/database.php';

class DatabaseStatementTest extends PHPUnit_Framework_TestCase
{
    public static $_count = 0;
    public static $_random = 0;

    public static function setUpBeforeClass() {

        self::$_count = Chrome_Database_Registry_Statement::count();

        self::$_random = mt_rand(5, 20);
        for($i = 1; $i <= self::$_random; ++$i) {
            Chrome_Database_Registry_Statement::addStatement($i.'. Statement');
        }
    }

    public function testCountIsValid() {
        $this->assertEquals(self::$_count + self::$_random, Chrome_Database_Registry_Statement::count());
    }

    public function testGetLastStatement() {

        $string = 'This should be my last statement';

        Chrome_Database_Registry_Statement::addStatement($string);

        $this->assertEquals($string, Chrome_Database_Registry_Statement::getLastStatement());
    }

    public function testGetStatement()
    {
        $string = 'This should be my sql statement which i want to get again';

        Chrome_Database_Registry_Statement::addStatement($string);

        $count = Chrome_Database_Registry_Statement::count();

        $random = mt_rand(5, 20);
        for($i = 1; $i <= $random; ++$i) {
            Chrome_Database_Registry_Statement::addStatement('Random created sql statement, just testing _ '.mt_rand());
        }

        $this->assertEquals($string, Chrome_Database_Registry_Statement::getStatement($count));
    }



}
