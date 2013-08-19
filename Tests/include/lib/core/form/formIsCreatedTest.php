<?php
require_once 'Tests/dummies/form/form.php';
require_once 'Tests/dummies/form/element/isCreated.php';

class FormIsCreatedTest extends Chrome_TestCase
{
    protected $_form;

    protected $_option;

    public function setUp()
    {
        $this->_form = new Test_Chrome_Form_No_Elements($this->_appContext);
        $this->_option = new Chrome_Form_Option_Element_Multiple();
    }

    protected function _addElementCheckbox()
    {
        $element = new Test_Chrome_Form_Element_isCreated($this->_form, 'created', $this->_option);
        $element->isCreated = true;
        $this->_form->addElement($element);
    }

    public function testIsCreatedSpecificElement()
    {
        $this->_addElementCheckbox();

        $this->assertTrue($this->_form->isCreated('created'));
        $this->assertSame($this->_form->isCreated('created'),$this->_form->isCreated('created'));
    }

    public function testIsCreated()
    {
        $this->_addElementCheckbox();

        $this->assertTrue($this->_form->isCreated());
        $this->assertSame($this->_form->isCreated(),$this->_form->isCreated());
    }

    public function testFormElementGetsOverwrittenWithSameId()
    {
        $this->_addElementCheckbox();
        $this->_addElementCheckbox();

        $this->assertSame(1, count($this->_form->getElements()));
        $this->assertTrue($this->_form->isCreated());
    }

    public function testFormIsNotCreated()
    {
        $errors = array('not created, test');
        $option = new Chrome_Form_Option_Element();
        $element = new Test_Chrome_Form_Element_isCreated($this->_form, 'notCreatedElement', $option);
        $element->isCreated = false;
        $element->errors = $errors;

        $this->_form->addElement($element);
        $this->_addElementCheckbox();
        $this->assertFalse($this->_form->isCreated(), 'form must be not created');
        $this->assertTrue($this->_form->isCreated('created'), 'element "created" must be created');
        $this->assertFalse($this->_form->isCreated('notCreatedElement'), 'ekenebt "notCreatedElement" must not be created');

        $this->assertSame(array('notCreatedElement' => $errors), $this->_form->getCreationErrors(), 'there must be only the errors from "notCreatedElement"');
        $this->assertSame(array(), $this->_form->getCreationErrors('created'), 'element "created" is created and thus has no errors');
        $this->assertSame($errors, $this->_form->getCreationErrors('notCreatedElement'), 'errors from "notCreatedElement" must be exactly the ones set previous..');
    }
}