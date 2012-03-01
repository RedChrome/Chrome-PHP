<?php

if(CHROME_PHP !== true)
    die();

class Chrome_Form_Index extends Chrome_Form_Abstract
{
    public function __construct()
    {

        $this->_id = 'Index';
        $this->setAttribute('name', $this->_id);
        $this->setAttribute('method', 'POST');
        $this->setAttribute('id', 'Index');

        $lengthValidator = new Chrome_Validator_Form_Length();
        $lengthValidator->setOptions(array(Chrome_Validator_Form_Length::CHROME_VALIDATOR_FORM_LENGTH_MAX => 35, Chrome_Validator_Form_Length::CHROME_VALIDATOR_FORM_LENGTH_MIN => 1));

        $emptyValidator = new Chrome_Validator_Form_Empty();
        $textValidators = array($emptyValidator, $lengthValidator);


        $this->setSentData(Chrome_Request::getInstance()->getPOSTParameter());


        $this->_elements[] = new Chrome_Form_Element_Form($this, $this->_id, array(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME => 30, Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 1));


        $this->_elements[] = new Chrome_Form_Element_Radio($this, 'radio', array(Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_IS_REQUIRED => true, Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_DEFAULT_SELECTION => 'test', Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array('test', 'test2')));

        $this->_elements[] = new Chrome_Form_Element_Text($this, 'text', array(Chrome_Form_Element_Text::CHROME_FORM_ELEMENT_IS_REQUIRED => true, Chrome_Form_Element_Text::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => $textValidators));

        $this->_elements[] = new Chrome_Form_Element_Password($this, 'password', array(Chrome_Form_Element_Password::CHROME_FORM_ELEMENT_IS_REQUIRED => true, Chrome_Form_Element_Password::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => array($emptyValidator)));


        $this->_elements[] = new Chrome_Form_Element_Checkbox($this, 'checkbox', array(Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array('Value1', 'Value2', 'Value3')));


        $this->_elements[] = new Chrome_Form_Element_Select($this, 'select', array(Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array('Value1', 'Value2', 'Value3'), Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECT_MULTIPLE => true, Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_IS_REQUIRED => false, Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_DEFAULT => array('Value1')));
    }
}
