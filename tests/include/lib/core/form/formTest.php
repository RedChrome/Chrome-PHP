<?php

namespace Test\Chrome\Form;

use Mockery as M;

class GeneralFormTest extends \Test\Chrome\TestCase
{
    protected $_form;

    public function setUp()
    {
        $this->_form = new EmptyForm($this->_appContext);
    }

    protected function _getRequest()
    {
        $mock = M::mock('\Psr\Http\Message\ServerRequestInterface');
        $mock->shouldReceive('getQueryParams')->andReturn(array('1' => 'testGET'));
        $mock->shouldReceive('getParsedBody')->andReturn(array('1' => 'testPOST'));

        return $mock;
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

        $this->assertEquals($this->_form->getElements(), array());
        $this->assertEquals($this->_form->getCreationErrors(), array());
        $this->assertEquals($this->_form->getReceivingErrors(), array());
        $this->assertEquals($this->_form->getValidationErrors(), array());
        $this->assertEquals($this->_form->getCreationErrors('notExisting'), array());
        $this->assertEquals($this->_form->getReceivingErrors('notExisting'), array());
        $this->assertEquals($this->_form->getValidationErrors('notExisting'), array());
        $this->assertEquals($this->_form->getErrors(), array());
        $this->assertEquals($this->_form->getAttribute('doesNotExist'), null);
        $this->assertInstanceOf('\Psr\Http\Message\ServerRequestInterface', $this->_form->getRequest());
        $this->assertFalse($this->_form->hasReceivingErrors('notExisting'));
        $this->assertFalse($this->_form->hasCreationErrors('notExisting'));
        $this->assertFalse($this->_form->hasValidationErrors('notExisting'));
        $this->assertFalse($this->_form->hasErrors('notExisting'));
        $this->assertTrue(is_array($this->_form->getAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE)));
    }

    public function testExceptionOnNonExistingElement()
    {
        $this->setExpectedException('\Chrome\Exception');
        $this->assertEquals($this->_form->getElements('doesNotExist'), null);
    }

    public function testSetAttributeStoreHandler()
    {
        $storage = new \Chrome\Form\Storage\Session($this->_session, 'testForm');
        $option = new \Chrome\Form\Option\Storage();
        $storeHandler = new \Chrome\Form\Handler\Store($storage, $option, array());
        $storeHandler2 = new \Chrome\Form\Handler\Store($storage, $option, array());

        $this->_form->setAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE, $storeHandler);
        $this->_form->setAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE, $storeHandler);
        $this->_form->setAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE, $storeHandler2);

        $this->assertSame(array($storeHandler, $storeHandler, $storeHandler2), $this->_form->getAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE), 'store handler should be injected correctly');
    }

    public function testSetAttributeStoreHandlerThrowsExcpetionOnWrongStoreHandler()
    {
        $this->setExpectedException('\Chrome\InvalidArgumentException');
        $this->_form->setAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE, 'string');
    }

    public function testSetAttributeStoreHandlerThrowsExcpetionOnWrongStoreHandler2()
    {
        $this->setExpectedException('\Chrome\InvalidArgumentException');
        $this->_form->setAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE, $this);
    }

    public function testExceptionIfElementDoesNotExistInisSent()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_form->isSent('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisCreated()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_form->isCreated('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisValid()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_form->isValid('doesNotExist');
    }

    public function testExceptionIfNoIdIsSet()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_form->getID();
    }

    public function testAttributes()
    {
        $this->_form->setAttribute('test', true);
        $this->assertTrue($this->_form->getAttribute('test'));
        $this->_form->setAttribute(\Chrome\Form\AbstractForm::ATTRIBUTE_ID, 'myTestId');
        $this->assertEquals('myTestId', $this->_form->getID());
        //TODO: add a proper action attribute test.
        /*
            $this->_form->setAttribute(\Chrome\Form\AbstractForm::ATTRIBUTE_ACTION, 'myAction.html');
            $this->assertEquals( '/myAction.html', $this->_form->getAttribute(\Chrome\Form\AbstractForm::ATTRIBUTE_ACTION));
        */
    }

    public function testFormWithRequestData()
    {
        $this->_form->setRequest($this->_getRequest());
        $this->_form->setAttribute(\Chrome\Form\AbstractForm::ATTRIBUTE_METHOD, \Chrome\Form\AbstractForm::CHROME_FORM_METHOD_GET);
        $this->assertTrue($this->_form->issetSentData('1'));
        $this->_form->setAttribute(\Chrome\Form\AbstractForm::ATTRIBUTE_METHOD, \Chrome\Form\AbstractForm::CHROME_FORM_METHOD_POST);
        $this->assertTrue($this->_form->issetSentData('1'));
        $this->setExpectedException('\Chrome\Exception', '', 0);
        $this->_form->setAttribute(\Chrome\Form\AbstractForm::ATTRIBUTE_METHOD, 'anyMethod...');
    }
}
