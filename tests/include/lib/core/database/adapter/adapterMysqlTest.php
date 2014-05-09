<?php

class DatabaseAdapterMysqlTest extends Chrome_TestCase
{
    protected $_db;

    public function setUp()
    {
        try {
            $this->_db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc', 'mysql_test');
        }
        catch (\Chrome\Exception $e) {
            $this->_db = null;
        }
    }

    public function doSkipTestsIfNeeded()
    {
        if($this->_db === null OR _skipDatabaseTest(get_class($this))) {
            $this->markTestSkipped();
        }
    }

    public function testIsEmptyOnEmptyResultSet()
    {
        $this->doSkipTestsIfNeeded();

        $this->_db->query('SELECT * FROM cpp_autoload WHERE true = false');

        $this->assertTrue($this->_db->getAdapter()->isEmpty());

        $this->assertEquals(0, $this->_db->getAdapter()->getAffectedRows());
    }

    public function testIsEmptyOnNotEmptyResultSet()
    {
        $this->doSkipTestsIfNeeded();

        $this->_db->query('SELECT * FROM cpp_autoload LIMIT 0,2');

        $this->assertFalse($this->_db->getAdapter()->isEmpty());
        $this->assertEquals(2, $this->_db->getAdapter()->getAffectedRows());
    }

    public function testGetAffectedRows()
    {
        $this->doSkipTestsIfNeeded();

        $this->_db->query('DELETE FROM cpp_user_regist LIMIT 0');

        $this->assertTrue($this->_db->getAdapter()->isEmpty());

        // this depends on your mysql server version...
        $this->assertGreaterThanOrEqual(0, $this->_db->getAdapter()->getAffectedRows());
    }

    public function testExceptionIsThrownOnWrongQuery()
    {
        $this->doSkipTestsIfNeeded();

        $this->setExpectedException('\Chrome\DatabaseException');

        $this->_db->query('SELECT * FROM cpp_autoload LIgMIT 0,1');
    }

    public function testBehaviourOnWrongQuery()
    {

        $this->doSkipTestsIfNeeded();

        // do not log the exception, we're expecting it
        $this->_db->setLogger(new \Psr\Log\NullLogger());
        try {
            $this->_db->query('SELECT * FROM cpp_autoload LIgMIT 0,1');
        }
        catch (\Chrome\DatabaseException $e) {
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
        $this->_db->setLogger(new \Psr\Log\NullLogger());
        try {
            $this->_db->query('SELEC * FROM cpp_autoload LIMIT 0,1'); // this will result in an exception
        }
        catch (\Chrome\DatabaseException $e) {
            // do nothing
        }

        $this->assertFalse($this->_db->getAdapter()->getNext());
    }

    public function testEscaping()
    {
        $this->doSkipTestsIfNeeded();
        // not escaped var => escaped var
        $array = array(
            'notEscaped' => 'notEscaped',
            'cpp_autoload`' => 'cpp_autoload`',
            '\'test\'' => '\\\'test\\\'',
            '"test"' => '\"test\"',
            'query---' => 'query---',
            '!"�$%&/()=?\\' => '!\"�$%&/()=?\\\\');

        foreach($array as $key => $value) {
            $this->assertEquals($value, $this->_db->getAdapter()->escape($key));
        }
    }

    public function testGetNext()
    {

        $this->doSkipTestsIfNeeded();

        $this->_db->query('SELECT * FROM cpp_autoload');

        $exists = false;

        $resultSet = $this->_db->getResult();

        while(($result = $resultSet->getNext()) !== false) {
            $exists = true;
            if(!is_file(BASEDIR.'/'.$result['path'])) {
                $this->assertFalse(true, 'path '.BASEDIR.'/'.$result['path'].' does not exist, or database has wrong elements in table cpp_autoload or getNext() does not work');
            }
        }

        $this->assertTrue($exists, 'no element found in table cpp_autoload? cannot be?');
    }

    public function testGetLastInsertedIdOnQueryNotContainingInsert()
    {
        $this->doSkipTestsIfNeeded();

        $this->_db->query('SELECT * FROM cpp_autoload LIMIT 0,1');

        $this->assertNull($this->_db->getResult()->getLastInsertId());

        $this->_db->clear()->query('SELECT COUNT(id) FROM testing ');

        $id = $this->_db->getResult()->getNext();

        $this->_db->clear()->query('INSERT INTO  `testing` ( id, var1) VALUES ( NULL , "testing getLastInsertId" )');

        $this->assertEquals($id['COUNT(id)'] + 1, $this->_db->getResult()->getLastInsertId());
    }

    public function testSetConnectionAndCannotConnect()
    {
        $this->doSkipTestsIfNeeded();

        $connection = new \Test\Chrome\Database\Connection\Dummy();
        $connection->_isConnected = false;

        $this->setExpectedException('\Chrome\DatabaseException');

        $this->_db->getAdapter()->setConnection($connection);
    }

    public function testSetConnectionAndConnectionIsNull()
    {
        $this->doSkipTestsIfNeeded();

        $connection = new \Test\Chrome\Database\Connection\Dummy();
        $connection->_isConnected = true;
        $connection->_connection = null;

        $this->setExpectedException('\Chrome\DatabaseException');
        $this->_db->getAdapter()->setConnection($connection);
    }

    public function testSetConnectionAndConnectionIsNotEstablishedTheFirstTime()
    {
        $this->doSkipTestsIfNeeded();

        $connection = new \Test\Chrome\Database\Connection\Dummy();
        $connection->_isConnected = true;
        $connection->_connection = 'myConnectionTest';
        // call handleConnection on connect()
        $connection->_connectionHandler = $this;

        $this->_db->getAdapter()->setConnection($connection);
        $this->assertEquals($connection, $this->_db->getAdapter()->getConnection());
    }

    public function handleConnection(\Chrome\Database\Connection\Connection_Interface $con)
    {
        $con->_isConnected = true;
    }

    public function setExpectedException($string, $exceptionMessage = '', $exceptionCode = 0)
    {
        $this->doSkipTestsIfNeeded();

        // do not log the exception, we're expecting it
        $this->_db->setLogger(new \Psr\Log\NullLogger());
        parent::setExpectedException($string, $exceptionMessage, $exceptionCode);
    }

}
