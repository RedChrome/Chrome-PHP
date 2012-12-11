<?php

require_once 'Tests/testsetup.php';

require_once LIB . 'core/database/database.php';
require_once LIB . 'core/database/connection/mysql.php';

class DatabaseAdapterMysqlTest extends PHPUnit_Framework_TestCase
{
    protected $_db;

    public function setUp()
    {
        try {
            $this->_db = Chrome_Database_Facade::getInterface('simple', 'assoc', 'mysql_test');
        }
        catch (Chrome_Exception $e) {
            $this->_db = null;
        }
    }

    public function doSkipTestsIfNeeded()
    {
        if($this->_db === null) {
            $this->markTestSkipped();
        }
    }

    public function testIsEmptyOnEmptyResultSet()
    {
        $this->doSkipTestsIfNeeded();

        $this->_db->query('SELECT * FROM cpp_require WHERE true = false');

        $this->assertTrue($this->_db->getAdapter()->isEmpty());

        $this->assertEquals(0, $this->_db->getAdapter()->getAffectedRows());
    }

    public function testIsEmptyOnNotEmptyResultSet()
    {
        $this->doSkipTestsIfNeeded();

        $this->_db->query('SELECT * FROM cpp_require LIMIT 0,2');

        $this->assertFalse($this->_db->getAdapter()->isEmpty());
        $this->assertEquals(2, $this->_db->getAdapter()->getAffectedRows());
    }

    public function testGetAffectedRows() {

         $this->doSkipTestsIfNeeded();

        $this->_db->query('DELETE FROM cpp_user_regist LIMIT 1');

        $this->assertTrue($this->_db->getAdapter()->isEmpty());
        // this depends on your mysql server version...
        $this->assertGreaterThanOrEqual(0, $this->_db->getAdapter()->getAffectedRows());
    }

    public function testExceptionIsThrownOnWrongQuery()
    {
        $this->doSkipTestsIfNeeded();

        $this->setExpectedException('Chrome_Exception_Database');

        $this->_db->query('SELECT * FROM cpp_require LIgMIT 0,1');

    }

    public function testBehaviourOnWrongQuery() {

        $this->doSkipTestsIfNeeded();

        //$this->setExpectedException('Chrome_Exception_Database');
        try {
        $this->_db->query('SELECT * FROM cpp_require LIgMIT 0,1');
        } catch(Chrome_Exception_Database $e) {
            // do nothing
        }

        $this->assertEquals(0, $this->_db->getAdapter()->getAffectedRows());
        $this->assertNotEmpty($this->_db->getAdapter()->getErrorCode());
        $this->assertNotEmpty($this->_db->getAdapter()->getErrorMessage());
    }

    public function testGetNextOnWrongQuery()
    {
        $this->doSkipTestsIfNeeded();

        try {
            $this->_db->query('SELEC * FROM cpp_require LIMIT 0,1'); // this will result in an exception
        }
        catch (Chrome_Exception_Database $e) {
            // do nothing
        }

        $this->assertFalse($this->_db->getAdapter()->getNext());
    }

    public function testEscaping()
    {
        $this->doSkipTestsIfNeeded();
        // not escaped var => escaped var
        $array = array('notEscaped' => 'notEscaped',
                        'cpp_require`' => 'cpp_require`',
                        '\'test\'' => '\\\'test\\\'',
                        '"test"' => '\"test\"',
                        'query---' => 'query---',
                        '!"§$%&/()=?\\' => '!\"§$%&/()=?\\\\');

        foreach($array as $key => $value) {
            $this->assertEquals($value, $this->_db->getAdapter()->escape($key));
        }
    }

}
