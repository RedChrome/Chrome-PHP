<?php

require_once 'Tests/testsetupdb.php';

require_once LIB . 'core/database/database.php';
require_once LIB . 'core/database/connection/mysql.php';

require_once 'Tests/dummies/database/connection/dummy.php';

class DatabaseAdapterMysqlTest extends Chrome_TestCase
{
    protected $_db;

    public function setUp()
    {
        try {
            $this->_db = $this->_appContext->getDatabaseFactory()->buildInterface('simple', 'assoc', 'mysql_test');
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

        // do not log the exception, we're expecting it
        $this->_db->setLogger(new Chrome_Logger_Null());
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

        // do not log the exception, we're expecting it
        $this->_db->setLogger(new Chrome_Logger_Null());
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
                        '!"�$%&/()=?\\' => '!\"�$%&/()=?\\\\');

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

    public function testSetConnectionAndCannotConnect() {

        $connection = new Chrome_Database_Connection_Dummy();
        $connection->_isConnected = false;

        $this->setExpectedException('Chrome_Exception_Database');

        $this->_db->getAdapter()->setConnection($connection);
    }

    public function testSetConnectionAndConnectionIsNull() {

        $connection = new Chrome_Database_Connection_Dummy();
        $connection->_isConnected = true;
        $connection->_connection  = null;

        $this->setExpectedException('Chrome_Exception_Database');
        $this->_db->getAdapter()->setConnection($connection);
    }

    public function testSetConnectionAndConnectionIsNotEstablishedTheFirstTime() {

        $connection = new Chrome_Database_Connection_Dummy();
        $connection->_isConnected = true;
        $connection->_connection  = 'myConnectionTest';
        // call handleConnection on connect()
        $connection->_connectionHandler = $this;

        $this->_db->getAdapter()->setConnection($connection);
        $this->assertEquals($connection, $this->_db->getAdapter()->getConnection());
    }

    public function handleConnection(Chrome_Database_Connection_Interface $con) {
        $con->_isConnected = true;
    }

    public function setExpectedException($string, $exceptionMessage = '', $exceptionCode = 0) {
        // do not log the exception, we're expecting it
        $this->_db->setLogger(new Chrome_Logger_Null());
        parent::setExpectedException($string, $exceptionMessage, $exceptionCode);
    }

}