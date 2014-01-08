<?php

class Test_Chrome_Request_Data_Form extends Chrome_Request_Data_Abstract
{

    public function __construct()
    {
    }

    public function getPOSTData($key = null)
    {
        return array('POST' => 'testPOST');
    }

    public function getGETData($key = null)
    {
        return array('GET' => 'testGET');
    }

    public function getData()
    {
        throw new Chrome_Exception('I was called!!!', 1);
    }
}

class GeneralFormTest extends Chrome_TestCase
{
    protected $_form;

    public function setUp()
    {
        $this->_form = new Test_Chrome_Form_No_Elements($this->_appContext);
    }

    public function testIfNoElementsAreAdded()
    {
        $this->assertSame(0, count($this->_form->getElements()));
        $this->assertSame($this->_appContext, $this->_form->getApplicationContext());
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
        $this->assertEquals($this->_form->getCreationErrors('notExisting'), array());
        $this->assertEquals($this->_form->getReceivingErrors('notExisting'), array());
        $this->assertEquals($this->_form->getValidationErrors('notExisting'), array());
        $this->assertEquals($this->_form->getErrors(), array());
        $this->assertEquals($this->_form->getAttribute('doesNotExist'), null);
        $this->assertInstanceOf('Chrome_Request_Data_Interface', $this->_form->getRequestData());
        $this->assertFalse($this->_form->hasReceivingErrors('notExisting'));
        $this->assertFalse($this->_form->hasCreationErrors('notExisting'));
        $this->assertFalse($this->_form->hasValidationErrors('notExisting'));
        $this->assertFalse($this->_form->hasErrors('notExisting'));
        $this->assertTrue(is_array($this->_form->getAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE)));

        // test action attribute
        $this->_form->setAttribute(Chrome_Form_Interface::ATTRIBUTE_ACTION, '/test.html');
        $firstAction = $this->_form->getAttribute(Chrome_Form_Interface::ATTRIBUTE_ACTION);
        $this->_form->setAttribute(Chrome_Form_Interface::ATTRIBUTE_ACTION, 'test.html');
        $this->assertSame($firstAction, $this->_form->getAttribute(Chrome_Form_Interface::ATTRIBUTE_ACTION), 'action attribute should trim /');
    }

    public function testSetAttributeStoreHandler()
    {
        $storage = new Chrome_Form_Storage_Session($this->_appContext->getRequestHandler()->getRequestData()->getSession(), 'testForm');
        $option = new Chrome_Form_Option_Storage();
        $storeHandler = new Chrome_Form_Handler_Store($storage, $option, array());
        $storeHandler2 = new Chrome_Form_Handler_Store($storage, $option, array());

        $this->_form->setAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE, $storeHandler);
        $this->_form->setAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE, $storeHandler);
        $this->_form->setAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE, $storeHandler2);

        $this->assertSame(array($storeHandler, $storeHandler, $storeHandler2), $this->_form->getAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE), 'store handler should be injected correctly');
    }

    public function testSetAttributeStoreHandlerThrowsExcpetionOnWrongStoreHandler()
    {
        $this->setExpectedException('Chrome_InvalidArgumentException');
        $this->_form->setAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE, 'string');
    }

    public function testSetAttributeStoreHandlerThrowsExcpetionOnWrongStoreHandler2()
    {
        $this->setExpectedException('Chrome_InvalidArgumentException');
        $this->_form->setAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE, $this);
    }

    public function testExceptionIfElementDoesNotExistInisSent()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_form->isSent('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisCreated()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_form->isCreated('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisValid()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_form->isValid('doesNotExist');
    }

    public function testExceptionIfNoIdIsSet()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_form->getID();
    }

    public function testAttributes()
    {
        $this->_form->setAttribute('test', true);
        $this->assertTrue($this->_form->getAttribute('test'));
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_ID, 'myTestId');
        $this->assertEquals('myTestId', $this->_form->getID());
        //TODO: add a proper action attribute test.
        /*
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_ACTION, 'myAction.html');
        $this->assertEquals( '/myAction.html', $this->_form->getAttribute(Chrome_Form_Abstract::ATTRIBUTE_ACTION));
        */
    }

    public function testFormWithRequestData()
    {
        $this->_form->setRequestData(new Test_Chrome_Request_Data_Form());
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, Chrome_Form_Abstract::CHROME_FORM_METHOD_GET);
        $this->assertTrue($this->_form->issetSentData('GET'));
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, Chrome_Form_Abstract::CHROME_FORM_METHOD_POST);
        $this->assertTrue($this->_form->issetSentData('POST'));
        $this->setExpectedException('Chrome_Exception', '', 1);
        $this->_form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, 'anyMethod...');
    }
}
