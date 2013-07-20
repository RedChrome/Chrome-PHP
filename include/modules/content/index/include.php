<?php

if( CHROME_PHP !== true ) die();

class Chrome_Form_Index extends Chrome_Form_Abstract
{
	protected function _init()
	{
		$this->_id = 'Index';
		$this->setAttribute( self::ATTRIBUTE_NAME, $this->_id );
		$this->setAttribute( self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST );
        // this sets $this->_id to 'Index'
		$this->setAttribute( self::ATTRIBUTE_ID, 'Index' );

		$lengthValidator = new Chrome_Validator_Form_Length();
		$lengthValidator->setOptions( array(
            Chrome_Validator_Form_Length::CHROME_VALIDATOR_FORM_LENGTH_MAX => 35,
            Chrome_Validator_Form_Length::CHROME_VALIDATOR_FORM_LENGTH_MIN => 1
                                            ) );

		$emptyValidator = new Chrome_Validator_Form_Empty();
		//$textValidators = array( $emptyValidator, $lengthValidator );

        $textValidators = new Chrome_Validator_Composition_And();
        $textValidators->addValidators(array($emptyValidator, $lengthValidator ));


        // form
        $formElementOption = new Chrome_Form_Option_Element_Form(
            new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id) );
        $formElementOption->setMaxAllowedTime(30)->setMinAllowedTime(1);

        $formElement = new Chrome_Form_Element_Form($this, $this->_id, $formElementOption);
        $this->_addElement($formElement);


        // radio
        $radioOption = new Chrome_Form_Option_Element_Multiple();
        $radioOption->setAllowedValues(array('test', 'test2'));
        $radioOption->setReadonly(array('test'));
        $radioOption->setRequired(array('test2'));

        $radioElement = new Chrome_Form_Element_Radio($this, 'radio', $radioOption);
        $this->_addElement($radioElement);


        // text
        $textOption = new Chrome_Form_Option_Element();
        $textOption->setIsRequired(true);
        $textOption->setValidator($textValidators);

        $textElement = new Chrome_Form_Element_Text($this, 'text', $textOption);
        $this->_addElement($textElement);

        // password
        $passwordOption = new Chrome_Form_Option_Element();
        $passwordOption->setIsRequired(true);
        $passwordOption->setValidator($emptyValidator);

        $passwordElement = new Chrome_Form_Element_Password($this, 'password', $passwordOption);
        $this->_addElement($passwordElement);



        /*
		$this->_elements['checkbox'] = new Chrome_Form_Element_Checkbox( $this, 'checkbox', array(
            Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array(
				'Value1',
				'Value2',
				'Value3'                                                                ),
            Chrome_Form_Element_Abstract::IS_REQUIRED => array('Value1', 'Value2'),
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array(
                Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT => array('Value1', 'Value2')
                                                                                         )
                                                                                                ) );


		$this->_elements['select'] = new Chrome_Form_Element_Select( $this, 'select', array(
			Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array(
				'Value1',
				'Value2',
				'Value3'                                                              ),
			Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECT_MULTIPLE => true,
            Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_READONLY => array('Value2'),
			Chrome_Form_Element_Select::IS_REQUIRED => false,
			Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array(
                Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT => array('Value1')
                                                                                        )
                                                                                            ) );

        $this->_elements['submit'] = new Chrome_Form_Element_Submit($this, 'submit', array(
            Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES => array('Absenden')
                                                                                            ) );

        $this->_elements['birthday'] = new Chrome_Form_Element_Birthday($this, 'birthday', array());

        //$this->addReceivingHandler(new Chrome_Form_Handler_Delete());*/


        #$storage = new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);
        #$storageOption = new Chrome_Form_Storage_Option();
        #$this->setAttribute(ATTRIBUTE_STORE, new Chrome_Form_Handler_Store($storage, $storageOption, array('radio')));
	}
}
