<?php

namespace Test\Chrome\Form;

require_once 'tests/dummies/form/form.php';
require_once 'tests/dummies/form/element/isValid.php';

class FormIsValidTest extends \Test\Chrome\TestCase
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
        $element = new \Test_Chrome_Form_Element_isValid($this->_form, 'valid', $this->_option);
        $element->isValid = true;
        $this->_form->addElement($element);
    }

    public function testIsValidRequiresIsSent()
    {
        $notSent = new \Test_Chrome_Form_Element_isSent($this->_form, 'notSent', $this->_option);
        $notSent->isSent = false;
        $notSent->errors = array('test, not sent');

        $this->_addElement();
        $this->_form->addElement($notSent);

        $this->assertFalse($this->_form->isValid(), 'form must not be valid, because there is one element which is not sent!');
        $this->assertTrue($this->_form->isValid('valid'), '"valid" element must be valid');
        $this->assertFalse($this->_form->isSent(), 'the whole form cannot be sent, "notSent" was not sent');
        $this->assertTrue($this->_form->isSent('valid'), 'the element "valid" must be valid');
        $this->assertFalse($this->_form->isSent('notSent'), 'the element "notSent" must not be sent');
        $this->assertFalse($this->_form->isValid('notSent'), 'elemnent "notSent" cannot be valid, if its not sent');

        $this->assertSame($this->_form->isValid(), $this->_form->isValid(), 'form should always return the same result on isValid');

        $this->assertSame(array(), $this->_form->getValidationErrors(), 'no validation errors cause the form was not sent');
    }

    public function testIsValid()
    {
        $errors = array('test, not valid');
        $this->_addElement();
        $notValid = new \Test_Chrome_Form_Element_isValid($this->_form, 'notValid', $this->_option);
        $notValid->isValid = false;
        $notValid->errors = $errors;
        $this->_form->addElement($notValid);

        $this->assertTrue($this->_form->isSent(), 'form must be sent');
        $this->assertFalse($this->_form->isValid('notValid'), 'element "notValid" must not be valid');
        $this->assertTrue($this->_form->isValid('valid'), 'element "valid" must be valid');
        $this->assertFalse($this->_form->isValid(), 'form must not be valid, element "notValid" was not valid');
        $this->assertSame($this->_form->isValid(), $this->_form->isValid());

        $this->assertSame(array(), $this->_form->getReceivingErrors());
        $this->assertSame($errors, $this->_form->getValidationErrors('notValid'));
        $this->assertSame(array('notValid' => $errors), $this->_form->getValidationErrors());
        $this->assertSame(array(), $this->_form->getValidationErrors('valid'));
        $this->assertTrue($this->_form->hasValidationErrors('notValid'));
        $this->assertFalse($this->_form->hasValidationErrors('valid'));
        $this->assertTrue($this->_form->hasValidationErrors('notValid', $errors[0]));
        $this->assertFalse($this->_form->hasValidationErrors('notValid', 'msg not existing...test, not sent'));
        $this->assertFalse($this->_form->hasValidationErrors('valid', $errors[0]));
    }

}