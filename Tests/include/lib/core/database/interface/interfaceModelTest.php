<?php

;
require_once LIB . 'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';
require_once 'Tests/dummies/database/result.php';


class DatabaseInterfaceModelTest extends Chrome_TestCase
{
    protected function _getDatabaseFactory() {
        return $this->_appContext->getModelContext()->getDatabaseFactory();
    }

    public function testSetModel()
    {
        $db = $this->_getDatabaseFactory()->buildInterface('model', 'dummy');

        require_once 'Tests/dummies/database/interfaceModel.php';

        $model = new Chrome_Model_Database_Statement_Dummy();
        $model->_handler = $this;
        $this->assertSame($db, $db->setModel($model));
    }

    public function testloadQuery()
    {
        $con = new Chrome_Database_Connection_Dummy('not Null');

        $db = $this->_getDatabaseFactory()->buildInterface('model', 'dummy', $con);

        $model = new Chrome_Model_Database_Statement_Dummy();
        $model->_handler = $this;

        $db->setModel($model);

        $db->loadQuery('anyKey');

        $db->execute();

        $this->assertEquals('any string', $db->getQuery());

        $this->setExpectedException('\Chrome\DatabaseException');
        $db->loadQuery('anything else');
    }

    public function testDefaultModelImplementation()
    {

        $con = new Chrome_Database_Connection_Dummy('not Null');

        $db = $this->_getDatabaseFactory()->buildInterface('model', 'dummy', $con);

        $this->setExpectedException('\Chrome\Exception');

        $db->loadQuery('notExisting');
    }

    public function getStatement($key)
    {
        if($key === 'anyKey') {

            return 'any string';

        } else {
            throw new \Chrome\Exception('Just testing');
        }
    }


}
