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

    public function testGetAffectedRows()
    {
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

    public function testGetNext() {

        $this->_db->query('SELECT * FROM cpp_require');

        $exists = false;

        $resultSet = $this->_db->getResult();

        while( ($result = $resultSet->getNext()) !== false) {
            $exists = true;
            if(!is_file(BASEDIR.'/'.$result['path'])) {
                $this->assertFalse(true, 'path '.BASEDIR.'/'.$result['path'].' does not exist, or database has wrong elements in table cpp_require or getNext() does not work');
            }
        }

        $this->assertTrue($exists, 'no element found in table cpp_require? cannot be?');
    }

    public function testGetLastInsertedIdOnQueryNotContainingInsert()
    {
        $this->_db->query('SELECT * FROM cpp_require LIMIT 0,1');

        $this->assertNull($this->_db->getResult()->getLastInsertId());

        $this->_db->clear()->query('SELECT COUNT(id) FROM testing ');

        $id = $this->_db->getResult()->getNext();

        $this->_db->clear()->query('INSERT INTO  `testing` ( id, var1) VALUES ( NULL , "testing getLastInsertId" )');

        $this->assertEquals($id['COUNT(id)'] +1, $this->_db->getResult()->getLastInsertId());
    }
}
