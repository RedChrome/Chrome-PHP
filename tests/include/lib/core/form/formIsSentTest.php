<?php

namespace Test\Chrome\Form;

use Test\Chrome\Form\Element\IsCreated;

use Test\Chrome\Form\Element\IsSent;

require_once 'tests/dummies/form/form.php';
require_once 'tests/dummies/form/element/isSent.php';

class FormIsSentTest extends \Test\Chrome\TestCase
{
    protected $_form;

    protected $_option;

    public function setUp()
    {
        $this->_form = new EmptyForm($this->_appContext);
        $this->_option = new \Chrome\Form\Option\Element();
    }

    protected function _addElement()
    {
        $element = new IsSent($this->_form, 'sent', $this->_option);
        $element->isSent = true;
        $this->_form->addElement($element);
    }

    public function testIsSentRequiresIsCreated()
    {
        $notCreated = new IsCreated($this->_form, 'notCreated', $this->_option);
        $notCreated->isCreated = false;
        $notCreated->errors = array('test, not created');

        $this->_addElement();
        $this->_form->addElement($notCreated);

        $this->assertFalse($this->_form->isSent(), 'form must be not sent, because there is one element which is not created!');
        $this->assertTrue($this->_form->isSent('sent'), '"sent" element must be sent');
        $this->assertFalse($this->_form->isCreated(), 'the whole form cannot be created, "notCreated" was not created');
        $this->assertTrue($this->_form->isCreated('sent'), 'the element "sent" must be created');
        $this->assertFalse($this->_form->isCreated('notCreated'), 'the element "notCreated" must not be created');
        $this->assertFalse($this->_form->isSent('notCreated'), 'elemnent "notCreated" cannot be sent, if its not created');

        $this->assertSame($this->_form->isSent(), $this->_form->isSent(), 'form should always return the same result on isSent');

        $this->assertSame(array(), $this->_form->getReceivingErrors(), 'no receiving errors cause the form was not created');
    }

    public function testIsSent()
    {
        $errors = array('test, not sent');
        $this->_addElement();
        $notSent = new IsSent($this->_form, 'notSent', $this->_option);
        $notSent->isSent = false;
        $notSent->errors = $errors;
        $this->_form->addElement($notSent);

        $this->assertTrue($this->_form->isCreated(), 'form must be created');
        $this->assertFalse($this->_form->isSent('notSent'), 'element "notSent" must not be sent');
        $this->assertTrue($this->_form->isSent('sent'), 'element "sent" must be sent');
        $this->assertFalse($this->_form->isSent(), 'form must not be sent, element "notSent" was not sent');
        $this->assertSame($this->_form->isSent(), $this->_form->isSent());

        $this->assertSame(array(), $this->_form->getCreationErrors());
        $this->assertSame($errors, $this->_form->getReceivingErrors('notSent'));
        $this->assertSame(array('notSent' => $errors), $this->_form->getReceivingErrors());
        $this->assertSame(array(), $this->_form->getReceivingErrors('sent'));
        $this->assertTrue($this->_form->hasReceivingErrors('notSent'));
        $this->assertFalse($this->_form->hasReceivingErrors('sent'));
        $this->assertTrue($this->_form->hasReceivingErrors('notSent', $errors[0]));
        $this->assertFalse($this->_form->hasReceivingErrors('notSent', 'msg not existing...test, not sent'));
        $this->assertFalse($this->_form->hasReceivingErrors('sent', $errors[0]));
    }
}