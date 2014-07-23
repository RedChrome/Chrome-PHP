<?php

namespace Test\Chrome\Form;

require_once 'tests/dummies/form/form.php';
require_once 'tests/dummies/form/element/isCreated.php';

class FormIsCreatedTest extends \Test\Chrome\TestCase
{
    protected $_form;

    protected $_option;

    public function setUp()
    {
        $this->_form = new \Test_Chrome_Form_No_Elements($this->_appContext);
        $this->_option = new \Chrome_Form_Option_Element();
    }

    protected function _addElement()
    {
        $element = new \Test_Chrome_Form_Element_isCreated($this->_form, 'created', $this->_option);
        $element->isCreated = true;
        $this->_form->addElement($element);
    }

    public function testIsCreatedSpecificElement()
    {
        $this->_addElement();

        $this->assertTrue($this->_form->isCreated('created'));
        $this->assertSame($this->_form->isCreated('created'), $this->_form->isCreated('created'));
    }

    public function testIsCreated()
    {
        $this->_addElement();

        $this->assertTrue($this->_form->isCreated());
        $this->assertSame($this->_form->isCreated(), $this->_form->isCreated());
    }

    public function testFormElementGetsOverwrittenWithSameId()
    {
        $this->_addElement();
        $this->_addElement();

        $this->assertSame(1, count($this->_form->getElements()));
        $this->assertTrue($this->_form->isCreated());
    }

    public function testFormIsNotCreated()
    {
        $errors = array('not created, test');
        $option = new \Chrome_Form_Option_Element();
        $element = new \Test_Chrome_Form_Element_isCreated($this->_form, 'notCreatedElement', $option);
        $element->isCreated = false;
        $element->errors = $errors;

        $this->_form->addElement($element);
        $this->_addElement();
        $this->assertFalse($this->_form->isCreated(), 'form must be not created');
        $this->assertTrue($this->_form->isCreated('created'), 'element "created" must be created');
        $this->assertFalse($this->_form->isCreated('notCreatedElement'), 'ekenebt "notCreatedElement" must not be created');

        $this->assertSame(array('notCreatedElement' => $errors), $this->_form->getCreationErrors(), 'there must be only the errors from "notCreatedElement"');
        $this->assertSame(array(), $this->_form->getCreationErrors('created'), 'element "created" is created and thus has no errors');
        $this->assertSame($errors, $this->_form->getCreationErrors('notCreatedElement'), 'errors from "notCreatedElement" must be exactly the ones set previous..');

        $this->assertTrue($this->_form->hasCreationErrors('notCreatedElement'), '"notCreatedElement" must have creation errors');
        $this->assertFalse($this->_form->hasCreationErrors('created'), '"created" must not have creation errors');
        $this->assertTrue($this->_form->hasCreationErrors('notCreatedElement', $errors[0]), '"notCreatedElement" must have this special error');
        $this->assertFalse($this->_form->hasCreationErrors('notCreatedElement', 'msg not existing...test, not sent'), '"notCreatedElement" does not have this special error');
        $this->assertFalse($this->_form->hasCreationErrors('created', $errors[0]), '"created" does not have any special error');
    }
}