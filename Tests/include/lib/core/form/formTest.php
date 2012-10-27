<?php

require_once 'Tests/testsetup.php';
require_once LIB.'core/language.php';
require_once LIB.'core/form/form.php';
require_once LIB.'core/request/request.php';
class Chrome_Form_Test_No_Elements extends Chrome_Form_Abstract
{
    public function __construct() {

    }
}

class Chrome_Request_Data_Test extends Chrome_Request_Data_Abstract
{
    public function __construct() {

    }

    public function getPOST($key = null) {
        return array('POST' => 'testPOST');
    }

    public function getGET($key = null) {
        return array('GET' => 'testGET');
    }

    public function getData() {
        throw new Chrome_Exception('I was called!!!', 1);
    }
}

class FormTest extends PHPUnit_Framework_TestCase
{
    public function testIfNoElementsAreAdded() {
        $form = new Chrome_Form_Test_No_Elements();

        $this->assertTrue($form->isSent());
        $this->assertTrue($form->isCreated());
        $this->assertTrue($form->isValid());
        $this->assertEquals(array(), $form->getData());
        $this->assertFalse($form->issetSentData('doesNotExist'));
        $this->assertEquals($form->getElements('doesNotExist'), null);
        $this->assertEquals($form->getElements(), array());
        $this->assertEquals($form->getCreationErrors(), array());
        $this->assertEquals($form->getReceivingErrors(), array());
        $this->assertEquals($form->getValidationErrors(), array());
        $this->assertEquals($form->getErrors(), array());
        $this->assertEquals($form->getAttribute('doesNotExist'), null);
        $this->assertInstanceOf('Chrome_Request_Data_Interface', $form->getRequestData());
        $this->assertFalse($form->hasReceivingErrors(''));
        $this->assertFalse($form->hasCreationErrors(''));
        $this->assertFalse($form->hasValidationErrors(''));
        $this->assertFalse($form->hasErrors(''));

    }

    public function testExceptionIfElementDoesNotExistInisSent() {

        $this->setExpectedException('Chrome_Exception');
        $form = new Chrome_Form_Test_No_Elements();

        $form->isSent('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisCreated() {

        $this->setExpectedException('Chrome_Exception');
        $form = new Chrome_Form_Test_No_Elements();

        $form->isCreated('doesNotExist');
    }

    public function testExceptionIfElementDoesNotExistInisValid() {

        $this->setExpectedException('Chrome_Exception');
        $form = new Chrome_Form_Test_No_Elements();

        $form->isValid('doesNotExist');
    }

    public function testExceptionIfNoIdIsSet() {

        $this->setExpectedException('Chrome_Exception');
        $form = new Chrome_Form_Test_No_Elements();

        $form->getID();
    }

    public function testAttributes() {
        $form = new Chrome_Form_Test_No_Elements();

        $form->setAttribute('test', true);
        $this->assertTrue($form->getAttribute('test'));
        $form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_ID, 'myTestId');
        $this->assertEquals($form->getID(), 'myTestId');
        $form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_ACTION, 'myAction.html');
        $this->assertEquals($form->getAttribute('action'), 'myAction.html');
    }

    public function testFormWithRequestData() {

        $form = new Chrome_Form_Test_No_Elements();
        $form->setRequestData(new Chrome_Request_Data_Test());
        $form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, Chrome_Form_Abstract::CHROME_FORM_METHOD_GET);
        $this->assertTrue($form->issetSentData('GET'));
        $form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, Chrome_Form_Abstract::CHROME_FORM_METHOD_POST);
        $this->assertTrue($form->issetSentData('POST'));
        $this->setExpectedException('Chrome_Exception', '', 1);
        $form->setAttribute(Chrome_Form_Abstract::ATTRIBUTE_METHOD, 'Chrome_Form_Abstract::CHROME_FORM_METHOD_POST');
    }
}