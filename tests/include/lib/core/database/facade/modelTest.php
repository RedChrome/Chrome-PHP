<?php

namespace Test\Chrome\Database\Facade;

use Mockery as M;

require_once LIB . 'core/database/database.php';
require_once 'tests/dummies/database/connection/dummy.php';
require_once 'tests/dummies/database/adapter.php';
require_once 'tests/dummies/database/result.php';

class ModelTest extends \Test\Chrome\TestCase
{
    protected function _getDatabaseFactory()
    {
        return $this->_appContext->getModelContext()->getDatabaseFactory();
    }

    public function testSetModel()
    {
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Model', '\Test\Chrome\Database\Result\Dummy');

        require_once 'tests/dummies/database/interfaceModel.php';

        $model = new \Test\Chrome\Model\Database\DummyStatement();
        $model->_handler = $this;
        $this->assertSame($db, $db->setModel($model));
    }

    public function testloadQuery()
    {
        $con = new \Test\Chrome\Database\Connection\Dummy('not Null');

        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Model', '\Test\Chrome\Database\Result\Dummy', $con);

        $model = new \Test\Chrome\Model\Database\DummyStatement();
        $model->_handler = $this;

        $db->setModel($model);

        $db->loadQuery('anyKey');

        $db->execute();

        $this->assertEquals('any string', $db->getQuery());

        $this->setExpectedException('\Chrome\Exception\Database');
        $db->loadQuery('anything else');
    }

    public function testDefaultModelImplementation()
    {
        $registry = M::mock('\Chrome\Database\Registry\Statement_Interface');
        $adapter = M::mock('\Chrome\Database\Adapter\Adapter_Interface');
        $result = M::mock('\Chrome\Database\Result\Result_Interface');

        $interface = new \Chrome\Database\Facade\Model($adapter, $result, $registry);

        $this->setExpectedException('\Chrome\Exception');
        $interface->loadQuery('notExisting');
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
