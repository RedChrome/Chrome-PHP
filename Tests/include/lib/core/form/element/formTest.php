<?php

require_once 'Tests/dummies/form/form.php';

class Test_Chrome_Form_Element_Form extends Test_Chrome_Form
{
    protected function _init()
    {

        //$formOption = new

    }


}

class FormElementFormTest extends Chrome_TestCase
{
    protected $_option = null;

    protected $_form = null;

    protected $_id = 'TEST_formElementForm_ID';

    public function setUp()
    {
        $this->_form = new Test_Chrome_Form_Element_Form($this->_appContext);
        // do not use the default session -> this would pollute the session namespace
        $storage = new Chrome_Form_Storage_Session(new Chrome_Session_Dummy(new Chrome_Cookie_Dummy(), Chrome_Hash::getInstance()), 'TEST_FORM_ELEMENT_FORM');
        $this->_option = new Chrome_Form_Option_Element_Form($storage);
    }

    public function testFormIsValidWithValidToken()
    {
        $token = mt_rand(0, 1000);
        $tokenNamespace = mt_rand(1, 10);

        $this->_option->setTokenNamespace($tokenNamespace);
        $this->_option->setMinAllowedTime(0);
        $this->_option->setToken($token);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);

        $this->_form->addElement($formElement);

        $this->_form->create();

        $sentData = array($tokenNamespace => $token);
        $this->_form->setSentData($sentData);

        $this->assertTrue($this->_form->isValid());
        $this->assertTrue($this->_form->getElements($this->_id)->isValid());
    }

    public function testFormIsInValidWithInValidToken()
    {
        $token = mt_rand(0, 1000);
        $tokenNamespace = mt_rand(1, 10);

        $this->_option->setTokenNamespace($tokenNamespace);
        $this->_option->setMinAllowedTime(0);
        $this->_option->setToken($token);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);

        $this->_form->addElement($formElement);

        $this->_form->create();

        $sentData = array($tokenNamespace => 10001);
        $this->_form->setSentData($sentData);

        $this->assertFalse($this->_form->isValid());
        $this->assertFalse($this->_form->getElements($this->_id)->isValid());
    }

    public function testFormIsInvalidWithMinAllowedTime()
    {
        $this->_option->setMinAllowedTime(2);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);

        $this->_form->addElement($formElement);

        $this->_form->create();
        // set the token to a value which will be right
        $sentData = array($this->_option->getTokenNamespace() => $this->_option->getToken());
        $this->_form->setSentData($sentData);
        // it is invalid, since the form was sent in less than 2 seconds...
        $this->assertFalse($this->_form->isValid());
    }

    public function testFormIsvalidWithMinAllowedTime()
    {
        $time = mt_rand(1, 100);

        $this->_option->setMinAllowedTime($time);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);

        $this->_form->addElement($formElement);

        $this->_form->create();
        // set the token to a value which will be right
        $sentData = array($this->_option->getTokenNamespace() => $this->_option->getToken());
        $this->_form->setSentData($sentData);


        //sleep($time);
        $this->_option->setTime(CHROME_TIME + $time);

        $this->assertTrue($this->_form->isValid());
    }

    public function testFormIsvalidWithMaxAllowedTime()
    {
        $time = mt_rand(1, 1000);

        $this->_option->setMinAllowedTime(0);
        $this->_option->setMaxAllowedTime($time);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);

        $this->_form->addElement($formElement);

        $this->_form->create();
        // set the token to a value which will be right
        $sentData = array($this->_option->getTokenNamespace() => $this->_option->getToken());
        $this->_form->setSentData($sentData);

        $this->_option->setTime(CHROME_TIME + $time - 1);

        $this->assertTrue($this->_form->isValid());
    }

    public function testFormIsInvalidWithMaxAllowedTime()
    {
        $time = mt_rand(1, 1000);

        $this->_option->setMinAllowedTime(0);
        $this->_option->setMaxAllowedTime($time);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);

        $this->_form->addElement($formElement);

        $this->_form->create();
        // set the token to a value which will be right
        $sentData = array($this->_option->getTokenNamespace() => $this->_option->getToken());
        $this->_form->setSentData($sentData);

        $this->_option->setTime(CHROME_TIME + $time + mt_rand(1, 100));

        $this->assertFalse($this->_form->isValid());
    }

    public function testFormWithRenewAndReset()
    {
        $this->_option->setMinAllowedTime(0);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);

        $this->assertFalse($formElement->isCreated());

        $this->_form->addElement($formElement);

        $formElement->reset();

        $this->_form->create();

        $token = $this->_option->getToken();

        $sentData = array($this->_option->getTokenNamespace() => $token);
        $this->_form->setSentData($sentData);

        $this->assertTrue($this->_form->isValid());

        // this clears the cache
        $this->_form->reset();
        // this creates a new token
        $this->_form->renew();

        // this may happen, but is really improbably
        $this->assertNotEquals($token, $this->_option->getToken(), 'called renew, but new calculated token was not different to the old one');

        $this->assertFalse($this->_form->isValid());

        $this->assertTrue($formElement->getStorage() instanceof Chrome_Form_Storage_Interface);

        $this->_form->reset();
        $sentData = array($this->_option->getTokenNamespace() => $this->_option->getToken());
        $this->_form->setSentData($sentData);
        $this->assertTrue($this->_form->isValid());

        $this->_form->reset();
        $this->_form->destroy();

        $this->assertFalse($this->_form->isValid());
    }

    public function testFormElementUsesOldToken()
    {
        $this->_option->setMinAllowedTime(0);

        $formElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $this->_option);
        $this->_form->addElement($formElement);

        $this->_form->create();

        $this->assertTrue($this->_form->isCreated());

        $this->assertNotNull($this->_option->getToken());

        $optionCloned = clone $this->_option;

        $optionCloned->setToken(null);
        $optionCloned->setStorage(new Chrome_Form_Storage_Session(new Chrome_Session_Dummy(new Chrome_Cookie_Dummy(), Chrome_Hash::getInstance()), 'TEST_FORM_ELEMENT_FORM'), $this->_id);
        $optionCloned->getStorage()->remove($this->_id);
        $this->assertNull($optionCloned->getStorage()->get($this->_id));
        $optionCloned->setStorage($this->_option->getStorage());

        $anotherFormElement = new Chrome_Form_Element_Form($this->_form, $this->_id, $optionCloned);

        $this->assertEquals($this->_option->getToken(), $optionCloned->getToken());
    }
}