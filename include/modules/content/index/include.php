<?php

namespace Chrome\Form\Module\Index;

class Index extends \Chrome\Form\AbstractForm
{
    protected function _init()
    {
        $this->_id = 'Index';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_GET);
        // this sets $this->_id to 'Index'
        $this->setAttribute(self::ATTRIBUTE_ID, 'Index');
        $this->setAttribute(self::ATTRIBUTE_ACTION, new \Chrome\Resource\Resource('static:index'));

        $lengthValidator = new \Chrome\Validator\String\LengthValidator();
        $lengthValidator->setOptions(array(\Chrome\Validator\String\LengthValidator::OPTION_MAX_LENGTH => 35,
                                            \Chrome\Validator\String\LengthValidator::OPTION_MIN_LENGTH => 1));

        $emptyValidator = new \Chrome\Validator\General\NotEmptyValidator();
        // $textValidators = array( $emptyValidator, $lengthValidator );

        $textValidators = new \Chrome\Validator\Composition\AndComposition();
        $textValidators->addValidators(array($emptyValidator, $lengthValidator));

        // form
        $storageSession = new \Chrome\Form\Storage\Session($this->_applicationContext->getRequestContext()->getSession(), $this->_id);
        $formElementOption = new \Chrome\Form\Option\Element\Form($storageSession);
        $formElementOption->setMaxAllowedTime(30)->setMinAllowedTime(1);

        $formElement = new \Chrome\Form\Element\Form($this, $this->_id, $formElementOption);
        $this->_addElement($formElement);

        // radio
        $radioOption = new \Chrome\Form\Option\MultipleElement();
        $radioOption->setAllowedValues(array('test', 'test2'));
        $radioOption->setReadonly(array('test'));
        $radioOption->setRequired(array('test2'));

        $radioElement = new \Chrome\Form\Element\Radio($this, 'radio', $radioOption);
        $this->_addElement($radioElement);

        // text
        $textOption = new \Chrome\Form\Option\Element();
        $textOption->setIsRequired(true);
        $textOption->setValidator($textValidators);

        $textElement = new \Chrome\Form\Element\Text($this, 'text', $textOption);
        $this->_addElement($textElement);

        // password
        $passwordOption = new \Chrome\Form\Option\Element();
        $passwordOption->setIsRequired(true);
        $passwordOption->setValidator($emptyValidator);

        $passwordElement = new \Chrome\Form\Element\Password($this, 'password', $passwordOption);
        $this->_addElement($passwordElement);

        // checkbox
        $checkboxOption = new \Chrome\Form\Option\MultipleElement();
        $checkboxOption->setAllowedValues(array('Value1', 'Value2', 'vAlue3'));
        $checkboxOption->setRequired(array('vAlue3', 'Value2'));
        $checkboxOption->setReadonly(array('Value1'));

        $checkboxElement = new \Chrome\Form\Element\Checkbox($this, 'checkbox', $checkboxOption);
        $this->_addElement($checkboxElement);

        // select
        $selectOption = new \Chrome\Form\Option\MultipleElement();
        $selectOption->setAllowedValues(array('Value1', 'Value2', 'Value3'));
        $selectOption->setSelectMultiple(true);
        $selectOption->setReadonly(array('Value2'));
        $selectOption->setRequired(array('Value3'));

        $selectElement = new \Chrome\Form\Element\Select($this, 'select', $selectOption);
        $this->_addElement($selectElement);

        // submit
        $submitOption = new \Chrome\Form\Option\Element();
        $submitOption->setAllowedValue('Absenden');

        $submitElement = new \Chrome\Form\Element\Submit($this, 'submit', $submitOption);
        $this->_addElement($submitElement);

        // this->_elements['birthday'] = new Chrome_Form_Element_Birthday($this, 'birthday', array());

        // $this->addReceivingHandler(new Chrome_Form_Handler_Delete());*/

        #$storage = new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);

        $storageOption = new \Chrome\Form\Option\Storage();
        #$storageOption->setStorageEnabled(true);
        #$storageOption->setStoreNullData(true);
        #$storageOption->setStoreInvalidData(false);

        $this->setAttribute(self::ATTRIBUTE_STORE, new \Chrome\Form\Handler\Store($storageSession, $storageOption, array('radio', 'select', 'text', 'checkbox')));
    }
}
