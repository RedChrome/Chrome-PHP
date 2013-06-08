<?php

;
require_once LIB.'core/language.php';
require_once LIB.'core/form/form.php';


require_once 'Tests/dummies/session.php';
require_once 'Tests/dummies/cookie.php';
require_once 'Tests/dummies/request/handler.php';

class Chrome_Form_Test_No_Elements extends Chrome_Form_Abstract
{
    protected function _init() {

    }
}

class Chrome_Request_Data_Test extends Chrome_Request_Data_Abstract
{
    public function __construct() {

    }

    public function getPOSTData($key = null) {
        return array('POST' => 'testPOST');
    }

    public function getGETData($key = null) {
        return array('GET' => 'testGET');
    }

    public function getData() {
        throw new Chrome_Exception('I was called!!!', 1);
    }
}

class FormTest extends PHPUnit_Framework_TestCase
{
    protected $_form;

    public function setUp() {

        $handler = new Chrome_Request_Handler_Dummy();
        $this->_form = new Chrome_Form_Test_No_Elements($handler);
    }

    public function testIfNoElementsAreAdded() {

        $this->assertTrue($this->_form->isSent());
        $this->assertTrue($this->_form->isCreated());
        $this->assertTrue($this->_form->isValid());
        $this->assertEquals(array(), $this->_form->getData());
        $this->assertFalse($this->_form->issetSentData('doesNotExist'));
        $this->assertEquals($this->_form->getElements('doesNotExist'), null);
        $this->assertEquals($this->_form->getElements(), array());
        $this->assertEquals($this->_form->getCreationErrors(), array());
        $this->assertEquals($this->_form->getReceivingErrors(), array());
        $this->assertEquals($this->_form->getValidationErrors(), array());
        $this->assertEquals($this->_form->getErrors(), array());
        $this->assertEquals($this->_form->getAttribute('doesNotExist'), null);
        $this->assertInstanceOf('Chrome_Request_Data_Interface', $this->_form->getRequestData());
        $this->assertFalse($this->_form->hasReceivingErrors(''));
        $this->assertFalse($this->_form->hasCreationErrors(''));
        $this->assertFalse($this->_form->hasValidationErrors(''));
        $this->assertFalse($this->_form->hasErrors(''));

    }

    public function testExceptionIfElementDoesNotExistInisSent() {

        $this->setExpectedException('Chrome_Exception');

        $this->_form->isSent('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisCreated() {

        $this->setExpectedException('Chrome_Exception');

        $this->_form->isCreated('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisValid() {

        $this->setExpectedException('Chrome_Exception');

        $this->_form->isValid('doesNotExist');
    }

    public function testExceptionIfNoIdIsSet() {

        $this->setExpectedException('Chrome_Exception');

        $this->_form->getID();
    }

    public function testAttributes() {

        $this->_form->setAttribute('test', true);
        $this->assertTrue($this->_form->getAttribute('test'));
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_ID, 'myTestId');
        $this->assertEquals($this->_form->getID(), 'myTestId');
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_ACTION, 'myAction.html');
        $this->assertEquals($this->_form->getAttribute('action'), 'myAction.html');
    }

    public function testFormWithRequestData() {


        $this->_form->setRequestData(new Chrome_Request_Data_Test());
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, Chrome_Form_Abstract::CHROME_FORM_METHOD_GET);
        $this->assertTrue($this->_form->issetSentData('GET'));
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, Chrome_Form_Abstract::CHROME_FORM_METHOD_POST);
        $this->assertTrue($this->_form->issetSentData('POST'));
        $this->setExpectedException('Chrome_Exception', '', 1);
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, 'anyMethod...');
    }
}