<?php

namespace Test\Chrome\Database;

require_once LIB . 'core/database/database.php';

class InitializeTest extends \PHPUnit_Framework_TestCase
{
    protected $_init = null;

    public function setUp()
    {
        $this->_init = new \Chrome\Database\Initializer\Initializer();
    }

    public function testGetFactory()
    {
        $this->assertNull($this->_init->getFactory());

        $this->_init->initialize();

        $this->assertTrue($this->_init->getFactory() instanceof \Chrome\Database\Factory\Factory);
    }
}
