<?php

if( CHROME_PHP !== true ) die();

class Chrome_Form_Index extends Chrome_Form_Abstract
{
	public function __construct()
	{

		$this->_id = 'Index';
		$this->setAttribute( self::ATTRIBUTE_NAME, $this->_id );
		$this->setAttribute( self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST );
		$this->setAttribute( self::ATTRIBUTE_ID, 'Index' );
        $this->setAttribute( self::ATTRIBUTE_DECORATOR, 'Yaml');

		$lengthValidator = new Chrome_Validator_Form_Length();
		$lengthValidator->setOptions( array( Chrome_Validator_Form_Length::CHROME_VALIDATOR_FORM_LENGTH_MAX =>
				35, Chrome_Validator_Form_Length::CHROME_VALIDATOR_FORM_LENGTH_MIN => 1 ) );

		$emptyValidator = new Chrome_Validator_Form_Empty();
		$textValidators = array( $emptyValidator, $lengthValidator );

		// not needed anymore, is done by $this->setAttribute('method',..)
		//$this->setSentData(Chrome_Request::getInstance()->getPOSTParameter());

		$this->_elements[$this->_id] = new Chrome_Form_Element_Form( $this, $this->_id, array( Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME =>
				30, Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 1 ) );


		$this->_elements['radio'] = new Chrome_Form_Element_Radio( $this, 'radio', array(
			Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
            Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA => true,
			Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT => 'test',
			Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array( 'test', 'test2' ),
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_READONLY => array('test') ) );

		$this->_elements['text'] = new Chrome_Form_Element_Text( $this, 'text', array( Chrome_Form_Element_Text::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
				Chrome_Form_Element_Text::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => $textValidators ) );

		$this->_elements['password'] = new Chrome_Form_Element_Password( $this, 'password', array( Chrome_Form_Element_Password::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
				Chrome_Form_Element_Password::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => array( $emptyValidator ) ) );


		$this->_elements['checkbox'] = new Chrome_Form_Element_Checkbox( $this, 'checkbox', array( Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_SELECTION_OPTIONS =>
				array(
				'Value1',
				'Value2',
				'Value3' ),
                Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => array('Value1', 'Value2'),
                Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array(
                    Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT => array('Value1', 'Value2')
                ) ) );


		$this->_elements['select'] = new Chrome_Form_Element_Select( $this, 'select', array(
			Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array(
				'Value1',
				'Value2',
				'Value3' ),
			Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECT_MULTIPLE => true,
            Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_READONLY => array('Value2'),
			Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_IS_REQUIRED => false,
			Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array(
                Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT => array('Value1') ) ) );

        $this->_elements['submit'] = new Chrome_Form_Element_Submit($this, 'submit', array(
            Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES => array('Absenden')
        ));

        $this->_elements['birthday'] = new Chrome_Form_Element_Birthday($this, 'birthday', array());

        //$this->addReceivingHandler(new Chrome_Form_Handler_Delete());
	}
}
