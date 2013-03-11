<?php

require_once 'Tests/testsetup.php';
require_once LIB . 'core/database/database.php';

class DatabaseLoaderTest extends PHPUnit_Framework_TestCase
{
    protected static $_sloader = null;

    protected $_loader = null;

    protected $_testData = array(array('Chrome_Database_Loader', false),
        array('Chrome_Database_Interface_Simple', true),
        array('Chrome_Database_Interface_simple', true),
        array('Chrome_Database_interface_simple', true),
        array('Chrome_Database_Result_assoc', true),
        array('Chrome_Database_Does_notExist', false),
        array('AnyClass', false)

        );

    public static function setUpBeforeClass() {
        self::$_sloader = new Chrome_Database_Loader();
    }

    public function setUp() {
        $this->_loader = self::$_sloader;
    }

    public function testLoadClass() {

        foreach($this->_testData as $key => $testData) {

            $result = $this->_loader->loadClass($testData[0]);

            $this->assertEquals($testData[1], $result, 'error in '.$key.' test data row');
        }
    }

    public function testConstructor() {

        $loader = new Chrome_Database_Loader();

        $this->assertTrue(array_search(array($loader, 'loadClass'), spl_autoload_functions()) !== false, 'loader was not added to autoload functions');
    }
}
