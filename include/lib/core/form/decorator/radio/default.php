<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.10.2012 19:34:32] --> $
 */
if( CHROME_PHP !== true ) die();

/**
 * TODO: change attribute class if errors exists
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Radio_Default extends Chrome_Form_Decorator_Abstract
{
	private $_int = 0;

	public function render()
	{

		$array = $this->_formElement->getOptions( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_SELECTION_OPTIONS );
		$value = $array[$this->_int];

		$this->_int = ++$this->_int % sizeof( $array );

		$checked = '';
		$disabled = '';
		$readOnly = $this->_formElement->getOptions( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_READONLY );
		if( $readOnly === true or in_array( $value, $readOnly ) ) {
			$disabled = ' disabled="disabled"';
		}

		if( in_array( $value, ( array )$this->getOptions( Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT ) ) == true ) {
			$checked = 'checked="checked" ';
		}

		if( $this->_formElement->getOptions( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED )
			=== true ) {
			$this->setAttribute( 'required', 'required' );
			$checked = '';
		}

		$data = $this->_formElement->getData();

		if( in_array( $value, ( array )$data ) or in_array( $value, ( array )$this->_formElement->getSavedData
			() ) ) {
			$checked = 'checked="checked" ';
		} else {
			$checked = '';
		}

		$return = '<input type="radio" name="' . $this->_formElement->getID() . '" value="' . $value . '"' .
			$this->_getPreparedAttrs() . $checked . $disabled . '/>';

		if( ( $label = $this->getOption( self::CHROME_FORM_DECORATOR_LABEL ) ) !== null and isset( $label[$this->_int] ) ) {
			$return = '<label> ' . $return . ' '.$label[$this->_int] . '</label>';
		}

		return $return;
	}
}
