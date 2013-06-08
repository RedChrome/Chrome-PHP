<?php

;
require_once LIB . 'core/database/database.php';

class DatabaseInitializeTest extends PHPUnit_Framework_TestCase
{
    protected $_init = null;

    public function setUp() {
        $this->_init = new Chrome_Database_Initializer();
    }

    public function testGetFactory() {

        $this->assertNull($this->_init->getFactory());

        $this->_init->initialize();

        $this->assertTrue($this->_init->getFactory() instanceof Chrome_Database_Factory);
    }
}
