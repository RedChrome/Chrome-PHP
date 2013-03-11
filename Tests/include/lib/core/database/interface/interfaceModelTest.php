<?php

require_once 'Tests/testsetup.php';
require_once LIB . 'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';
require_once 'Tests/dummies/database/result.php';


class DatabaseInterfaceModelTest extends Chrome_TestCase
{
    public function testSetModel()
    {
        $db = $this->_appContext->getDatabaseFactory()->buildInterface('model', 'dummy');

        require_once 'Tests/dummies/database/interfaceModel.php';

        $model = new Chrome_Model_Database_Statement_Dummy();
        $model->_handler = $this;
        $this->assertSame($db, $db->setModel($model));
    }

    public function testPrepare()
    {
        $con = new Chrome_Database_Connection_Dummy('not Null');

        $db = $this->_appContext->getDatabaseFactory()->buildInterface('model', 'dummy', $con);

        $model = new Chrome_Model_Database_Statement_Dummy();
        $model->_handler = $this;

        $db->setModel($model);

        $db->prepare('anyKey');

        $db->execute();

        $this->assertEquals('any string', $db->getQuery());

        $this->setExpectedException('Chrome_Exception_Database');
        $db->prepare('anything else');
    }

    public function testDefaultModelImplementation()
    {

        $con = new Chrome_Database_Connection_Dummy('not Null');

        $db = $this->_appContext->getDatabaseFactory()->buildInterface('model', 'dummy', $con);

        $this->setExpectedException('Chrome_Exception');

        $db->prepare('notExisting');
    }

    public function getStatement($key)
    {
        if($key === 'anyKey') {

            return 'any string';

        } else {
            throw new Chrome_Exception('Just testing');
        }
    }


}
