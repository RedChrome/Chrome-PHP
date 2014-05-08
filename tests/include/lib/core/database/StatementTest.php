<?php

require_once LIB.'core/database/database.php';

class DatabaseStatementTest extends PHPUnit_Framework_TestCase
{
    protected $_statementRegistry;

    protected $_random;

    public function setUp()
    {
        $this->_statementRegistry = new \Chrome\Database\Registry\Statement();
        $this->_random = 0;
    }

    protected function _addRandomStatements($min, $max)
    {
        $this->_random = mt_rand($min, $max);
        for($i = 1; $i <= $this->_random; ++$i) {
            $this->_statementRegistry->addStatement('Random created sql statement, just testing _ '.mt_rand());
        }
    }

    public function testCountIsValid()
    {
        $this->_addRandomStatements(0, 100);

        $this->assertEquals($this->_random, $this->_statementRegistry->count());
    }

    public function testGetLastStatement()
    {
        $this->_addRandomStatements(1, 10);

        $string = 'This should be my last statement';

        $this->_statementRegistry->addStatement($string);

        $this->assertEquals($string, $this->_statementRegistry->getLastStatement());
    }

    public function testGetStatement()
    {
        $this->_addRandomStatements(5, 20);

        $string = 'This should be my sql statement which i want to get again';

        $this->_statementRegistry->addStatement($string);

        $count = $this->_statementRegistry->count();

        $this->_addRandomStatements(1, 10);

        $this->assertEquals($string, $this->_statementRegistry->getStatement($count));
    }

    public function testGetStatementsIsNotEmpty()
    {
        $string = 'Test statement which should be in getStatements()';
        $this->_statementRegistry->addStatement($string);

        $this->assertNotEmpty($this->_statementRegistry->getStatements());

        $this->assertContains($string, $this->_statementRegistry->getStatements());
    }
}
